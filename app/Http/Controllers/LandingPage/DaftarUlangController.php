<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Promo;
use App\Models\TagihanSantri;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarUlangController extends Controller
{
    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load([
            'pendidikan',
            'tagihanSantri.details.pembayaran',
            'tagihanSantri.details.promo',
        ]);

        $tagihan = $this->ensureTagihan($pendaftaran);

        return view('pages.landing-page.daftar-ulang.daftar-ulang', [
            'showModal' => false,
            'pendaftaran' => $pendaftaran,
            'tagihan' => $tagihan->load([
                'details.pembayaran',
                'details.promo',
            ]),
        ]);
    }

    public function store(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'tagihan_detail_ids' => 'required|array|min:1',
            'tagihan_detail_ids.*' => 'integer',
        ]);

        $tagihan = $this->ensureTagihan($pendaftaran);

        $details = $tagihan->details()
            ->with('pembayaran')
            ->whereIn('id', $validated['tagihan_detail_ids'])
            ->where('status_pembayaran', 'belum_dibayar')
            ->where('nominal_akhir', '>', 0)
            ->get();

        if ($details->isEmpty()) {
            return back()->withErrors([
                'tagihan' => 'Pilih minimal satu tagihan yang belum lunas.',
            ]);
        }

        $transaksi = DB::transaction(function () use ($pendaftaran, $details, $tagihan) {
            $total = $details->sum('nominal_akhir');
            $firstDetail = $details->first();

            $transaksi = Transaksi::create([
                'pendaftaran_id' => $pendaftaran->id,
                'pembayaran_id' => $firstDetail->pembayaran_id,
                'kode_transaksi' => 'DU-' . now()->format('YmdHis') . '-' . $pendaftaran->id,
                'order_id' => 'DU-ORDER-' . now()->format('YmdHis') . '-' . $pendaftaran->id,
                'nominal' => $total,
                'status' => 'pending',
            ]);

            foreach ($details as $detail) {
                $transaksi->details()->create([
                    'tagihan_santri_detail_id' => $detail->id,
                    'nominal' => $detail->nominal_akhir,
                ]);

                $detail->update([
                    'status_pembayaran' => 'belum_dibayar',
                ]);
            }

            $this->refreshTagihanSummary($tagihan);

            return $transaksi;
        });

        return redirect()->route('pembayaran-daftar-ulang', $transaksi->id);
    }

    private function ensureTagihan(Pendaftaran $pendaftaran): TagihanSantri
    {
        return DB::transaction(function () use ($pendaftaran) {
            $pendaftaran->loadMissing('pendidikan');

            $jenjang = $pendaftaran->pendidikan?->jenjang_pendidikan ?? 'SMP';
            $gelombangId = $this->gelombangIdFor($pendaftaran);
            $tanggalPromo = $pendaftaran->created_at ?? now();

            if ($gelombangId && $pendaftaran->gelombang_pendaftaran_id !== $gelombangId) {
                $pendaftaran->update([
                    'gelombang_pendaftaran_id' => $gelombangId,
                ]);
            }

            $tagihan = TagihanSantri::firstOrCreate(
                ['pendaftaran_id' => $pendaftaran->id],
                [
                    'gelombang_pendaftaran_id' => $gelombangId,
                    'kode_tagihan' => 'TAG-' . now()->format('YmdHis') . '-' . $pendaftaran->id,
                    'jenjang' => $jenjang,
                    'boleh_dicicil' => true,
                    'jumlah_cicilan' => 1,
                    'jatuh_tempo' => now()->addDays(7),
                ]
            );

            if ($tagihan->gelombang_pendaftaran_id !== $gelombangId) {
                $tagihan->update([
                    'gelombang_pendaftaran_id' => $gelombangId,
                    'jenjang' => $jenjang,
                ]);
            }

            $paidPaymentIds = Transaksi::where('pendaftaran_id', $pendaftaran->id)
                ->where('status', 'settlement')
                ->pluck('pembayaran_id')
                ->all();

            $pembayarans = Pembayaran::where('jenjang', $jenjang)
                ->where('is_active', true)
                ->orderByRaw("FIELD(kategori, 'Biaya Tahunan', 'Biaya Bulanan')")
                ->orderBy('id')
                ->get();

            foreach ($pembayarans as $pembayaran) {
                $existingDetail = $tagihan->details()
                    ->where('pembayaran_id', $pembayaran->id)
                    ->first();

                if ($existingDetail) {
                    if ($existingDetail->status_pembayaran === 'belum_dibayar') {
                        $this->applyPromoToDetail($existingDetail, $pembayaran, $jenjang, $gelombangId, $tanggalPromo);
                    }

                    continue;
                }

                $promo = $this->promoFor($pembayaran, $jenjang, $gelombangId, $tanggalPromo);
                $potongan = $this->potonganPromo($pembayaran, $promo);
                $nominalAkhir = max(0, $pembayaran->nominal - $potongan);
                $isPaid = in_array($pembayaran->id, $paidPaymentIds) || $nominalAkhir === 0;

                $tagihan->details()->create([
                    'pembayaran_id' => $pembayaran->id,
                    'promo_id' => $promo?->id,
                    'nama_pembayaran' => $pembayaran->nama_pembayaran,
                    'kategori' => $pembayaran->kategori,
                    'nominal_awal' => $pembayaran->nominal,
                    'potongan_promo' => $potongan,
                    'nominal_akhir' => $nominalAkhir,
                    'nama_promo' => $promo?->nama_promo,
                    'status_pembayaran' => $isPaid ? 'lunas' : 'belum_dibayar',
                    'tanggal_bayar' => $isPaid ? now() : null,
                ]);
            }

            $this->refreshTagihanSummary($tagihan);

            return $tagihan;
        });
    }

    private function gelombangIdFor(Pendaftaran $pendaftaran): ?int
    {
        if ($pendaftaran->gelombang_pendaftaran_id) {
            return $pendaftaran->gelombang_pendaftaran_id;
        }

        $gelombangId = GelombangPendaftaran::whereDate('tanggal_mulai', '<=', $pendaftaran->created_at)
            ->whereDate('tanggal_selesai', '>=', $pendaftaran->created_at)
            ->orderBy('urutan')
            ->value('id');

        return $gelombangId
            ?? GelombangPendaftaran::aktif()
                ->orderBy('urutan')
                ->value('id');
    }

    private function applyPromoToDetail($detail, Pembayaran $pembayaran, string $jenjang, ?int $gelombangId, $tanggalPromo): void
    {
        $promo = $this->promoFor($pembayaran, $jenjang, $gelombangId, $tanggalPromo);
        $potongan = $this->potonganPromo($pembayaran, $promo);
        $nominalAkhir = max(0, $pembayaran->nominal - $potongan);

        $detail->update([
            'promo_id' => $promo?->id,
            'nominal_awal' => $pembayaran->nominal,
            'potongan_promo' => $potongan,
            'nominal_akhir' => $nominalAkhir,
            'nama_promo' => $promo?->nama_promo,
            'status_pembayaran' => $nominalAkhir === 0 ? 'lunas' : 'belum_dibayar',
            'tanggal_bayar' => $nominalAkhir === 0 ? now() : null,
        ]);
    }

    private function promoFor(Pembayaran $pembayaran, string $jenjang, ?int $gelombangId, $tanggalPromo): ?Promo
    {
        return Promo::where('is_active', true)
            ->where(function ($query) use ($gelombangId) {
                $query->whereNull('gelombang_pendaftaran_id')
                    ->orWhere('gelombang_pendaftaran_id', $gelombangId);
            })
            ->where(function ($query) use ($jenjang) {
                $query->whereNull('jenjang')
                    ->orWhere('jenjang', $jenjang);
            })
            ->where(function ($query) use ($tanggalPromo) {
                $query->whereNull('tanggal_mulai')
                    ->orWhereDate('tanggal_mulai', '<=', $tanggalPromo);
            })
            ->where(function ($query) use ($tanggalPromo) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', $tanggalPromo);
            })
            ->where(function ($query) {
                $query->whereNull('kuota')
                    ->orWhereColumn('terpakai', '<', 'kuota');
            })
            ->whereHas('pembayarans', function ($query) use ($pembayaran) {
                $query->where('pembayarans.id', $pembayaran->id);
            })
            ->latest()
            ->first();
    }

    private function potonganPromo(Pembayaran $pembayaran, ?Promo $promo): int
    {
        if (! $promo) {
            return 0;
        }

        return match ($promo->tipe) {
            'gratis_biaya' => $pembayaran->nominal,
            'persentase' => (int) floor($pembayaran->nominal * ($promo->nilai / 100)),
            default => min($pembayaran->nominal, $promo->nilai),
        };
    }

    private function refreshTagihanSummary(TagihanSantri $tagihan): void
    {
        $details = $tagihan->details()->get();
        $totalDibayar = $details
            ->where('status_pembayaran', 'lunas')
            ->sum('nominal_akhir');

        $tagihan->update([
            'nominal_awal' => $details->sum('nominal_awal'),
            'potongan_promo' => $details->sum('potongan_promo'),
            'nominal_akhir' => $details->sum('nominal_akhir'),
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
