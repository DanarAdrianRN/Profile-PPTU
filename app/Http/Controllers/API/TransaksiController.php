<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use App\Models\TagihanSantri;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    /**
     * method pilih metode pembayaran
     */
    public function pilihMetode(Request $request,Transaksi $transaksi)
    {
        $request->validate([

            'payment_method' => 'required'

        ]);

        $transaksi->update([

            'payment_type' =>
                $request->payment_method

        ]);

        return redirect()->route(
            'detail-pembayaran',
            $transaksi->id
        );
    }

    /**
     * method midtrans config
     */
    private function midtransConfig()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * method payment
     */
    public function payment($id)
    {
        $this->midtransConfig();

        $transaksi = Transaksi::with([
            'pendaftaran',
            'pembayaran',
            'details.tagihanSantriDetail',
        ])->findOrFail($id);

        $itemDetails = $transaksi->details->isNotEmpty()
            ? $transaksi->details->map(function ($detail) {
                return [
                    'id' => 'TAG-' . $detail->tagihan_santri_detail_id,
                    'price' => $detail->nominal,
                    'quantity' => 1,
                    'name' => $detail->tagihanSantriDetail?->nama_pembayaran ?? 'Daftar Ulang',
                ];
            })->values()->all()
            : null;

        $params = [

            'transaction_details' => [
                'order_id' => $transaksi->order_id,
                'gross_amount' => $transaksi->nominal,
            ],

            'customer_details' => [
                'first_name' => $transaksi->pendaftaran->nama_lengkap,
                'phone' => $transaksi->pendaftaran->no_hp_ortu,

            ],

        ];

        if ($itemDetails) {
            $params['item_details'] = $itemDetails;
        }

        $snapToken = Snap::getSnapToken($params);
        $transaksi->update([
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'snap_token' => $snapToken,

        ]);
    }

    /**
     * Dipanggil oleh halaman detail agar status tetap berubah otomatis saat
     * aplikasi lokal belum dapat menerima webhook langsung dari Midtrans.
     */
    public function status($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'pending' && $transaksi->order_id && $transaksi->transaction_id) {
            $this->midtransConfig();

            try {
                $gatewayStatus = Transaction::status($transaksi->order_id);
                $this->callback(new Request((array) $gatewayStatus));
                $transaksi->refresh();
            } catch (\Throwable $e) {
                Log::warning('Gagal memeriksa status transaksi Midtrans.', [
                    'transaksi_id' => $transaksi->id,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => $transaksi->status,
            'redirect_url' => in_array($transaksi->status, ['settlement', 'expire', 'cancel', 'deny'], true)
                ? route('pembayaran-berhasil', $transaksi)
                : null,
        ]);
    }

    /**
     * Memverifikasi hasil Snap langsung ke Midtrans setelah callback onSuccess.
     * Status tidak pernah dipercaya dari browser; server tetap mengambilnya dari Midtrans.
     */
    public function confirm($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'pending' && $transaksi->order_id) {
            $this->midtransConfig();

            try {
                $gatewayStatus = Transaction::status($transaksi->order_id);
                $this->callback(new Request((array) $gatewayStatus));
                $transaksi->refresh();
            } catch (\Throwable $e) {
                Log::warning('Gagal mengonfirmasi hasil pembayaran Midtrans.', [
                    'transaksi_id' => $transaksi->id,
                    'order_id' => $transaksi->order_id,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => $transaksi->status,
            'redirect_url' => $transaksi->status === 'settlement'
                ? route('pembayaran-berhasil', $transaksi)
                : null,
        ]);
    }

    /**
     * method callback midtrans
     */
    public function callback(Request $request)
    {
        $orderId = $request->order_id;

        $transaksi = Transaksi::where(
            'order_id',
            $orderId
        )->first();

        if (!$transaksi) {
            return response()->json([
                'message' => 'Not Found'
            ]);
        }

        if (
            $request->transaction_status
            == 'settlement'
        ) {

            $transaksi->update([

                'status' => 'settlement',

                'tanggal_bayar' => now(),

                'payment_type' =>
                    $request->payment_type,

                'response_midtrans' =>
                    json_encode($request->all())

            ]);

            $transaksi->load('details.tagihanSantriDetail.tagihanSantri');

            foreach ($transaksi->details as $detail) {
                $tagihanDetail = $detail->tagihanSantriDetail;

                if (! $tagihanDetail) {
                    continue;
                }

                $tagihanDetail->update([
                    'status_pembayaran' => 'lunas',
                    'tanggal_bayar' => now(),
                ]);

                $tagihan = $tagihanDetail->tagihanSantri;

                if ($tagihan) {
                    $this->refreshTagihanSummary($tagihan);
                }
            }
        }

        if (in_array($request->transaction_status, [
            'expire',
            'cancel',
            'deny',
        ])) {
            $transaksi->update([
                'status' => $request->transaction_status,
                'payment_type' => $request->payment_type,
                'response_midtrans' => json_encode($request->all()),
            ]);

            $transaksi->load('details.tagihanSantriDetail.tagihanSantri');

            foreach ($transaksi->details as $detail) {
                $tagihanDetail = $detail->tagihanSantriDetail;

                if (! $tagihanDetail || $tagihanDetail->status_pembayaran === 'lunas') {
                    continue;
                }

                $tagihanDetail->update([
                    'status_pembayaran' => 'belum_dibayar',
                ]);

                if ($tagihanDetail->tagihanSantri) {
                    $this->refreshTagihanSummary($tagihanDetail->tagihanSantri);
                }
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function refreshTagihanSummary(TagihanSantri $tagihan): void
    {
        $details = $tagihan->details()->get();
        $totalDibayar = $details
            ->where('status_pembayaran', 'lunas')
            ->sum('nominal_akhir');

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
