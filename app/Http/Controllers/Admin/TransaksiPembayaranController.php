<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\Pendaftaran;
use App\Models\TagihanSantri;
use App\Models\TagihanSantriDetail;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembayaranExport;
use App\Exports\RiwayatTransaksiExport;

class TransaksiPembayaranController extends Controller
{
    use FilterByPeriode;
    use LogsAdminActivity;
    use LogsAdminActivity;

    /**
     * Menu utama "Pembayaran": daftar tagihan semua santri, bisa difilter.
     * Ini yang dulunya cuma tampilan ringkasan di modal Data Pendaftar —
     * sekarang jadi menu sendiri dan bisa langsung ditindaklanjuti.
     */
    public function index(Request $request)
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;
        $isArsip = $periode && ! $periode->is_active;

        $query = TagihanSantri::with([
            'pendaftaran.pendidikan',
            'details' => function ($q) {
                $q->orderBy('kategori')->orderBy('id');
            },
            'details.pembayaran',
        ])
            ->when($selectedPeriodeId, function ($q) use ($selectedPeriodeId) {
                $q->whereHas('pendaftaran', function ($q2) use ($selectedPeriodeId) {
                    $q2->where('periode_id', $selectedPeriodeId);
                });
            });

        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        if ($request->filled('jenjang')) {
            $query->where('jenjang', $request->jenjang);
        }

        // Catatan: search & filter di sini sengaja tidak dipakai untuk query DB —
        // sama seperti menu Data Pendaftaran / Master Biaya, filter dilakukan di
        // JS sisi client dari data yang sudah ditampilkan (lihat blade).

        $tagihans = $query->latest()->get();

