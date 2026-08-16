<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMediaDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function mediaSession(): array
    {
        $admin = Admin::create([
            'nama_lengkap' => 'Admin Media',
            'email' => 'media@example.test',
            'username' => 'adminmedia',
            'role' => 'media',
            'password' => bcrypt('password'),
        ]);

        return [
            'admin' => [
                'id' => $admin->id,
                'role' => 'media',
                'session_version' => 1,
            ],
        ];
    }

    public function test_admin_can_delete_selected_berita_detail_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('berita/gambar_detail/one.jpg', 'one');
        Storage::disk('public')->put('berita/gambar_detail/two.jpg', 'two');

        $berita = Berita::create([
            'judul' => 'Berita Uji', 'slug' => 'berita-uji', 'thumbnail' => 'berita/thumbnails/test.jpg',
            'gambar_detail_1' => 'berita/gambar_detail/one.jpg',
            'gambar_detail_2' => 'berita/gambar_detail/two.jpg',
            'isi_berita' => 'Isi berita', 'penulis' => 'Admin', 'kategori' => 'Kegiatan',
            'status' => 'Draft', 'tanggal_publish' => now(),
        ]);

        $response = $this->withSession($this->mediaSession())->post(route('berita.update', $berita), [
            'judul' => $berita->judul, 'kategori' => $berita->kategori, 'status' => $berita->status,
            'penulis' => $berita->penulis, 'isi_berita' => $berita->isi_berita,
            'hapus_gambar_detail' => json_encode(['gambar_detail_1']),
        ]);

        $response->assertRedirect(route('admin-berita'));
        $this->assertNull($berita->fresh()->gambar_detail_1);
        $this->assertSame('berita/gambar_detail/two.jpg', $berita->fresh()->gambar_detail_2);
        Storage::disk('public')->assertMissing('berita/gambar_detail/one.jpg');
        Storage::disk('public')->assertExists('berita/gambar_detail/two.jpg');
    }

    public function test_admin_can_delete_only_own_selected_gallery_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('galeri/foto/selected.jpg', 'selected');
        Storage::disk('public')->put('galeri/foto/other.jpg', 'other');

        $galeri = Galeri::create([
            'judul' => 'Galeri Uji', 'thumbnail' => 'galeri/thumbnail/test.jpg',
            'tanggal_kegiatan' => now(), 'status' => 'Draft',
        ]);
        $fotoTerpilih = GaleriFoto::create(['galeri_id' => $galeri->id, 'gambar' => 'galeri/foto/selected.jpg']);
        $galeriLain = Galeri::create([
            'judul' => 'Galeri Lain', 'thumbnail' => 'galeri/thumbnail/other.jpg',
            'tanggal_kegiatan' => now(), 'status' => 'Draft',
        ]);
        $fotoLain = GaleriFoto::create(['galeri_id' => $galeriLain->id, 'gambar' => 'galeri/foto/other.jpg']);

        $response = $this->withSession($this->mediaSession())->post(route('galeri.update', $galeri), [
            'judul' => $galeri->judul, 'tanggal_kegiatan' => now()->format('Y-m-d'), 'status' => 'Draft',
            'hapus_foto' => json_encode([$fotoTerpilih->id, $fotoLain->id]),
        ]);

        $response->assertRedirect(route('admin-galeri'));
        $this->assertDatabaseMissing('galeri_fotos', ['id' => $fotoTerpilih->id]);
        $this->assertDatabaseHas('galeri_fotos', ['id' => $fotoLain->id]);
        Storage::disk('public')->assertMissing('galeri/foto/selected.jpg');
        Storage::disk('public')->assertExists('galeri/foto/other.jpg');
    }
}
