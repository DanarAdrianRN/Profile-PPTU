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
        | SIMPAN PENDAFTARAN
        |--------------------------------------------------------------------------
        */
        $gelombangAktif = GelombangPendaftaran::aktif()
            ->orderBy('urutan')
            ->first();

        $pendaftaran = Pendaftaran::create([

            'gelombang_pendaftaran_id' => $gelombangAktif?->id,
            'periode_id' => Periode::aktif()->value('id'),

            'kode_pendaftaran' => 'PSB-' . strtoupper(Str::random(6)),

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

        $transaksi = Transaksi::create([


            'pendaftaran_id' => $pendaftaran->id,

            'pembayaran_id' => $pembayaran->id,

            'kode_transaksi' => 'TRX-' . time(),

            'order_id' => 'ORDER-' . time(),

            'nominal' => $pembayaran->nominal,

            'status' => 'pending',

        ]);
        

        return redirect()->route(

            'pembayaran-pendaftaran',
            ['transaksi' => $transaksi->id]
        );

    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])->findOrFail($id);

        return view(
            'pages.admin.administrasi.pendaftaran.show',
            compact('pendaftaran')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])->findOrFail($id);

        return view(
            'pages.admin.administrasi.pendaftaran.edit',
            compact('pendaftaran')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {

        $pendaftaran = Pendaftaran::findOrFail($id);

        $pendaftaran->update([

            'nama_lengkap' => $request->nama_lengkap,

            'nama_panggilan' => $request->nama_panggilan,

            'jenis_kelamin' => $request->jenis_kelamin,

            'agama' => $request->agama,

            'alamat' => $request->alamat,

            'status' => $request->status,

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE PENDIDIKAN
        |--------------------------------------------------------------------------
        */

        if ($pendaftaran->pendidikan) {

            $pendaftaran->pendidikan->update([

                'sekolah_asal' => $request->sekolah_asal,

                'jurusan' => $request->jurusan,

                'nisn' => $request->nisn,

            ]);
        }

        return redirect()
            ->route('admin.pendaftaran.index')
            ->with('success', 'Data berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $pendaftaran = Pendaftaran::with('dokumens')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE
        |--------------------------------------------------------------------------
        */

        foreach ($pendaftaran->dokumens as $dokumen) {

            if (Storage::disk('public')->exists($dokumen->file)) {

                Storage::disk('public')
                    ->delete($dokumen->file);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA
        |--------------------------------------------------------------------------
        */

        $pendaftaran->delete();

        return redirect()
            ->back()
            ->with('success', 'Data berhasil dihapus');
    }
}
