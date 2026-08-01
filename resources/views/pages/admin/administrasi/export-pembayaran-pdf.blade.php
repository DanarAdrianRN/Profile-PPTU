<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
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
            padding: 6px 8px;
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
    <h2>Laporan Pembayaran Santri</h2>
    <p class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Santri</th>
                <th>NISN</th>
                <th>Jenjang</th>
                <th class="text-right">Total Tagihan</th>
                <th class="text-right">Sisa Tagihan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tagihans as $key => $tagihan)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $tagihan->pendaftaran->nama_lengkap ?? '-' }}</td>
                    <td>{{ $tagihan->pendaftaran->pendidikan->nisn ?? '-' }}</td>
                    <td>{{ $tagihan->jenjang }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->nominal_akhir, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $tagihan->status_pembayaran)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
