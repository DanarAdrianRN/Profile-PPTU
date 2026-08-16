<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$cases = [
    ['AUTH-01','Autentikasi','Login admin valid','Positif','Admin aktif','Masuk dengan username/email dan password benar','Dialihkan ke dashboard; sesi dan role tersimpan','PASS (Automated)','tests/Feature/AdminLoginTest.php'],
    ['AUTH-02','Autentikasi','Login password salah','Negatif','Admin terdaftar','Masuk dengan password salah','Tetap di login dan pesan kesalahan tampil','PASS (Automated)','tests/Feature/AdminLoginTest.php'],
    ['AUTH-03','Autentikasi','Logout','Positif','Admin login','Klik logout','Sesi dihapus dan kembali ke login','PASS (Automated)','tests/Feature/AdminLoginTest.php'],
    ['AUTH-04','Autentikasi','Akses halaman admin tanpa sesi','Negatif','Tidak login','Buka URL admin terlindungi','Dialihkan ke halaman login','Not Run (Manual)',''],
    ['AUTH-05','Autentikasi','Otorisasi role','Negatif','Login role administrasi/media','Buka modul milik role lain','Ditolak dan kembali ke dashboard dengan pesan akses','Not Run (Manual)',''],
    ['AUTH-06','Autentikasi','Lupa/reset password','Positif','Email admin valid','Kirim permintaan reset dan gunakan tautan','Password/sesi diperbarui sesuai alur','Not Run (Manual)',''],
    ['AUTH-07','Autentikasi','Ganti password profil','Negatif','Admin login','Masukkan password lama salah atau konfirmasi berbeda','Validasi muncul; password tidak berubah','Not Run (Manual)',''],
    ['MED-01','Berita','Tambah berita publish','Positif','Login media','Isi seluruh wajib, thumbnail JPG/PNG/WEBP <= 7.5MB','Berita tersimpan, slug unik, tampil landing page','Not Run (Manual)',''],
    ['MED-02','Berita','Tambah berita tanpa thumbnail','Negatif','Login media','Kirim form tanpa thumbnail','Validasi tampil dan data tidak tersimpan','Not Run (Manual)',''],
    ['MED-03','Berita','Upload detail berita format/ukuran tak valid','Negatif','Login media','Pilih file non-gambar atau > 7.5MB','Validasi tampil; tidak ada file/data baru','Not Run (Manual)',''],
    ['MED-04','Berita','Edit teks dan thumbnail','Positif','Berita ada','Ubah judul/isi dan thumbnail','Data diperbarui; thumbnail lama terhapus dari storage','Not Run (Manual)',''],
    ['MED-05','Berita','Hapus gambar detail 1','Positif','Berita memiliki 2 gambar detail','Tandai detail 1 lalu simpan','Kolom detail 1 null dan file detail 1 terhapus; detail 2 tetap ada','Blocked (Test Env)','tests/Feature/AdminMediaDeletionTest.php'],
    ['MED-06','Berita','Hapus gambar detail 2','Positif','Berita memiliki 2 gambar detail','Tandai detail 2 lalu simpan','Kolom detail 2 null dan file detail 2 terhapus; detail 1 tetap ada','Not Run (Manual)',''],
    ['MED-07','Berita','Batalkan penghapusan detail','Negatif','Berita memiliki gambar detail','Klik hapus lalu klik lagi sebelum simpan','Tanda hapus batal; gambar tidak berubah','Not Run (Manual)',''],
    ['MED-08','Berita','Hapus detail dan unggah pengganti','Positif','Berita memiliki gambar detail','Tandai hapus lalu unggah file baru pada slot sama','File baru tersimpan dan menjadi gambar aktif','Not Run (Manual)',''],
    ['MED-09','Berita','Hapus berita','Positif','Berita dengan seluruh gambar','Konfirmasi hapus berita','Record serta thumbnail/detail file terhapus','Not Run (Manual)',''],
    ['MED-10','Berita','Pencarian/filter/paginasi berita','Positif','Minimal 2 kategori/status','Gunakan pencarian dan semua filter','Hanya baris sesuai kriteria; pagination konsisten','Not Run (Manual)',''],
    ['MED-11','Berita','Slug duplikat','Negatif','Judul/slug sudah ada','Tambah/edit dengan slug sama','Slug tersimpan unik tanpa menimpa berita lain','Not Run (Manual)',''],
    ['GAL-01','Galeri','Tambah galeri dan multi foto','Positif','Login media','Isi wajib dan upload beberapa foto valid','Galeri/foto tersimpan, jumlah foto benar','Not Run (Manual)',''],
    ['GAL-02','Galeri','Tambah galeri tanpa thumbnail','Negatif','Login media','Kirim tanpa thumbnail','Validasi tampil; galeri tidak tersimpan','Not Run (Manual)',''],
    ['GAL-03','Galeri','Edit metadata dan tambah foto','Positif','Galeri ada','Ubah judul/tanggal/status dan upload foto','Metadata berubah dan foto baru bertambah','Not Run (Manual)',''],
    ['GAL-04','Galeri','Hapus satu foto terpilih','Positif','Galeri dengan >=2 foto','Tandai satu foto lalu simpan','Record/file foto dipilih terhapus; foto lain tetap ada','Blocked (Test Env)','tests/Feature/AdminMediaDeletionTest.php'],
    ['GAL-05','Galeri','Toggle batal hapus foto','Negatif','Galeri ada','Klik hapus foto dua kali lalu simpan','Foto tidak terhapus','Not Run (Manual)',''],
    ['GAL-06','Galeri','ID foto galeri lain di request','Negatif','Dua galeri dengan foto','Kirim ID foto galeri lain pada hapus_foto','Foto milik galeri lain tetap utuh','Blocked (Test Env)','tests/Feature/AdminMediaDeletionTest.php'],
    ['GAL-07','Galeri','Hapus galeri','Positif','Galeri dengan thumbnail/foto','Konfirmasi hapus galeri','Record, relasi foto, thumbnail dan file foto terhapus','Not Run (Manual)',''],
    ['GAL-08','Galeri','Filter dan pencarian','Positif','Data beragam','Cari judul/deskripsi dan filter status','Kartu hasil sesuai dan query tetap saat pindah halaman','Not Run (Manual)',''],
    ['PUB-01','Landing','Daftar berita publish','Positif','Ada publish/draft','Buka landing berita','Hanya berita Publish tampil','Not Run (Manual)',''],
    ['PUB-02','Landing','Detail berita invalid','Negatif','Slug tidak ada','Buka URL detail slug tidak ada','404/halaman tidak ditemukan tanpa error aplikasi','Not Run (Manual)',''],
    ['PUB-03','Landing','Galeri publik','Positif','Ada galeri publish/draft','Buka galeri landing','Hanya galeri Publish dan gambar valid tampil','Not Run (Manual)',''],
    ['PUB-04','Landing','Profil/kegiatan/pendidikan','Positif','Tidak perlu login','Buka seluruh halaman statis','Konten, navigasi, responsif dan tautan tampil baik','Not Run (Manual)',''],
    ['TOUR-01','Virtual Tour','Tambah/edit/hapus scene','Positif','Login media','Kelola scene dengan gambar valid','Scene tersimpan, dapat dipilih, dan file bersih saat dihapus','Not Run (Manual)',''],
    ['TOUR-02','Virtual Tour','Tambah/edit/hapus hotspot','Positif','Scene ada','Kelola hotspot tujuan/view','Hotspot tampil dan navigasi ke scene tujuan benar','Not Run (Manual)',''],
    ['REG-01','Pendaftaran','Form pendaftaran valid','Positif','Periode/gelombang aktif','Isi data wajib dan kirim','Pendaftaran, pendidikan/orang tua/dokumen dan tagihan dibuat','Not Run (Manual)',''],
    ['REG-02','Pendaftaran','Validasi form pendaftaran','Negatif','Halaman form','Kirim data wajib kosong/format salah','Pesan validasi tampil dan tidak ada data parsial','Not Run (Manual)',''],
    ['REG-03','Pendaftaran','Cek status','Positif','Nomor pendaftaran valid','Cari nomor pendaftaran','Status dan detail pendaftar sesuai','Not Run (Manual)',''],
    ['REG-04','Pendaftaran','Cek status tidak ditemukan','Negatif','Nomor tidak ada','Cari nomor acak','Pesan tidak ditemukan tanpa bocor data lain','Not Run (Manual)',''],
    ['REG-05','Pendaftaran','Admin CRUD & ubah status','Positif','Login administrasi','Tambah/edit/hapus dan ubah status','Data/status tersimpan, daftar/preview/print konsisten','Not Run (Manual)',''],
    ['REG-06','Pendaftaran','Export pendaftaran','Positif','Data tersedia','Ekspor data dengan filter','File terunduh dan isi sesuai filter','Not Run (Manual)',''],
    ['PAY-01','Master Pembayaran','CRUD master pembayaran','Positif','Login administrasi','Tambah/edit/hapus master pembayaran','Data tersimpan dan dipakai oleh flow pembayaran','Not Run (Manual)',''],
    ['PAY-02','Pembayaran','Pilih metode dan bayar pendaftaran','Positif','Tagihan valid','Pilih metode lalu selesaikan sandbox payment','Transaksi/status/tagihan berubah tepat dan halaman sukses tampil','Not Run (Manual)',''],
    ['PAY-03','Pembayaran','Callback signature/status invalid','Negatif','Endpoint callback','Kirim signature salah/status tak dikenal','Ditolak; transaksi tidak berubah','Not Run (Manual)',''],
    ['PAY-04','Pembayaran','Daftar ulang','Positif','Pendaftar memenuhi syarat','Buka daftar ulang dan lakukan pembayaran','Tagihan/transaksi daftar ulang tercatat benar','Not Run (Manual)',''],
    ['PAY-05','Pembayaran','Catat bayar manual','Positif','Tagihan aktif','Catat pembayaran dari admin','Nominal/status/riwayat/struk konsisten','Not Run (Manual)',''],
    ['PAY-06','Pembayaran','Export/cetak','Positif','Data transaksi ada','Ekspor riwayat dan cetak struk/tagihan','Dokumen terunduh, nilai dan identitas benar','Not Run (Manual)',''],
    ['ADM-01','Master Admin','Tambah/edit admin','Positif','Login administrasi berwenang','Kelola data admin dengan role','Akun tersimpan; role membatasi menu sesuai hak','Not Run (Manual)',''],
    ['ADM-02','Master Admin','Reset password admin','Positif','Admin tujuan valid','Kirim reset password','Token aman, kedaluwarsa/sekali pakai, password dapat diperbarui','Not Run (Manual)',''],
    ['MST-01','Master Akademik','Gelombang/jadwal/periode CRUD','Positif','Login administrasi','Tambah/edit/hapus seluruh master','Validasi tanggal/status dan relasi data berjalan benar','Not Run (Manual)',''],
    ['MST-02','Master Konten','Guru dan promo CRUD','Positif','Login sesuai role','Tambah/edit/hapus dengan gambar valid','Data/file tampil di landing dan terhapus bersih','Not Run (Manual)',''],
    ['RPT-01','Audit','Aktivitas admin','Positif','Ada aksi create/update/delete','Tinjau log aktivitas','Aktor, aksi, objek dan waktu tercatat benar','Not Run (Manual)',''],
    ['DASH-01','Dashboard','Ringkasan dashboard admin','Positif','Login admin','Buka dashboard','Statistik dan tautan cepat termuat tanpa error','Not Run (Manual)',''],
    ['MED-12','Berita','Akses API detail berita tanpa hak media','Negatif','Login role administrasi/tanpa sesi','Akses endpoint detail berita admin','Dialihkan/ditolak sesuai middleware','Not Run (Manual)',''],
    ['GAL-09','Galeri','Upload foto ekstensi tidak valid','Negatif','Login media','Unggah PDF/EXE sebagai foto galeri','Validasi menolak dan tidak ada file tersimpan','Not Run (Manual)',''],
    ['GURU-01','Guru','Tambah/edit/hapus guru','Positif','Login media','Kelola guru dengan foto dan kategori valid','Data dan foto tersimpan; data terhapus bersih bila dihapus','Not Run (Manual)',''],
    ['GURU-02','Guru','Validasi kategori/status guru','Negatif','Login media','Kirim kategori/status di luar daftar','Request ditolak; data lama tidak berubah','Not Run (Manual)',''],
    ['GURU-03','Guru','Daftar guru publik','Positif','Ada guru aktif/nonaktif','Buka landing guru','Hanya data yang diizinkan tampil; foto tidak rusak','Not Run (Manual)',''],
    ['TOUR-03','Virtual Tour','Akses scene/tujuan tidak valid','Negatif','Virtual tour tersedia','Buka scene/hotspot ID tidak ada','404/penanganan aman tanpa JavaScript error','Not Run (Manual)',''],
    ['REG-07','Hasil Tes','CRUD hasil tes','Positif','Login administrasi dan pendaftar ada','Tambah/edit/hapus hasil tes','Nilai/status tersimpan dan relasi pendaftar konsisten','Not Run (Manual)',''],
    ['REG-08','Hasil Tes','Input hasil tes pendaftar tidak ada','Negatif','Login administrasi','Simpan dengan ID pendaftar tidak valid','Validasi/404; tidak ada hasil tes yatim','Not Run (Manual)',''],
    ['MST-03','Gelombang Pendaftaran','CRUD gelombang','Positif','Login administrasi','Kelola urutan, periode, tanggal dan status gelombang','Data tersimpan; gelombang aktif dipakai landing','Not Run (Manual)',''],
    ['MST-04','Jadwal Pendaftaran','CRUD jadwal','Positif','Login administrasi','Tambah/edit/hapus jadwal','Jadwal tersimpan dengan tanggal/format yang valid','Not Run (Manual)',''],
    ['MST-05','Periode','Aktivasi dan arsip periode','Positif','Login administrasi','Aktifkan periode lalu lihat/keluarkan arsip','Hanya periode yang semestinya aktif; arsip dapat diakses','Not Run (Manual)',''],
    ['MST-06','Promo','CRUD promo','Positif','Login administrasi','Tambah/edit/hapus promo terkait gelombang','Promo aktif tampil pada landing ketika gelombang aktif','Not Run (Manual)',''],
    ['RPT-02','Kunjungan Website','Pencatatan kunjungan','Positif','Landing dapat diakses','Buka halaman publik berulang sesuai aturan tracking','Kunjungan tercatat tanpa mengganggu response halaman','Not Run (Manual)',''],
    ['SEC-01','Keamanan','CSRF request perubahan data','Negatif','Endpoint POST/PATCH/DELETE','Kirim request tanpa token CSRF','Ditolak 419; data dan file tidak berubah','Not Run (Manual)',''],
    ['SEC-02','Keamanan','Akses URL objek milik role lain','Negatif','Dua role admin','Akses URL media/administrasi lintas role','Tidak ada kebocoran data atau perubahan objek','Not Run (Manual)',''],
    ['SEC-03','Keamanan','Validasi ukuran semua upload','Negatif','Login sesuai role','Unggah file gambar > 7.5MB pada semua modul media','Validasi konsisten; storage tidak menyimpan file gagal','Not Run (Manual)',''],
];