        return view('pages.admin.administrasi.pembayaran-santri', compact('tagihans', 'periode', 'isArsip'));
    }

    /**
     * Cetak rincian tagihan satu santri (item lunas & belum bayar) —
     * untuk dipegang wali santri sebagai referensi tunggakan.
     */
    public function cetakTagihan(TagihanSantri $tagihan)
    {
        $tagihan->load('pendaftaran.pendidikan', 'details');

        $pdf = Pdf::loadView('pages.admin.administrasi.print-tagihan-santri', compact('tagihan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('tagihan-' . str_replace(' ', '-', $tagihan->pendaftaran->nama_lengkap) . '.pdf');
    }

    /**
     * Riwayat Transaksi: log semua transaksi (Midtrans maupun manual/tunai)
     * lintas santri, untuk rekonsiliasi dan laporan keuangan.
     */
    public function riwayat(Request $request)
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;
        $isArsip = $periode && ! $periode->is_active;

        $transaksis = Transaksi::with([
            'pendaftaran.pendidikan',
            'details.tagihanSantriDetail',
            'dicatatOlehAdmin',
        ])
            ->when($selectedPeriodeId, function ($query) use ($selectedPeriodeId) {
                $query->whereHas('pendaftaran', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            })
            ->when($request->filled('sumber') && $request->sumber !== 'all', function ($query) use ($request) {
                $query->where('sumber_pembayaran', $request->sumber);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->whereHas('pendaftaran', function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%' . $request->cari . '%');
                });
            })
            ->when($request->filled('dari'), function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '>=', $request->dari);
            })
            ->when($request->filled('sampai'), function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '<=', $request->sampai);
            })
            ->latest('tanggal_bayar')
            ->get();

        return view('pages.admin.administrasi.riwayat-transaksi', compact('transaksis', 'periode', 'isArsip'));
    }

    /**
     * Export Riwayat Transaksi, ikut filter yang aktif di layar.
     */
    public function exportRiwayat(Request $request)
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;

        $transaksis = Transaksi::with(['pendaftaran.pendidikan', 'details.tagihanSantriDetail', 'dicatatOlehAdmin'])
            ->when($selectedPeriodeId, function ($query) use ($selectedPeriodeId) {
                $query->whereHas('pendaftaran', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            })
            ->when($request->filled('sumber') && $request->sumber !== 'all', function ($query) use ($request) {
                $query->where('sumber_pembayaran', $request->sumber);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->whereHas('pendaftaran', function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%' . $request->cari . '%');
                });
            })
            ->when($request->filled('dari'), function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '>=', $request->dari);
            })
            ->when($request->filled('sampai'), function ($query) use ($request) {
                $query->whereDate('tanggal_bayar', '<=', $request->sampai);
            })
            ->latest('tanggal_bayar')
            ->get();

        $filename = 'riwayat-transaksi-' . now()->format('Ymd-His');

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView(
                'pages.admin.administrasi.export-riwayat-transaksi-pdf',
                compact('transaksis')
            )->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(new RiwayatTransaksiExport($transaksis), $filename . '.xlsx');
    }

    /**
     * Export laporan pembayaran (tagihan per santri, sesuai yang tampil di
     * tabel menu ini). Untuk laporan berbasis transaksi per-baris pencairan,
     * akan dibuat menyusul saat fitur riwayat transaksi selesai.
     */
    public function export(Request $request)
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;

        $tagihans = TagihanSantri::with(['pendaftaran.pendidikan', 'details'])
            ->when($selectedPeriodeId, function ($query) use ($selectedPeriodeId) {
                $query->whereHas('pendaftaran', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            })
            ->when($request->filled('jenjang') && $request->jenjang !== 'all', function ($query) use ($request) {
                $query->where('jenjang', $request->jenjang);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->where('status_pembayaran', $request->status);
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->whereHas('pendaftaran', function ($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%' . $request->cari . '%');
                });
            })
            ->latest()
            ->get();

        $filename = 'laporan-pembayaran-' . now()->format('Ymd-His');

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView(
                'pages.admin.administrasi.export-pembayaran-pdf',
                compact('tagihans')
            )->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        return Excel::download(new PembayaranExport($tagihans), $filename . '.xlsx');
    }

    /**
     * Catat pembayaran tunai/transfer manual untuk satu atau beberapa item
     * tagihan sekaligus (mis. santri bayar Uang Kesantrian + Makan bulan ini
     * dalam satu kali setor ke admin).
     */
    public function catatBayar(Request $request, TagihanSantri $tagihan)
    {
        $validated = $request->validate([
            'detail_ids' => 'required|array|min:1',
            'detail_ids.*' => 'integer|exists:tagihan_santri_details,id',
            'metode_bayar' => 'required|in:tunai,transfer_manual',
            'catatan' => 'nullable|string|max:255',
        ]);

        $details = $tagihan->details()
            ->whereIn('id', $validated['detail_ids'])
            ->where('status_pembayaran', '!=', 'lunas')
            ->get();

        if ($details->isEmpty()) {
            return back()->withErrors([
                'detail_ids' => 'Item tagihan yang dipilih tidak valid atau sudah lunas.',
            ]);
        }

        DB::transaction(function () use ($tagihan, $details, $validated) {
            $total = $details->sum('nominal_akhir');
            $firstDetail = $details->first();

            $transaksi = Transaksi::create([
                'pendaftaran_id' => $tagihan->pendaftaran_id,
                'pembayaran_id' => $firstDetail->pembayaran_id,
                'kode_transaksi' => 'MNL-' . now()->format('YmdHis') . '-' . $tagihan->pendaftaran_id,
                'order_id' => null,
                'nominal' => $total,
                'status' => 'settlement',
                'sumber_pembayaran' => 'manual',
                'payment_type' => $validated['metode_bayar'],
                'tanggal_bayar' => now(),
                'dicatat_oleh_admin_id' => session('admin.id'),
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($details as $detail) {
                $transaksi->details()->create([
                    'tagihan_santri_detail_id' => $detail->id,
                    'nominal' => $detail->nominal_akhir,
                ]);

                $detail->update([
                    'status_pembayaran' => 'lunas',
                    'tanggal_bayar' => now(),
                ]);
            }

            $this->refreshTagihanSummary($tagihan);
        });

        $tagihan->loadMissing('pendaftaran');

        $this->catatAktivitas(
            'catat_bayar',
            'Mencatat pembayaran ' . $validated['metode_bayar'] . ' untuk ' . ($tagihan->pendaftaran->nama_lengkap ?? 'santri') . ' sebesar Rp ' . number_format($details->sum('nominal_akhir'), 0, ',', '.'),
            $tagihan
        );

        $this->catatAktivitas(
            'catat_bayar',
            'Mencatat pembayaran tunai/manual untuk ' . ($tagihan->pendaftaran?->nama_lengkap ?? 'santri') . ' sebesar Rp ' . number_format($details->sum('nominal_akhir'), 0, ',', '.'),
            $tagihan
        );

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    private function refreshTagihanSummary(TagihanSantri $tagihan): void
    {
        $details = $tagihan->details()->get();
        $totalDibayar = $details->where('status_pembayaran', 'lunas')->sum('nominal_akhir');

        $tagihan->update([
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan' => max(0, $details->sum('nominal_akhir') - $totalDibayar),
            'status_pembayaran' => match (true) {
                $details->where('status_pembayaran', 'lunas')->count() === 0 => 'belum_dibayar',
                $details->where('status_pembayaran', 'lunas')->count() === $details->count() => 'lunas',
                default => 'dicicil',
            },
        ]);
    }
}