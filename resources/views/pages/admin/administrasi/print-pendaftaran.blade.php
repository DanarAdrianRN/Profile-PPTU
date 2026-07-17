<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <style>
        @page {
            margin: 18mm 18mm 18mm 18mm;
        }

        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/Amiri-Regular.ttf') }}")
            format('truetype');
        }

        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/Amiri-Bold.ttf') }}")
            format('truetype');
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .print-wrapper {
            width: 100%;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 115px;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 95px;
        }

        .kop-content {
            text-align: center;
            vertical-align: middle;
        }

        .arabic {
            font-family: 'Amiri';
            font-size: 34px;
            direction: rtl;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 2px;
        }

        .yayasan {
            font-size: 28px;
            font-weight: bold;
            color: #2d7d59;
            letter-spacing: .5px;
        }

        .alamat {
            font-size: 12px;
            line-height: 1.2;
        }

        .line {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            margin: 12px 0 22px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title h2 {
            font-size: 20px;
            font-weight: 700;
        }

        .page-title h3 {
            font-size: 18px;
            margin-top: 4px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            /* background: #f2f2f2;
            border-left: 4px solid #2d7d59; */
            /* padding: 8px 12px; */
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        table td:first-child {
            width: 250px;
            font-weight: 600;
        }

        table td:nth-child(2) {
            width: 20px;
            text-align: center;
        }

        .no-pendaftar {
            width: 100%;
            text-align: right;
            margin-bottom: 20px;
        }

        .data-table {
            width: auto !important;
            margin-left: auto;
        }

        .data-table td:first-child {
            width: auto;
            font-weight: bold;
        }

        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .signature-table td {
            width: 50% !important;
            vertical-align: top;
        }

        .signature-title {
            margin-bottom: 75px;
            font-size: 14px;
        }

        .signature-table h5 {
            font-size: 14px;
            text-decoration: underline;
        }

        /* @media print {

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section {
                page-break-inside: avoid;
            }
        } */
    </style>

    <title>
        Formulir Pendaftaran Santri
    </title>

</head>

<body>

    @php

        $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();

        $ibu = $pendaftaran->orangTuas->where('tipe', 'ibu')->first();

        $wali = $pendaftaran->orangTuas->where('tipe', 'wali')->first();

    @endphp

    <div class="print-wrapper">

        {{-- KOP SURAT --}}
        <table class="kop-table">
            <tr>

                <td class="kop-logo">

                    @php
                        $path = public_path('assets/pptu.png');
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp

                    <img src="{{ $base64 }}">

                </td>

                <td class="kop-content">

                    <div class="arabic">
                        مؤسسة تربية العلوم الإسلامية
                    </div>

                    <div class="yayasan">
                        YAYASAN TARBIYATUL ULUM SUMURSONGO
                    </div>

                    <div class="alamat">
                        Ds. Sumursongo RT. 01 RW. 01 Kec. Karas Kab. Magetan - 63393
                    </div>

                    <div class="alamat">
                        0813 3529 4691 - 0812 5212 8582
                    </div>

                    <div class="alamat">
                        PPTU sumursongo
                    </div>

                </td>

            </tr>
        </table>

        <div class="line"></div>

        {{-- TITLE --}}
        <div class="page-title">

            <h2>
                FORMULIR PENDAFTARAN SANTRI BARU
            </h2>

            <h3>
                PONDOK PESANTREN TARBIYATUL 'ULUM SUMURSONGO
            </h3>

        </div>

        <div class="section no-pendaftar">
            <table class="data-table">
                <tr>
                    <td>Nomor Pendaftaran</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kode_pendaftaran }}</td>
                </tr>
            </table>
        </div>

        {{-- IDENTITAS --}}
        <div class="section">

            <div class="section-title">
                A. IDENTITAS SANTRI
            </div>

            <table>

                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->nama_lengkap }}</td>
                </tr>

                <tr>
                    <td>Nama Panggilan</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->nama_panggilan }}</td>
                </tr>

                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>
                        {{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                </tr>

                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->agama }}</td>
                </tr>

                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>
                        {{ $pendaftaran->tempat_lahir }},
                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->translatedFormat('d F Y') }}
                    </td>
                </tr>

                <tr>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kewarganegaraan }}</td>
                </tr>

                <tr>
                    <td>Anak Ke</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->anak_ke }}</td>
                </tr>

                <tr>
                    <td>Jumlah Saudara Kandung</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->jumlah_saudara_kandung }}</td>
                </tr>

                <tr>
                    <td>Jumlah Saudara Angkat</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->jumlah_saudara_angkat }}</td>
                </tr>

                <tr>
                    <td>Jumlah Saudara Tiri</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->jumlah_saudara_tiri }}</td>
                </tr>

                <tr>
                    <td>Status Anak</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->status_anak }}</td>
                </tr>

                <tr>
                    <td>Bahasa Sehari-hari</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->bahasa_rumah }}</td>
                </tr>

            </table>

        </div>

        {{-- TEMPAT TINGGAL --}}
        <div class="section">

            <div class="section-title">
                B. TEMPAT TINGGAL & KESEHATAN
            </div>

            <table>

                <tr>
                    <td>Alamat Lengkap</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->alamat }}</td>
                </tr>

                <tr>
                    <td>RT / RW</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->rt_rw }}</td>
                </tr>

                <tr>
                    <td>Desa</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->desa }}</td>
                </tr>

                <tr>
                    <td>Kecamatan</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kecamatan }}</td>
                </tr>

                <tr>
                    <td>Kabupaten</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kabupaten }}</td>
                </tr>

                <tr>
                    <td>Tempat Tinggal</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->tempat_tinggal }}</td>
                </tr>

                <tr>
                    <td>Jarak Rumah</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->jarak_rumah }}</td>
                </tr>

                <tr>
                    <td>No HP Orang Tua</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->no_hp_ortu }}</td>
                </tr>

                <tr>
                    <td>Berat Badan</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->berat_badan }}</td>
                </tr>

                <tr>
                    <td>Tinggi Badan</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->tinggi_badan }}</td>
                </tr>

                <tr>
                    <td>Riwayat Penyakit</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->riwayat_penyakit }}</td>
                </tr>

                <tr>
                    <td>Kelainan Jasmani</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kelainan_jasmani }}</td>
                </tr>

            </table>

        </div>

        {{-- PENDIDIKAN --}}
        <div class="section">

            <div class="section-title">
                C. PENDIDIKAN
            </div>

            <table>

                <tr>
                    <td>Jenjang Pendidikan</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->jenjang_pendidikan }}</td>
                </tr>

                <tr>
                    <td>Jurusan</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->jurusan }}</td>
                </tr>

                <tr>
                    <td>Sekolah Asal</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->sekolah_asal }}</td>
                </tr>

                <tr>
                    <td>Tahun Lulus</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->tahun_lulus }}</td>
                </tr>

                <tr>
                    <td>Tanggal & Nomor Ijazah</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->tanggal_nomor_ijazah }}</td>
                </tr>

                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->nisn }}</td>
                </tr>

                <tr>
                    <td>Lama Belajar</td>
                    <td>:</td>
                    <td>{{ optional($pendaftaran->pendidikan)->lama_belajar }}</td>
                </tr>

            </table>

        </div>

        {{-- ORANG TUA --}}
        <div class="section">

            <div class="section-title">
                D. DATA AYAH KANDUNG
            </div>

            <table>

                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->nama }}</td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->status }}</td>
                </tr>

                <tr>
                    <td>Tempat dan Tanggal Lahir</td>
                    <td>:</td>
                    <td>
                        {{ optional($ayah)->tempat_lahir }},
                        {{ optional($ayah)->tanggal_lahir ? \Carbon\Carbon::parse(optional($ayah)->tanggal_lahir)->translatedFormat('d F Y') : '' }}
                    </td>
                </tr>

                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->agama }}</td>
                </tr>

                <tr>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->kewarganegaraan }}</td>
                </tr>

                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->pendidikan }}</td>
                </tr>

                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->pekerjaan }}</td>
                </tr>

                <tr>
                    <td>Penghasilan Perbulan</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->penghasilan }}</td>
                </tr>

                <tr>
                    <td>Alamat Rumah</td>
                    <td>:</td>
                    <td>{{ optional($ayah)->alamat }}</td>
                </tr>

            </table>

        </div>

        <div class="section">

            <div class="section-title">
                E. DATA IBU KANDUNG
            </div>

            <table>

                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->nama }}</td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->status }}</td>
                </tr>

                <tr>
                    <td>Tempat dan Tanggal Lahir</td>
                    <td>:</td>
                    <td>
                        {{ optional($ibu)->tempat_lahir }},
                        {{ optional($ibu)->tanggal_lahir ? \Carbon\Carbon::parse(optional($ibu)->tanggal_lahir)->translatedFormat('d F Y') : '' }}
                    </td>
                </tr>

                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->agama }}</td>
                </tr>

                <tr>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->kewarganegaraan }}</td>
                </tr>

                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->pendidikan }}</td>
                </tr>

                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->pekerjaan }}</td>
                </tr>

                <tr>
                    <td>Penghasilan Perbulan</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->penghasilan }}</td>
                </tr>

                <tr>
                    <td>Alamat Rumah</td>
                    <td>:</td>
                    <td>{{ optional($ibu)->alamat }}</td>
                </tr>

            </table>

        </div>

        <div class="section">

            <div class="section-title">
                F. DATA WALI SANTRI
            </div>

            <table>

                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ optional($wali)->nama }}</td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ optional($wali)->status }}</td>
                </tr>

                <tr>
                    <td>Tempat dan Tanggal Lahir</td>
                    <td>:</td>
                    <td>
                        {{ optional($wali)->tempat_lahir }},
                        {{ optional($wali)->tanggal_lahir ? \Carbon\Carbon::parse(optional($wali)->tanggal_lahir)->translatedFormat('d F Y') : '' }}
                    </td>
                </tr>

                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>{{ optional($wali)->agama }}</td>
                </tr>

                <tr>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                    <td>{{ optional($wali)->kewarganegaraan }}</td>
                </tr>

                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td>{{ optional($wali)->pendidikan }}</td>
                </tr>

                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ optional($wali)->pekerjaan }}</td>
                </tr>

                <tr>
                    <td>Penghasilan Perbulan</td>
                    <td>:</td>
                    <td>{{ optional($wali)->penghasilan }}</td>
                </tr>

                <tr>
                    <td>Alamat Rumah</td>
                    <td>:</td>
                    <td>{{ optional($wali)->alamat }}</td>
                </tr>

            </table>

        </div>

        {{-- KEMAMPUAN --}}
        <div class="section">

            <div class="section-title">
                G. KEMAMPUAN KEPESANTRENAN
            </div>

            <table>

                <tr>
                    <td>Kemampuan Membaca Al-Qur'an</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->kemampuan_quran }}</td>
                </tr>

                <tr>
                    <td>Hafalan Surat Pendek</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->hafalan }}</td>
                </tr>

                <tr>
                    <td>Membaca Pegon</td>
                    <td>:</td>
                    <td>
                        {{ $pendaftaran->baca_pegon ? 'Bisa' : 'Belum Bisa' }}
                    </td>
                </tr>

                <tr>
                    <td>Menulis Pegon</td>
                    <td>:</td>
                    <td>
                        {{ $pendaftaran->tulis_pegon ? 'Bisa' : 'Belum Bisa' }}
                    </td>
                </tr>

                {{-- <tr>
                    <td>Do'a Harian</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->doa }}</td>
                </tr>

                <tr>
                    <td>Shalat 5 Waktu</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->shalat_wajib }}</td>
                </tr>

                <tr>
                    <td>Shalat 5 Waktu Berjamaah</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->shalat_berjamaah }}</td>
                </tr> --}}

            </table>

        </div>

        {{-- EKSTRA --}}
        <div class="section">

            <div class="section-title">
                H. MINAT & EKSTRAKURIKULER
            </div>

            <table>

                <tr>
                    <td>Bakat & Prestasi</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->bakat_prestasi }}</td>
                </tr>

                <tr>
                    <td>Ekstrakurikuler</td>
                    <td>:</td>
                    <td>
                        {{ implode(', ', $pendaftaran->ekstrakurikuler ?? []) }}
                    </td>
                </tr>

                <tr>
                    <td>Size Seragam Pondok</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->size_seragam_pondok }}</td>
                </tr>

                <tr>
                    <td>Size Seragam Formal</td>
                    <td>:</td>
                    <td>{{ $pendaftaran->size_seragam_formal }}</td>
                </tr>

                <tr>
                    <td>Sumber Informasi</td>
                    <td>:</td>
                    <td>
                        {{ implode(', ', $pendaftaran->sumber_info ?? []) }}
                    </td>
                </tr>

            </table>

        </div>

    {{-- TANDA TANGAN --}}
    <table class="signature-table">

        <tr>

            <td>

                <p class="signature-title">
                    Orang Tua / Wali
                </p>

                <div class="signature-space"></div>

                <h5>
                    {{ optional($wali)->nama ?? optional($ayah)->nama }}
                </h5>

            </td>

            <td>

                <p class="signature-title">
                    Hormat Kami, Calon Santri
                </p>

                <div class="signature-space"></div>

                <h5>
                    {{ $pendaftaran->nama_lengkap }}
                </h5>

            </td>

        </tr>

    </table>

    </div>

</body>

</html>
