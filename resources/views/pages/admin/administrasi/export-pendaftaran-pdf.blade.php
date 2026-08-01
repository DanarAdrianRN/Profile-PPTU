<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Pendaftaran</title>
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
    <h2>Data Pendaftaran</h2>
    <p class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>NISN</th>
                <th>Jenjang</th>
                <th>Jurusan</th>
                <th>Wali (Ayah)</th>
                <th>Status Pendaftaran</th>
                <th>Status Pembayaran</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pendaftarans as $key => $pendaftaran)
                @php
                    $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();
                    $tagihan = $pendaftaran->tagihanSantri;
                    $totalItem = $tagihan?->details?->count() ?? 0;
                    $totalLunas = $tagihan?->details?->where('status_pembayaran', 'lunas')->count() ?? 0;
                    $isLunas = $totalItem > 0 && $totalItem === $totalLunas;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $pendaftaran->nama_lengkap }}</td>
                    <td>{{ $pendaftaran->pendidikan->nisn ?? '-' }}</td>
                    <td>{{ $pendaftaran->pendidikan->jenjang_pendidikan ?? '-' }}</td>
                    <td>{{ $pendaftaran->pendidikan->jurusan ?? '-' }}</td>
                    <td>{{ $ayah->nama ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $pendaftaran->status)) }}</td>
                    <td>{{ $isLunas ? 'Lunas' : 'Belum Lunas' }}</td>
                    <td>{{ optional($pendaftaran->created_at)->format('d-m-Y') }}</td>
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
