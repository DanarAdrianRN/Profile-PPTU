<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #172033;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .sheet {
            border: 1px solid #d8dee9;
            border-radius: 10px;
            padding: 26px 28px;
        }

        /* HEADER — table-based, bukan float, supaya aman di DomPDF */
        table.header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px solid #117a8b;
            padding-bottom: 14px;
        }

        table.header-table td {
            vertical-align: bottom;
            padding-bottom: 14px;
        }

        table.header-table .brand-cell {
            text-align: left;
        }

        table.header-table .brand-cell h1 {
            color: #117a8b;
            font-size: 17px;
            margin: 0 0 3px;
        }

        table.header-table .brand-cell p {
            margin: 0;
            color: #64748b;
            font-size: 10px;
        }

        table.header-table .doc-cell {
            text-align: right;
            width: 40%;
        }

        table.header-table .doc-cell h2 {
            color: #172033;
            font-size: 16px;
            margin: 0 0 6px;
            letter-spacing: 1px;
        }

        table.header-table .doc-cell .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: bold;
            background: #dcfce7;
            color: #15803d;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table.info td {
            padding: 6px 0;
            border-bottom: 1px solid #edf0f4;
            vertical-align: top;
            font-size: 11px;
        }

        table.info td:first-child {
            width: 38%;
            color: #64748b;
        }

        table.info td:last-child {
            font-weight: bold;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        table.items th {
            background: #f0f9fa;
            color: #117a8b;
            text-align: left;
            padding: 9px 10px;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.items th.amount,
        table.items td.amount {
            text-align: right;
        }

        table.items td {
            padding: 9px 10px;
            border-bottom: 1px solid #edf0f4;
            font-size: 11px;
        }

        /* KOTAK TOTAL — table-based juga */
        table.total-box {
            width: 100%;
            background: #f0f9fa;
            border-radius: 8px;
            margin-top: 6px;
        }

        table.total-box td {
            padding: 14px 16px;
        }

        table.total-box .label {
            color: #334155;
            font-size: 12px;
            text-align: left;
        }

        table.total-box .amount {
            color: #117a8b;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        /* TANDA TANGAN */
        table.signature {
            width: 100%;
            margin-top: 34px;
        }

        table.signature td {
            width: 50%;
            text-align: center;
            font-size: 11px;
            vertical-align: top;
        }

        table.signature .sign-space {
            height: 50px;
        }

        table.signature .sign-name {
            border-top: 1px solid #172033;
            padding-top: 6px;
            display: inline-block;
            min-width: 160px;
            font-weight: bold;
        }

        .footer {
            margin-top: 26px;
            padding-top: 14px;
            border-top: 1px solid #edf0f4;
            color: #94a3b8;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="sheet">

        <table class="header-table">
            <tr>
                <td class="brand-cell">
                    <h1>Pondok Pesantren Tarbiyatul 'Ulum</h1>
                    <p>Sumursongo &mdash; Kwitansi Pembayaran Resmi</p>
                </td>
                <td class="doc-cell">
                    <h2>KWITANSI</h2>
                    <span class="status">PEMBAYARAN BERHASIL</span>
                </td>
            </tr>
        </table>

        <table class="info">
            <tr>
                <td>Kode Transaksi</td>
                <td>{{ $transaksi->kode_transaksi ?? ($transaksi->order_id ?? '-') }}</td>
            </tr>
            @if ($transaksi->order_id)
                <tr>
                    <td>Order ID</td>
                    <td>{{ $transaksi->order_id }}</td>
                </tr>
            @endif
            <tr>
                <td>Nama Santri</td>
                <td>{{ $transaksi->pendaftaran->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenjang</td>
                <td>{{ $transaksi->pendaftaran->pendidikan?->jenjang_pendidikan ?? ($transaksi->pembayaran?->jenjang ?? '-') }}
                </td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>{{ strtoupper($transaksi->payment_type ?? '-') }} {{ strtoupper($transaksi->bank ?? '') }}</td>
            </tr>
            <tr>
                <td>Tanggal Pembayaran</td>
                <td>{{ optional($transaksi->tanggal_bayar)->format('d M Y, H:i') ?? $transaksi->updated_at->format('d M Y, H:i') }}
                </td>
            </tr>
            @if (($transaksi->sumber_pembayaran ?? null) === 'manual' && $transaksi->dicatatOlehAdmin)
                <tr>
                    <td>Diterima Oleh</td>
                    <td>{{ $transaksi->dicatatOlehAdmin->nama_lengkap }} (Admin)</td>
                </tr>
            @endif
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th>Rincian Pembayaran</th>
                    <th class="amount">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @if ($transaksi->details->isNotEmpty())
                    @foreach ($transaksi->details as $detail)
                        <tr>
                            <td>{{ $detail->tagihanSantriDetail?->nama_pembayaran ?? 'Tagihan' }}</td>
                            <td class="amount">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $transaksi->pembayaran->nama_pembayaran ?? 'Pembayaran' }}</td>
                        <td class="amount">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="total-box">
            <tr>
                <td class="label">Total Pembayaran</td>
                <td class="amount">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
            </tr>
        </table>

        <table class="signature">
            <tr>
                <td></td>
                <td>
                    <div>Sumursongo, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="sign-space"></div>
                    <span class="sign-name">
                        {{ $transaksi->dicatatOlehAdmin->nama_lengkap ?? 'Admin Yayasan' }}
                    </span>
                    <div>Bendahara / Admin Yayasan</div>
                </td>
            </tr>
        </table>

        <div class="footer">
            Dokumen ini dibuat dan disahkan secara otomatis oleh sistem administrasi Pondok Pesantren Tarbiyatul 'Ulum
            Sumursongo.
        </div>

    </div>
</body>

</html>
