<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    use LogsAdminActivity;

    public function index()
    {
        $beritas = Berita::query()
            ->orderByDesc('tanggal_publish')
            ->orderByDesc('id')
            ->get();

        return view('pages.admin.media.berita', compact('beritas'));
    }

    public function show(Berita $berita)
    {
        return response()->json([
            'data' => [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'slug' => $berita->slug,
                'thumbnail' => $berita->thumbnail,
                'gambar_detail_1' => $berita->gambar_detail_1,
                'gambar_detail_2' => $berita->gambar_detail_2,
                'isi_berita' => $berita->isi_berita,
                'blockquote' => $berita->blockquote,
                'penulis' => $berita->penulis,
                'tanggal_publish' => $berita->tanggal_publish?->format('Y-m-d'),
                'kategori' => $berita->kategori,
                'status' => $berita->status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $admin = session('admin');

        $validated = $request->validate([

            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:20'],
            'penulis' => ['required', 'string', 'max:255'],
            'tanggal_publish' => ['required', 'date'],
            'isi_berita' => ['required', 'string'],
            'blockquote' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],

            'thumbnail' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:7680'
            ],

            'gambar_detail_1' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:7680'
            ],

            'gambar_detail_2' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:7680'
            ],

        ]);

        $slug = $validated['slug']
            ?? Str::slug($validated['judul']);

        $thumbnailPath = $request->file('thumbnail')
            ->store('berita/thumbnails', 'public');

        $berita = Berita::create([
            'judul' => $validated['judul'],
            'slug' => $this->uniqueSlug($slug),
            'thumbnail' => $thumbnailPath,
            'gambar_detail_1' => $request->hasFile('gambar_detail_1')
                ? $request->file('gambar_detail_1')->store('berita/gambar_detail', 'public')
                : null,
            'gambar_detail_2' => $request->hasFile('gambar_detail_2')
                ? $request->file('gambar_detail_2')->store('berita/gambar_detail', 'public')
                : null,
            'isi_berita' => $validated['isi_berita'],
            'blockquote' => $validated['blockquote'] ?? null,
            'penulis' => $validated['penulis'],
            'tanggal_publish' => $validated['tanggal_publish'],
            'kategori' => $validated['kategori'],
            'status' => $validated['status'],
            'created_by_admin_id' => $admin['id'] ?? null,
        ]);

        $this->catatAktivitas('create', 'Menambahkan berita: ' . $berita->judul, $berita);

        return redirect()
            ->route('admin-berita')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    public function update(Request $request, Berita $berita)
    {
        $admin = session('admin');
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:20'],
            'penulis' => ['required', 'string', 'max:255'],
            'isi_berita' => ['required', 'string'],
            'blockquote' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:7680'],
            'gambar_detail_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:7680'],
            'gambar_detail_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:7680'],
            'hapus_gambar_detail' => ['nullable', 'string'],
        ]);

        $slug = $validated['slug']
            ?? Str::slug($validated['judul']);

        $thumbnailPath = $berita->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $this->deleteStoredFile($berita->thumbnail);

            $thumbnailPath = $request->file('thumbnail')
                ->store('berita/thumbnails', 'public');
        }

        $gambarDetail1Path = $berita->gambar_detail_1;
        if ($request->hasFile('gambar_detail_1')) {
            $this->deleteStoredFile($berita->gambar_detail_1);

            $gambarDetail1Path = $request->file('gambar_detail_1')
                ->store('berita/gambar_detail', 'public');
        }

        $gambarDetail2Path = $berita->gambar_detail_2;
        if ($request->hasFile('gambar_detail_2')) {
            $this->deleteStoredFile($berita->gambar_detail_2);

            $gambarDetail2Path = $request->file('gambar_detail_2')
                ->store('berita/gambar_detail', 'public');
        }

        $gambarDetailUntukDihapus = collect(json_decode($request->input('hapus_gambar_detail', '[]'), true))
            ->filter(fn ($field) => in_array($field, ['gambar_detail_1', 'gambar_detail_2'], true))
            ->unique()
            ->all();

        foreach ($gambarDetailUntukDihapus as $field) {
            if ($request->hasFile($field)) {
                continue;
            }

            $path = $field === 'gambar_detail_1' ? $gambarDetail1Path : $gambarDetail2Path;
            $this->deleteStoredFile($path);

            if ($field === 'gambar_detail_1') {
                $gambarDetail1Path = null;
            } else {
                $gambarDetail2Path = null;
            }
        }

        $berita->update([
            'judul' => $validated['judul'],
            'slug' => $this->uniqueSlug($slug, $berita->id),
            'thumbnail' => $thumbnailPath,
            'gambar_detail_1' => $gambarDetail1Path,
            'gambar_detail_2' => $gambarDetail2Path,
            'isi_berita' => $validated['isi_berita'],
            'blockquote' => $validated['blockquote'] ?? null,
            'penulis' => $validated['penulis'],
            'kategori' => $validated['kategori'],
            'status' => $validated['status'],
            'updated_by_admin_id' => $admin['id'] ?? null,
        ]);

        $this->catatAktivitas('update', 'Memperbarui berita: ' . $berita->judul, $berita);

        if ($validated['status'] === 'Publish') {
        $data['tanggal_publish'] = now();
        }
        
        return redirect()
            ->route('admin-berita')
            ->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(Berita $berita)
    {
        $judulBerita = $berita->judul;

        foreach ([
            $berita->thumbnail,
            $berita->gambar_detail_1,
            $berita->gambar_detail_2,
        ] as $path) {
            $this->deleteStoredFile($path);
        }

        $berita->delete();

        $this->catatAktivitas('delete', 'Menghapus berita: ' . $judulBerita);

        return redirect()
            ->route('admin-berita')
            ->with('success', 'Berita berhasil dihapus');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug);
        $candidate = $base;

        $i = 1;
        while (
            Berita::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $candidate)
                ->exists()
        ) {
            $candidate = $base . '-' . $i;
            $i++;
        }

        return $candidate;
    }
}
