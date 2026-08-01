<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tagihan {{ $tagihan->pendaftaran->nama_lengkap ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #172033; font-size: 12px; }
        .sheet { width: 100%; border: 1px solid #d8dee9; border-radius: 8px; padding: 28px; box-sizing: border-box; }
        .header { border-bottom: 2px solid #0f766e; padding-bottom: 16px; margin-bottom: 20px; }
        h1 { color: #0f766e; font-size: 20px; margin: 0 0 6px; }
        .subtitle { color: #64748b; }
        table.info { width: 100%; margin-bottom: 16px; }
        table.info td { padding: 4px 0; vertical-align: top; }
        table.info td:first-child { width: 30%; color: #64748b; }
        table.items { width: 100%; border-collapse: collapse; margin: 16px 0; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #edf0f4; text-align: left; }
        table.items th { background: #f8fafc; color: #334155; }
        .text-right { text-align: right; }
        .status-lunas { color: #15803d; font-weight: bold; }
        .status-belum { color: #b45309; font-weight: bold; }
        .summary { margin-top: 18px; }
        .summary table { width: 50%; margin-left: auto; }
        .summary td { padding: 6px 0; }
        .summary td:first-child { color: #64748b; }
        .summary .grand-total td { border-top: 2px solid #0f766e; padding-top: 10px; font-size: 15px; font-weight: bold; color: #0f766e; }
        .footer { margin-top: 26px; color: #64748b; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="header">
            <h1>Rincian Tagihan Santri</h1>
            <div class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
        </div>

        <table class="info">
            <tr><td>Nama Santri</td><td>{{ $tagihan->pendaftaran->nama_lengkap ?? '-' }}</td></tr>
            <tr><td>NISN</td><td>{{ $tagihan->pendaftaran->pendidikan->nisn ?? '-' }}</td></tr>
            <tr><td>Jenjang</td><td>{{ $tagihan->jenjang }}</td></tr>
        </table>

        @foreach ($tagihan->details->groupBy('kategori') as $kategori => $items)
            <table class="items">
                <thead>
                    <tr>
                        <th colspan="2">{{ $kategori }}</th>
                        <th class="text-right">Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td colspan="2">{{ $item->nama_pembayaran }}</td>
                            <td class="text-right">Rp {{ number_format($item->nominal_akhir, 0, ',', '.') }}</td>
                            <td>
                                @if ($item->status_pembayaran === 'lunas')
                                    <span class="status-lunas">Lunas</span>
                                @else
                                    <span class="status-belum">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <div class="summary">
            <table>
                <tr>
                    <td>Total Tagihan</td>
                    <td class="text-right">Rp {{ number_format($tagihan->details->sum('nominal_akhir'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Lunas</td>
                    <td class="text-right">Rp {{ number_format($tagihan->details->where('status_pembayaran', 'lunas')->sum('nominal_akhir'), 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Sisa Tagihan</td>
                    <td class="text-right">Rp {{ number_format($tagihan->details->where('status_pembayaran', '!=', 'lunas')->sum('nominal_akhir'), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">Dokumen ini dibuat secara otomatis oleh sistem administrasi.</div>
    </div>
</body>
</html>