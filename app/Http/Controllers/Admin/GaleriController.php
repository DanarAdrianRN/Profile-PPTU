<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
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
        ]);

        $adminId = session('admin')['id'] ?? null;

        if ($request->hasFile('thumbnail')) {
            if ($galeri->thumbnail) {
                Storage::disk('public')->delete($galeri->thumbnail);
            }

            $galeri->thumbnail = $request->file('thumbnail')
                ->store('galeri/thumbnail', 'public');
        }

        if ($request->hapus_foto) {
            $ids = json_decode($request->hapus_foto, true);
            $fotos = GaleriFoto::whereIn('id', $ids)->get();

            foreach ($fotos as $foto) {
                Storage::disk('public')->delete($foto->gambar);
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

        return redirect()
            ->route('admin-galeri')
            ->with('success', 'Galeri berhasil diperbarui');
    }

    public function destroy($id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);
        // hapus foto dari storage
        foreach ($galeri->fotos as $foto) {
            if ($foto->gambar) {
                Storage::disk('public')->delete($foto->gambar);
            }
        }
        if ($galeri->thumbnail) {
            Storage::disk('public')->delete($galeri->thumbnail);
        }
        $galeri->delete();
        return back()->with('success', 'Galeri berhasil dihapus');
    }
}