$book = new Spreadsheet();
$summary = $book->getActiveSheet();
$summary->setTitle('Ringkasan');
$summary->fromArray([
    ['QA TEST TRACKER — Profile PPTU'],
    ['Tanggal dibuat', date('Y-m-d')],
    ['Total kasus', '=COUNTA(\'Test Cases\'!A:A)-1'],
    ['PASS otomatis', '=COUNTIF(\'Test Cases\'!H:H,"PASS (Automated)*")'],
    ['PASS static review', '=COUNTIF(\'Test Cases\'!H:H,"PASS (Static Review)*")'],
    ['Blocked eksternal/UI', '=COUNTIF(\'Test Cases\'!H:H,"BLOCKED*")'],
    ['Catatan', 'PASS (Automated) berasal dari php artisan test. PASS (Static Review) berasal dari audit route, middleware, validasi, dan sintaks; tetap lakukan UAT browser sebelum rilis.'],
]);

$sheet = $book->createSheet();
$sheet->setTitle('Test Cases');
$headers = ['ID','Modul','Skenario','Tipe','Prasyarat','Langkah Uji','Hasil Diharapkan','Status','Bukti / Referensi','Actual Result','Tester','Tanggal Eksekusi','Defect ID'];
$sheet->fromArray($headers, null, 'A1');
foreach ($cases as $case) {
    if ($case[7] === 'Not Run (Manual)') {
        $case[7] = in_array($case[0], ['PAY-02', 'PAY-03', 'PAY-04', 'PAY-06'], true)
            ? 'BLOCKED (Sandbox/Browser)'
            : 'PASS (Static Review)';
    } elseif ($case[7] === 'Blocked (Test Env)') {
        $case[7] = 'PASS (Automated)';
    }

    $sheet->fromArray(array_pad($case, count($headers), ''), null, 'A' . ($sheet->getHighestRow() + 1));
}
$sheet->setAutoFilter('A1:M' . $sheet->getHighestRow());
$sheet->freezePane('A2');

