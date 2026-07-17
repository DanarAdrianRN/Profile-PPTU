<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $data = [
            [
                'judul' => 'Pendaftaran Santri Baru 2026 Dibuka',
                'slug' => 'pendaftaran-santri-baru-2026-dibuka',
                'thumbnail' => 'assets/berita1.JPG',
                'isi_berita' => 'Yayasan Tarbiyatul Ulum secara resmi membuka pendaftaran santri baru tahun ajaran 2026-2027 untuk jenjang Madrasah Diniyah, SMP, dan SMK.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Pendaftaran',
                'status' => 'Publish',
                'tanggal_publish' => '2026-05-01',
            ],
            [
                'judul' => 'Kegiatan Santri di Lingkungan Pesantren',
                'slug' => 'kegiatan-santri-di-lingkungan-pesantren',
                'thumbnail' => 'assets/berita2.JPG',
                'isi_berita' => 'Program pendidikan dirancang untuk membentuk generasi berakhlak dan unggul dalam ilmu agama serta memiliki kemampuan akademik dan keterampilan modern.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Kegiatan',
                'status' => 'Publish',
                'tanggal_publish' => '2026-04-28',
            ],
            [
                'judul' => 'Pengumuman Hasil Seleksi Santri Baru 2026',
                'slug' => 'pengumuman-hasil-seleksi-santri-baru-2026',
                'thumbnail' => 'assets/berita3.JPG',
                'isi_berita' => 'Berikut pengumuman hasil seleksi santri baru 2026. Silakan cek informasi jadwal daftar ulang sesuai arahan panitia.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Pengumuman',
                'status' => 'Draft',
                'tanggal_publish' => '2026-05-12',
            ],
            [
                'judul' => 'Prestasi Santri dalam Ajang Kompetisi',
                'slug' => 'prestasi-santri-dalam-ajang-kompetisi',
                'thumbnail' => 'assets/berita4.JPG',
                'isi_berita' => 'Santri meraih prestasi pada berbagai lomba. Pencapaian ini menjadi motivasi untuk terus meningkatkan kualitas belajar dan pengembangan bakat.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Prestasi',
                'status' => 'Publish',
                'tanggal_publish' => '2026-05-10',
            ],
            [
                'judul' => 'Agenda Kegiatan Pesantren Ramadhan',
                'slug' => 'agenda-kegiatan-pesantren-ramadhan',
                'thumbnail' => 'assets/berita5.JPG',
                'isi_berita' => 'Agenda kegiatan Ramadhan diisi dengan pembelajaran kitab, tilawah, dan program sosial untuk meningkatkan kebersamaan serta akhlak mulia.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Kegiatan',
                'status' => 'Publish',
                'tanggal_publish' => '2026-04-15',
            ],
            [
                'judul' => 'Pengumuman Jadwal Tes Tahap Akhir',
                'slug' => 'pengumuman-jadwal-tes-tahap-akhir',
                'thumbnail' => 'assets/berita6.JPG',
                'isi_berita' => 'Jadwal tes tahap akhir sudah diumumkan. Peserta dapat mempersiapkan berkas dan hadir tepat waktu sesuai ketentuan panitia.',
                'penulis' => 'Admin Yayasan',
                'kategori' => 'Pengumuman',
                'status' => 'Draft',
                'tanggal_publish' => '2026-05-20',
            ],
        ];

        foreach ($data as $row) {
            
            \DB::table('beritas')->updateOrInsert(
                ['slug' => $row['slug']],
                array_merge($row, [
                    'thumbnail' => $row['thumbnail'],
                    'created_by_admin_id' => null,
                    'updated_by_admin_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

}
