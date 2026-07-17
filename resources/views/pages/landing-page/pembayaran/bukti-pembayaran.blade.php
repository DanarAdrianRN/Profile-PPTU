<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Pembayaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 12px; }
        .receipt { width: 100%; border: 1px solid #d8dee9; border-radius: 8px; padding: 28px; box-sizing: border-box; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 16px; margin-bottom: 20px; }
        h1 { color: #1d4ed8; font-size: 22px; margin: 0 0 6px; }
        .status { color: #15803d; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        td { padding: 9px 0; border-bottom: 1px solid #edf0f4; vertical-align: top; }
        td:first-child { width: 42%; color: #64748b; }
        .total { margin-top: 18px; padding: 16px; background: #eff6ff; text-align: right; }
        .total strong { color: #1d4ed8; font-size: 18px; }
        .footer { margin-top: 26px; color: #64748b; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>Bukti Pembayaran</h1>
            <div class="status">PEMBAYARAN BERHASIL</div>
        </div>

        <table>
            <tr><td>Kode Transaksi</td><td>{{ $transaksi->kode_transaksi ?? $transaksi->order_id }}</td></tr>
            <tr><td>Order ID</td><td>{{ $transaksi->order_id }}</td></tr>
            <tr><td>Nama Santri</td><td>{{ $transaksi->pendaftaran->nama_lengkap }}</td></tr>
            <tr><td>Jenjang</td><td>{{ $transaksi->pendaftaran->pendidikan?->jenjang ?? $transaksi->pembayaran?->jenjang ?? '-' }}</td></tr>
            <tr><td>Metode Pembayaran</td><td>{{ strtoupper($transaksi->payment_type ?? '-') }} {{ strtoupper($transaksi->bank ?? '') }}</td></tr>
            <tr><td>Tanggal Pembayaran</td><td>{{ optional($transaksi->tanggal_bayar)->format('d M Y H:i') ?? $transaksi->updated_at->format('d M Y H:i') }}</td></tr>
        </table>

        @if ($transaksi->details->isNotEmpty())
            <table>
                @foreach ($transaksi->details as $detail)
                    <tr>
                        <td>{{ $detail->tagihanSantriDetail?->nama_pembayaran ?? 'Tagihan' }}</td>
                        <td style="text-align: right">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <div class="total">
            Total Pembayaran<br>
            <strong>Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</strong>
        </div>

        <div class="footer">Dokumen ini dibuat secara otomatis oleh sistem pembayaran.</div>
    </div>
</body>
</html>
