<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    private function midtransConfig(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$curlOptions = [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_PROXY => '',
            CURLOPT_HTTPHEADER => [],
        ];
    }

    public function pembayaranPendaftaran(Transaksi $transaksi)
    {
        $transaksi->load('pendaftaran.pendidikan', 'pembayaran');

        return view('pages.landing-page.pembayaran.pembayaran', [
            'showModal' => false,
            'transaksi' => $transaksi,
            'title' => 'Pembayaran Pendaftaran',
            'subtitle' => 'Silakan pilih metode pembayaran untuk melanjutkan proses PPDB santri baru.',
        ]);
    }

    public function pembayaranDaftarUlang(Transaksi $transaksi)
    {
        $transaksi->load('pendaftaran.pendidikan', 'pembayaran', 'details.tagihanSantriDetail');

        return view('pages.landing-page.pembayaran.pembayaran', [
            'showModal' => false,
            'transaksi' => $transaksi,
            'title' => 'Pembayaran Daftar Ulang',
            'subtitle' => 'Silakan pilih metode pembayaran untuk melanjutkan proses daftar ulang.',
        ]);
    }

    /** Membuat Snap Token untuk metode pembayaran yang dipilih pengguna. */
    public function pilihMetode(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:bca,bri,bni,mandiri,qris,gopay,dana,ovo,shopeepay',
        ]);

        $terminalStatuses = ['expire', 'cancel', 'deny'];

        if (blank($transaksi->order_id) || in_array($transaksi->status, $terminalStatuses, true)) {
            $transaksi->update([
                'order_id' => 'ORDER-' . $transaksi->id . '-' . Str::upper(Str::random(8)),
            ]);
        }

        if ((int) $transaksi->nominal < 1 || blank(config('midtrans.server_key'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data transaksi atau konfigurasi pembayaran belum lengkap.',
                ], 422);
            }

            return back()->withErrors([
                'payment_method' => 'Data transaksi atau konfigurasi pembayaran belum lengkap.',
            ]);
        }

        // Satu transaksi hanya mempunyai satu instruksi pembayaran aktif.
        if ($transaksi->status === 'pending' && $transaksi->snap_token &&
            (! $transaksi->expired_at || $transaksi->expired_at->isFuture())) {
            return $request->expectsJson()
                ? response()->json(['snap_token' => $transaksi->snap_token])
                : back();
        }

        $this->midtransConfig();
        $method = $validated['payment_method'];

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $transaksi->order_id,
                    'gross_amount' => (int) $transaksi->nominal,
                ],
                'enabled_payments' => [$this->snapPaymentMethod($method)],
            ]);

            $transaksi->update([
                'status' => 'pending',
                'payment_type' => in_array($method, ['bca', 'bri', 'bni', 'mandiri'], true)
                    ? 'bank_transfer'
                    : $method,
                'bank' => in_array($method, ['bca', 'bri', 'bni', 'mandiri'], true) ? $method : null,
                'snap_token' => $snapToken,
                'transaction_id' => null,
                'va_number' => null,
                'qr_url' => null,
                'expired_at' => now()->addDay(),
                'response_midtrans' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal membuat instruksi pembayaran Midtrans.', [
                'transaksi_id' => $transaksi->id,
                'order_id' => $transaksi->order_id,
                'payment_method' => $method,
                'exception' => $e->getMessage(),
            ]);

            $message = 'Instruksi pembayaran belum dapat dibuat. Silakan pilih metode lain atau coba lagi.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 502);
            }

            return back()->withErrors([
                'payment_method' => $message,
            ]);
        }

        return $request->expectsJson()
            ? response()->json(['snap_token' => $snapToken])
            : back();
    }

    public function detailPembayaran(Transaksi $transaksi)
    {
        if (in_array($transaksi->status, ['settlement', 'expire', 'cancel', 'deny'], true)) {
            return redirect()->route('pembayaran-berhasil', $transaksi);
        }

        return redirect($this->paymentPageUrl($transaksi));
    }

    public function pembayaranBerhasil(Transaksi $transaksi)
    {
        $transaksi->load('pendaftaran.pendidikan', 'pembayaran', 'details.tagihanSantriDetail');

        return view('pages.landing-page.pembayaran.pembayaran-berhasil', [
            'showModal' => false,
            'transaksi' => $transaksi,
        ]);
    }

    public function downloadBukti(Transaksi $transaksi)
    {
        abort_unless($transaksi->status === 'settlement', 403);

        $transaksi->load('pendaftaran.pendidikan', 'pembayaran', 'details.tagihanSantriDetail');

        return Pdf::loadView('pages.landing-page.pembayaran.bukti-pembayaran', compact('transaksi'))
            ->setPaper('a4', 'portrait')
            ->download('bukti-pembayaran-' . $transaksi->kode_transaksi . '.pdf');
    }

    private function snapPaymentMethod(string $method): string
    {
        return match ($method) {
            'bca' => 'bca_va',
            'bri' => 'bri_va',
            'bni' => 'bni_va',
            'mandiri' => 'echannel',
            'qris' => 'other_qris',
            default => $method,
        };
    }

    private function paymentPageUrl(Transaksi $transaksi): string
    {
        return $transaksi->details()->exists()
            ? route('pembayaran-daftar-ulang', $transaksi)
            : route('pembayaran-pendaftaran', $transaksi);
    }
}
