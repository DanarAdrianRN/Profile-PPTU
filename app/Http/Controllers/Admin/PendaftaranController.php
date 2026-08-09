<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendaftaranExport;

use App\Models\Pendaftaran;
use App\Models\PendaftaranPendidikan;
use App\Models\PendaftaranOrangTua;
use App\Models\PendaftaranDokumen;
use App\Models\Periode;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    use FilterByPeriode;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;
        $isArsip = $periode && ! $periode->is_active;
        $canManageSelectedPeriod = (bool) $periode?->is_active;

        $pendaftaranQuery = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens',
            'periode',
            'tagihanSantri.details.pembayaran',
            'tagihanSantri.details.promo',
        ])
            ->when($selectedPeriodeId, function ($query, $periodeId) {
                $query->where('periode_id', $periodeId);
            });

        $pendaftarans = (clone $pendaftaranQuery)
            ->latest()
            ->get();

        $statQuery = Pendaftaran::query()
            ->when($selectedPeriodeId, function ($query, $periodeId) {
                $query->where('periode_id', $periodeId);
            });

        $jumlahPendaftaran = (clone $statQuery)->count();
        $menungguVerifikasi = (clone $statQuery)->where(
            'status',
            'menunggu_verifikasi'
        )->count();

        $diterima = (clone $statQuery)->where(
            'status',
            'diterima'
        )->count();

        $ditolak = (clone $statQuery)->where(
            'status',
            'ditolak'
        )->count();

        $lunas = (clone $statQuery)->whereHas('tagihanSantri.details.pembayaran', function ($query) {
            $query->where('status', 'paid');
        })->count();

        $belumLunas = (clone $statQuery)->whereDoesntHave('tagihanSantri.details.pembayaran', function ($query) {
            $query->where('status', 'paid');
        })->count();

        return view('pages.admin.administrasi.pendaftaran', compact
        ('pendaftarans',
        'periode',
        'isArsip',
        'selectedPeriodeId',
        'canManageSelectedPeriod',
        'jumlahPendaftaran',
        'menungguVerifikasi',
        'diterima',
        'ditolak',
        'lunas',
        'belumLunas'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;

        $pendaftarans = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'tagihanSantri.details',
        ])
            ->when($selectedPeriodeId, function ($query, $periodeId) {
                $query->where('periode_id', $periodeId);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('jenjang') && $request->jenjang !== 'all', function ($query) use ($request) {
                $query->whereHas('pendidikan', function ($q) use ($request) {
                    $q->where('jenjang_pendidikan', $request->jenjang);
                });
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%' . $request->cari . '%');
            })
            ->latest()
            ->get();

        // Filter status pembayaran (paid/unpaid) dihitung dari relasi, bukan kolom
        // langsung, jadi diterapkan di level collection setelah data diambil.
        if ($request->filled('payment') && $request->payment !== 'all') {
            $wantPaid = $request->payment === 'paid';

            $pendaftarans = $pendaftarans->filter(function ($pendaftaran) use ($wantPaid) {
                $tagihan = $pendaftaran->tagihanSantri;
                $totalItem = $tagihan?->details?->count() ?? 0;
                $totalLunas = $tagihan?->details?->where('status_pembayaran', 'lunas')->count() ?? 0;
                $isLunas = $totalItem > 0 && $totalItem === $totalLunas;

                return $isLunas === $wantPaid;
            })->values();
        }

        $filename = 'data-pendaftaran-' . now()->format('Ymd-His');

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView(
                'pages.admin.administrasi.export-pendaftaran-pdf',
                compact('pendaftarans')
            )->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(new PendaftaranExport($pendaftarans), $filename . '.xlsx');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            // DATA SANTRI
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required',
            'agama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_hp_ortu' => 'nullable|string|max:20',

            // FILE
            'akta_kelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ortu' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nisn_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'foto_warna' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'foto_bw' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | SIMPAN PENDAFTARAN
            |--------------------------------------------------------------------------
            */

            $pendaftaran = Pendaftaran::create([

                'periode_id' => Periode::aktif()->value('id'),

                'kode_pendaftaran' =>
                'PSB-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(5)),

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
                'tempat_lahir' => $request->tempat_lahir_ibu,
                'tanggal_lahir' => $request->tanggal_lahir_ibu,
                'agama' => $request->agama_ibu,
                'pendidikan' => $request->pendidikan_ibu,
                'pekerjaan' => $request->pekerjaan_ibu,
                'penghasilan' => $request->penghasilan_ibu,
                'alamat' => $request->alamat_ibu,

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
                'status' => $request->status_wali,
                'tempat_lahir' => $request->tempat_lahir_wali,
                'tanggal_lahir' => $request->tanggal_lahir_wali,
                'agama' => $request->agama_wali,
                'pendidikan' => $request->pendidikan_wali,
                'pekerjaan' => $request->pekerjaan_wali,
                'penghasilan' => $request->penghasilan_wali,
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
        });

        return redirect()
            ->back()
            ->with(
                'success',
                'Pendaftaran berhasil dikirim'
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
            'periode',
            'dokumens'
        ])->findOrFail($id);

        $this->ensurePendaftaranEditable($pendaftaran);

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
        
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])->findOrFail($id);

        DB::transaction(function () use (
            $request,
            $pendaftaran
        ) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE PENDAFTARAN
            |--------------------------------------------------------------------------
            */

            $pendaftaran->update([

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
            | UPDATE PENDIDIKAN
            |--------------------------------------------------------------------------
            */

            if ($pendaftaran->pendidikan) {

                $pendaftaran->pendidikan->update([

                    'jenjang_pendidikan' => $request->jenjang_pendidikan,
                    'jurusan' => $request->jurusan,
                    'sekolah_asal' => $request->sekolah_asal,
                    'tahun_lulus' => $request->tahun_lulus,
                    'tanggal_nomor_ijazah' =>
                    $request->tanggal_nomor_ijazah,
                    'nisn' => $request->nisn,
                    'lama_belajar' => $request->lama_belajar,

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE ORANG TUA
            |--------------------------------------------------------------------------
            */

            $orangTuas = [
                'ayah',
                'ibu',
                'wali'
            ];

            foreach ($orangTuas as $tipe) {

                $ortu = $pendaftaran
                    ->orangTuas
                    ->where('tipe', $tipe)
                    ->first();

                $data = [

                    'nama' => $request->{'nama_' . $tipe},
                    'status' => $request->{'status_' . $tipe},
                    'tempat_lahir' =>
                    $request->{'tempat_lahir_' . $tipe},
                    'tanggal_lahir' =>
                    $request->{'tanggal_lahir_' . $tipe},
                    'agama' =>
                    $request->{'agama_' . $tipe},
                    'pendidikan' =>
                    $request->{'pendidikan_' . $tipe},
                    'pekerjaan' =>
                    $request->{'pekerjaan_' . $tipe},
                    'penghasilan' =>
                    $request->{'penghasilan_' . $tipe},
                    'alamat' =>
                    $request->{'alamat_' . $tipe},

                ];

                if ($ortu) {

                    $ortu->update($data);

                } else {

                    PendaftaranOrangTua::create([

                        'pendaftaran_id' =>
                        $pendaftaran->id,

                        'tipe' => $tipe,

                        ...$data

                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DOKUMEN
            |--------------------------------------------------------------------------
            */

            $documents = [

                'akta_kelahiran' =>
                'Akta Kelahiran',

                'ktp_ortu' =>
                'KTP Orang Tua',

                'kk' =>
                'Kartu Keluarga',

                'ijazah' =>
                'Ijazah',

                'nisn_file' =>
                'NISN',

                'kip' =>
                'KIP',

                'foto_warna' =>
                'Foto Warna',

                'foto_bw' =>
                'Foto Hitam Putih',

            ];

            foreach ($documents as $field => $jenis) {

                if ($request->hasFile($field)) {

                    $dokumen = $pendaftaran
                        ->dokumens
                        ->where(
                            'jenis_dokumen',
                            $jenis
                        )
                        ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | HAPUS FILE LAMA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $dokumen &&
                        Storage::disk('public')
                        ->exists($dokumen->file)
                    ) {

                        Storage::disk('public')
                            ->delete($dokumen->file);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UPLOAD BARU
                    |--------------------------------------------------------------------------
                    */

                    $path = $request
                        ->file($field)
                        ->store(
                            'pendaftaran',
                            'public'
                        );

                    if ($dokumen) {

                        $dokumen->update([
                            'file' => $path
                        ]);

                    } else {

                        PendaftaranDokumen::create([

                            'pendaftaran_id' =>
                            $pendaftaran->id,

                            'jenis_dokumen' =>
                            $jenis,

                            'file' => $path

                        ]);
                    }
                }
            }
        });

        return redirect()->back()
            ->with(
                'success',
                'Data berhasil diupdate'
            );
    }

    public function updateStatus(
    Request $request,
    Pendaftaran $pendaftaran
    ) {
        $this->ensurePendaftaranEditable($pendaftaran);

        $request->validate([
            'status' => 'required|in:menunggu_verifikasi,diterima,ditolak'
        ]);

        $pendaftaran->update([
            'status' => $request->status
        ]);

        return back()->with(
            'success',
            'Status berhasil diperbarui'
        );
    }
    /*
    |
    --------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'periode',
            'dokumens'
        ])->findOrFail($id);

        $this->ensurePendaftaranEditable($pendaftaran);

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE
        |--------------------------------------------------------------------------
        */

        foreach ($pendaftaran->dokumens as $dokumen) {

            if (
                Storage::disk('public')
                ->exists($dokumen->file)
            ) {

                Storage::disk('public')
                    ->delete($dokumen->file);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS RELASI
        |--------------------------------------------------------------------------
        */

        if ($pendaftaran->pendidikan) {
            $pendaftaran->pendidikan->delete();
        }

        $pendaftaran->orangTuas()->delete();
        $pendaftaran->dokumens()->delete();

        /*
        |--------------------------------------------------------------------------
        | HAPUS PENDAFTARAN
        |--------------------------------------------------------------------------
        */

        $pendaftaran->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Data berhasil dihapus'
            );
    }

        /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    public function preview($id)
    {
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])->findOrFail($id);

        return view(
            'pages.admin.administrasi.print-pendaftaran',
            compact('pendaftaran')
        );
    }

    public function print($id)
    {

        
        $pendaftaran = Pendaftaran::with([
            'pendidikan',
            'orangTuas',
            'dokumens'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'pages.admin.administrasi.print-pendaftaran',
            compact('pendaftaran')
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'pendaftaran-' .
            $pendaftaran->nama_lengkap .
            '.pdf'
        );
    }

    private function ensurePendaftaranEditable(Pendaftaran $pendaftaran): void
    {
        if (! $pendaftaran->periode?->is_active) {
            abort(403, 'Data pendaftaran periode nonaktif hanya bisa dilihat.');
        }
    }
}