<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Riwayat Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #222;
        }

        h2 {
            margin-bottom: 2px;
        }

        p.subtitle {
            margin-top: 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 5px 7px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>Riwayat Transaksi</h2>
    <p class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Nama Santri</th>
                <th>Item Dibayar</th>
                <th class="text-right">Nominal</th>
                <th>Sumber</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                @php
                    $items = $transaksi->details
                        ->map(fn($d) => $d->tagihanSantriDetail->nama_pembayaran ?? '-')
                        ->implode(', ');
                @endphp
                <tr>
                    <td>{{ $transaksi->kode_transaksi ?? ($transaksi->order_id ?? '-') }}</td>
                    <td>{{ optional($transaksi->tanggal_bayar)->format('d-m-Y H:i') ?? '-' }}</td>
                    <td>{{ $transaksi->pendaftaran->nama_lengkap ?? '-' }}</td>
                    <td>{{ $items }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->sumber_pembayaran === 'manual' ? 'Manual' : 'Midtrans' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $transaksi->payment_type ?? '-')) }}</td>
                    <td>{{ ucfirst($transaksi->status) }}</td>
                    <td>{{ $transaksi->dicatatOlehAdmin->nama_lengkap ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
