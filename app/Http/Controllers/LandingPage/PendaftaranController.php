<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pendaftaran;
use App\Models\PendaftaranPendidikan;
use App\Models\PendaftaranOrangTua;
use App\Models\PendaftaranDokumen;
use App\Models\Transaksi;
use App\Models\Pembayaran;
use App\Models\TagihanSantri;
use App\Models\TagihanSantriDetail;
use App\Models\GelombangPendaftaran;
use App\Models\Periode;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $gelombangAktif = GelombangPendaftaran::aktif()
            ->orderBy('urutan')
            ->first();

        if (! $gelombangAktif) {
            return back()->withErrors([
                'gelombang' => 'Pendaftaran saat ini sudah ditutup. Silakan menghubungi admin untuk informasi lebih lanjut.',
            ]);
        }

        $pendaftarans = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])
        ->latest()
        ->get();

        return view(
            'pages.landing-page.pendaftaran.form-pendaftaran',
            compact('pendaftarans')
        );
    }

    public function cekStatus()
    {
        return view('pages.landing-page.cek-status.cek-pendaftaran', [
            'showModal' => false,
        ]);
    }

    public function cariStatus(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string',
            'nisn' => 'required|string',
        ]);

        $namaLengkap = trim($validated['nama_lengkap']);
        $nisn = trim($validated['nisn']);

        $pendaftaran = Pendaftaran::query()
            ->whereHas('pendidikan', function ($query) use ($nisn) {
                $query->where('nisn', $nisn);
            })
            ->whereRaw('LOWER(nama_lengkap) = ?', [
                Str::lower($namaLengkap),
            ])
            ->first();

        if (! $pendaftaran) {
            return back()
                ->withInput()
                ->withErrors([
                    'cek_status' => 'Data pendaftaran tidak ditemukan. Pastikan nama lengkap dan NISN sudah sesuai.',
                ]);
        }

        return redirect()->route('detail-cek', $pendaftaran->id);
    }

    public function detailCek(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load([
            'pendidikan',
            'orangTuas',
            'hasilTes',
        ]);

        return view('pages.landing-page.cek-status.detail-cek', [
            'showModal' => false,
            'pendaftaran' => $pendaftaran,
            'orangTua' => $pendaftaran->orangTuas->firstWhere('tipe', 'ayah')
                ?? $pendaftaran->orangTuas->firstWhere('tipe', 'ibu')
                ?? $pendaftaran->orangTuas->firstWhere('tipe', 'wali'),
            'hasilTes' => $pendaftaran->hasilTes,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $gelombangAktif = GelombangPendaftaran::aktif()
            ->orderBy('urutan')
            ->first();

        if (! $gelombangAktif) {
            return back()->withErrors([
                'gelombang' => 'Pendaftaran saat ini sudah ditutup. Silakan menghubungi admin untuk informasi lebih lanjut.',
            ]);
        }

        $request->validate([

            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',

            'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ortu'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kk'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nisn_file'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kip'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'foto_warna'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'foto_bw'        => 'nullable|image|mimes:jpg,jpeg,png|max:5120',

        ]);

        /*
        |--------------------------------------------------------------------------
        | CEGAH DATA DOBEL: kalau NISN ini sudah punya pendaftaran yang belum
        | dibayar, arahkan ke situ lagi untuk lanjut bayar — jangan buat baris
        | pendaftaran baru.
        |--------------------------------------------------------------------------
        */
        if ($request->filled('nisn')) {
            $pendaftaranBelumBayar = Pendaftaran::where('status', 'belum_bayar')
                ->whereHas('pendidikan', function ($query) use ($request) {
                    $query->where('nisn', $request->nisn);
                })
                ->latest()
                ->first();

            if ($pendaftaranBelumBayar) {
                $transaksiLama = Transaksi::where('pendaftaran_id', $pendaftaranBelumBayar->id)
                    ->where(function ($query) {
                        $query->whereNull('order_id')
                            ->orWhereIn('status', ['pending', 'expire', 'cancel', 'deny']);
                    })
                    ->latest()
                    ->first();

                if ($transaksiLama) {
                    return redirect()
                        ->route('pembayaran-pendaftaran', $transaksiLama->id)
                        ->with('info', 'Kamu sudah pernah mendaftar dengan NISN ini dan belum menyelesaikan pembayaran. Silakan lanjutkan pembayaran di bawah.');
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENDAFTARAN
        |--------------------------------------------------------------------------
        */
        $pendaftaran = Pendaftaran::create([

            'gelombang_pendaftaran_id' => $gelombangAktif?->id,
            'periode_id' => Periode::aktif()->value('id'),

            'kode_pendaftaran' => 'PSB-' . strtoupper(Str::random(6)),

            'status' => 'belum_bayar',

            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,

            'jenis_kelamin' => $request->jenis_kelamin,

            'agama' => $request->agama,

            'tempat_lahir' => $request->tempat_lahir,

            'tanggal_lahir' => $request->tanggal_lahir,

            'kewarganegaraan' => $request->kewarganegaraan,

            'anak_ke' => $request->anak_ke,

            'jumlah_saudara_kandung' => $request->jumlah_saudara_kandung,

            'jumlah_saudara_angkat' => $request->jumlah_saudara_angkat,

            'jumlah_saudara_tiri' => $request->jumlah_saudara_tiri,

            'status_anak' => $request->status_anak,

            'bahasa_rumah' => $request->bahasa_rumah,

            'alamat' => $request->alamat,

            'rt_rw' => $request->rt_rw,

            'desa' => $request->desa,

            'kecamatan' => $request->kecamatan,

            'kabupaten' => $request->kabupaten,

            'tempat_tinggal' => $request->tempat_tinggal,

            'jarak_rumah' => $request->jarak_rumah,

            'no_hp_ortu' => $request->no_hp_ortu,

            'berat_badan' => $request->berat_badan,

            'tinggi_badan' => $request->tinggi_badan,

            'riwayat_penyakit' => $request->riwayat_penyakit,

            'kelainan_jasmani' => $request->kelainan_jasmani,

            'kemampuan_quran' => $request->kemampuan_quran,

            'hafalan' => $request->hafalan,

            'baca_pegon' => $request->baca_pegon,

            'tulis_pegon' => $request->tulis_pegon,

            'bakat_prestasi' => $request->bakat_prestasi,

            'ekstrakurikuler' => $request->ekstrakurikuler,

            'size_seragam_pondok' => $request->size_seragam_pondok,

            'size_seragam_formal' => $request->size_seragam_formal,

            'sumber_info' => $request->sumber_info,

        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PENDIDIKAN
        |--------------------------------------------------------------------------
        */
        PendaftaranPendidikan::create([

            'pendaftaran_id' => $pendaftaran->id,

            'jenjang_pendidikan' => $request->jenjang_pendidikan,

            'jurusan' => $request->jurusan,

            'sekolah_asal' => $request->sekolah_asal,

            'tahun_lulus' => $request->tahun_lulus,

            'tanggal_nomor_ijazah' => $request->tanggal_nomor_ijazah,

            'nisn' => $request->nisn,

            'lama_belajar' => $request->lama_belajar,

        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA AYAH
        |--------------------------------------------------------------------------
        */
        PendaftaranOrangTua::create([

            'pendaftaran_id' => $pendaftaran->id,

            'tipe' => 'ayah',

            'nama' => $request->nama_ayah,

            'status' => $request->status_ayah,

            'tempat_lahir' => $request->tempat_lahir_ayah,

            'tanggal_lahir' => $request->tanggal_lahir_ayah,

            'agama' => $request->agama_ayah,

            'pendidikan' => $request->pendidikan_ayah,

            'pekerjaan' => $request->pekerjaan_ayah,

            'penghasilan' => $request->penghasilan_ayah,

            'alamat' => $request->alamat_ayah,

        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA IBU
        |--------------------------------------------------------------------------
        */
        PendaftaranOrangTua::create([

            'pendaftaran_id' => $pendaftaran->id,

            'tipe' => 'ibu',

            'nama' => $request->nama_ibu,

            'status' => $request->status_ibu,

        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA WALI
        |--------------------------------------------------------------------------
        */
        PendaftaranOrangTua::create([

            'pendaftaran_id' => $pendaftaran->id,

            'tipe' => 'wali',

            'nama' => $request->nama_wali,

            'pekerjaan' => $request->pekerjaan_wali,

            'alamat' => $request->alamat_wali,

        ]);

        /*
        |--------------------------------------------------------------------------
        | DOKUMEN
        |--------------------------------------------------------------------------
        */
        $documents = [

            'akta_kelahiran' => 'Akta Kelahiran',
            'ktp_ortu' => 'KTP Orang Tua',
            'kk' => 'Kartu Keluarga',
            'ijazah' => 'Ijazah',
            'nisn_file' => 'NISN',
            'kip' => 'KIP',
            'foto_warna' => 'Foto Warna',
            'foto_bw' => 'Foto Hitam Putih',

        ];

        foreach ($documents as $field => $jenis) {

            if ($request->hasFile($field)) {

                $path = $request
                    ->file($field)
                    ->store('pendaftaran', 'public');

                PendaftaranDokumen::create([

                    'pendaftaran_id' => $pendaftaran->id,

                    'jenis_dokumen' => $jenis,

                    'file' => $path,

                ]);
            }
        }
        
        
        $pembayaran = Pembayaran::where(
            'jenjang',
            $request->jenjang_pendidikan
        )
        ->where(
            'nama_pembayaran',
            'like',
            'Pendaftaran Pondok'
        )
        ->where(
            'is_active',
            true
        )
        ->first();

        if (! $pembayaran) {
            return back()->withErrors([
                'jenjang_pendidikan' => 'Biaya Pendaftaran Pondok untuk jenjang ini belum diatur admin. Silakan hubungi pihak yayasan.',
            ])->withInput();
        }

        // Setiap santri hanya punya satu TagihanSantri (relasinya unik per
        // pendaftaran) — di titik ini pasti belum ada, jadi dibuat sekalian
        // supaya biaya Pendaftaran Pondok tercatat sebagai item tagihan yang
        // sah, bukan cuma transaksi lepas tanpa rincian.
        $gelombangId = GelombangPendaftaran::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderBy('urutan')
            ->value('id')
            ?? GelombangPendaftaran::aktif()->orderBy('urutan')->value('id');

        $tagihan = TagihanSantri::firstOrCreate(
            ['pendaftaran_id' => $pendaftaran->id],
            [
                'gelombang_pendaftaran_id' => $gelombangId,
                'kode_tagihan' => 'TAG-' . now()->format('YmdHis') . '-' . $pendaftaran->id,
                'jenjang' => $request->jenjang_pendidikan,
                'boleh_dicicil' => true,
                'jumlah_cicilan' => 1,
                'jatuh_tempo' => now()->addDays(7),
            ]
        );

        $tagihanDetail = TagihanSantriDetail::create([
            'tagihan_santri_id' => $tagihan->id,
            'pembayaran_id' => $pembayaran->id,
            'nama_pembayaran' => $pembayaran->nama_pembayaran,
            'kategori' => $pembayaran->kategori,
            'nominal_awal' => $pembayaran->nominal,
            'potongan_promo' => 0,
            'nominal_akhir' => $pembayaran->nominal,
            'status_pembayaran' => 'belum_dibayar',
        ]);

        $tagihan->update([
            'nominal_awal' => $tagihan->nominal_awal + $pembayaran->nominal,
            'nominal_akhir' => $tagihan->nominal_akhir + $pembayaran->nominal,
            'sisa_tagihan' => $tagihan->sisa_tagihan + $pembayaran->nominal,
        ]);

        $transaksi = Transaksi::create([


            'pendaftaran_id' => $pendaftaran->id,

            'pembayaran_id' => $pembayaran->id,

            'kode_transaksi' => 'TRX-' . time(),

            'order_id' => 'ORDER-' . time(),

            'nominal' => $pembayaran->nominal,

            'status' => 'pending',

        ]);

        $transaksi->details()->create([
            'tagihan_santri_detail_id' => $tagihanDetail->id,
            'nominal' => $pembayaran->nominal,
        ]);
        

        return redirect()->route(

            'pembayaran-pendaftaran',
            ['transaksi' => $transaksi->id]
        );

    }

}