<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    use LogsAdminActivity;

    public function index(Request $request)
    {
        $query = Galeri::query();

        $query->with('fotos')
            ->withCount('fotos');

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'judul',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'deskripsi',
                    'like',
                    '%' . $request->search . '%'
                );

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $perPage = 6;

        $galeris = $query
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $jumlahFoto = GaleriFoto::count();
        $jumlahGaleri = Galeri::count();

        return view(
            'pages.admin.media.galeri',
            compact(
                'galeris',
                'jumlahFoto',
                'jumlahGaleri'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'thumbnail' => 'required|image|max:7680',
            'tanggal_kegiatan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|in:Publish,Draft',
            'fotos' => 'array',
            'fotos.*' => 'required|image|max:7680',
        ]);


        $adminId = session('admin')['id'] ?? null;

        $galeri = Galeri::create([
            'judul' => $request->judul,
            'thumbnail' => $request->file('thumbnail')->store('galeri/thumbnail', 'public'),
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'deskripsi' => $request->filled('deskripsi') ? $request->deskripsi : null,
            'status' => $request->input('status', 'Publish'),
            'created_by_admin_id' => $adminId,
            'updated_by_admin_id' => $adminId,
        ]);



        foreach (($request->file('fotos') ?? []) as $foto) {
            GaleriFoto::create([
                'galeri_id' => $galeri->id,
                'gambar' => $foto->store('galeri/foto', 'public'),
            ]);
        }

        $this->catatAktivitas('create', 'Menambahkan galeri: ' . $galeri->judul, $galeri);

        return redirect()->route('admin-galeri')
            ->with('success', 'Galeri berhasil ditambahkan dan siap dikelola.');
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'thumbnail' => 'nullable|image|max:7680',
            'tanggal_kegiatan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'status' => 'nullable|in:Publish,Draft',
            'fotos' => 'array',
            'fotos.*' => 'required|image|max:7680',
            'hapus_foto' => 'nullable|string',
        ]);

        $adminId = session('admin')['id'] ?? null;

        if ($request->hasFile('thumbnail')) {
            $this->deleteStoredFile($galeri->thumbnail);

            $galeri->thumbnail = $request->file('thumbnail')
                ->store('galeri/thumbnail', 'public');
        }

        if ($request->hapus_foto) {
            $ids = collect(json_decode($request->hapus_foto, true))
                ->filter(fn ($id) => is_numeric($id))
                ->map(fn ($id) => (int) $id)
                ->all();

            $fotos = $galeri->fotos()
                ->whereIn('id', $ids)
                ->get();

            foreach ($fotos as $foto) {
                $this->deleteStoredFile($foto->gambar);
                $foto->delete();
            }
        }

        $galeri->judul = $request->judul;
        $galeri->tanggal_kegiatan = $request->tanggal_kegiatan;
        $galeri->deskripsi = $request->deskripsi;
        $galeri->status = $request->input('status', 'Publish');
        $galeri->updated_by_admin_id = $adminId;

        foreach (($request->file('fotos') ?? []) as $foto) {
            GaleriFoto::create([
                'galeri_id' => $galeri->id,
                'gambar' => $foto->store('galeri/foto', 'public'),
            ]);
        }

        $galeri->save();

        $this->catatAktivitas('update', 'Memperbarui galeri: ' . $galeri->judul, $galeri);

        return redirect()
            ->route('admin-galeri')
            ->with('success', 'Galeri berhasil diperbarui');
    }

    public function destroy($id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);

        foreach ($galeri->fotos as $foto) {
            $this->deleteStoredFile($foto->gambar);
        }

        $this->deleteStoredFile($galeri->thumbnail);

        $judulGaleri = $galeri->judul;
        $galeri->fotos()->delete();
        $galeri->delete();

        $this->catatAktivitas('delete', 'Menghapus galeri: ' . $judulGaleri);

        return back()->with('success', 'Galeri berhasil dihapus');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