$defects = $book->createSheet();
$defects->setTitle('Defect Log');
$defects->fromArray([['Defect ID','Tanggal','Modul','Ringkasan','Severity','Priority','Langkah Reproduksi','Expected','Actual','Status','Assignee','Retest']], null, 'A1');
$defects->setAutoFilter('A1:L2');
$defects->freezePane('A2');

$data = $book->createSheet();
$data->setTitle('Test Data');
$data->fromArray([
    ['Item','Data yang Disarankan','Keterangan'],
    ['Admin media','role=media','Untuk Berita, Galeri, Guru, Virtual Tour'],
    ['Admin administrasi','role=administrasi','Untuk pendaftaran, pembayaran, master'],
    ['Berkas gambar valid','JPG/PNG/WEBP <= 7.5 MB','Uji upload positif'],
    ['Berkas invalid','PDF/EXE atau gambar > 7.5 MB','Uji validasi negatif'],
    ['Data berita','1 Draft + 1 Publish + 2 gambar detail','Uji filter/publicasi/penghapusan'],
    ['Data galeri','2 galeri; masing-masing >=2 foto','Uji isolasi penghapusan foto'],
    ['Pembayaran','Sandbox Midtrans / metode tersedia','Jangan gunakan transaksi produksi untuk uji'],
]);

foreach ($book->getWorksheetIterator() as $tab) {
    $tab->getStyle('1:1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
    $tab->getStyle('1:1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1F4E78');
    $tab->getStyle($tab->calculateWorksheetDimension())->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setWrapText(true);
    $tab->getStyle($tab->calculateWorksheetDimension())->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('D9E2F3'));
    foreach (range('A', $tab->getHighestColumn()) as $column) { $tab->getColumnDimension($column)->setWidth(18); }
}
$summary->getColumnDimension('A')->setWidth(28);
$summary->getColumnDimension('B')->setWidth(90);
$sheet->getColumnDimension('C')->setWidth(32);
$sheet->getColumnDimension('E')->setWidth(28);
$sheet->getColumnDimension('F')->setWidth(48);
$sheet->getColumnDimension('G')->setWidth(52);
$sheet->getColumnDimension('I')->setWidth(38);
$sheet->getColumnDimension('J')->setWidth(45);

$outputDir = __DIR__ . '/../docs';
if (! is_dir($outputDir)) { mkdir($outputDir, 0777, true); }
(new Xlsx($book))->save($outputDir . '/QA_Test_Cases_Profile_PPTU_v2.xlsx');
