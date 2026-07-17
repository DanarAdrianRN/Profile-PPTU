@extends('layout.app')

@include('components.header')

@section('content')
    <section class="form-pendaftaran">
        <div class="container">
            <!-- HEADER -->
            <div class="form-header">
                <h2>
                    <i class="fa-solid fa-file-signature"></i>
                    Formulir Pendaftaran Santri Baru
                </h2>
                <p>
                    Lengkapi seluruh data dengan benar sesuai identitas resmi calon santri
                </p>
            </div>
            <!-- STEP PROGRESS -->
            <div class="step-progress">
                <div class="step-item active">
                    <div class="circle">1</div>
                    <span>Identitas</span>
                </div>
                <div class="step-item">
                    <div class="circle">2</div>
                    <span>Tempat Tinggal</span>
                </div>
                <div class="step-item">
                    <div class="circle">3</div>
                    <span>Pendidikan</span>
                </div>
                <div class="step-item">
                    <div class="circle">4</div>
                    <span>Data Orang Tua</span>
                </div>
                <div class="step-item">
                    <div class="circle">5</div>
                    <span>Kemampuan</span>
                </div>
                <div class="step-item">
                    <div class="circle">6</div>
                    <span>Minat & Ekstra</span>
                </div>
                <div class="step-item">
                    <div class="circle">7</div>
                    <span>Finalisasi</span>
                </div>
                <div class="step-item">
                    <div class="circle">8</div>
                    <span>Upload Dokumen</span>
                </div>
            </div>
            <!-- FORM BOX -->
            <div class="form-box">
                <form id="formPendaftaran" action="{{ route('pendaftaran.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- STEP 1 -->
                    <div class="form-step active">
                        <div class="step-title">
                            <h3>Identitas Santri</h3>
                            <p>Informasi dasar calon santri</p>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                            </div>
                            <div class="form-group">
                                <label>Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}">
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="jenis_kelamin" value="L"
                                            {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}>
                                        Laki-laki
                                    </label>
                                    <label>
                                        <input type="radio" name="jenis_kelamin" value="P"
                                            {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}>
                                        Perempuan
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama" value="{{ old('agama') }}">
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="form-group">
                                <label>Kewarganegaraan</label>
                                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan') }}">
                            </div>
                            <div class="form-group">
                                <label>Anak Ke</label>
                                <input type="number" name="anak_ke" value="{{ old('anak_ke') }}">
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Kandung</label>
                                <input type="number" name="jumlah_saudara_kandung"
                                    value="{{ old('jumlah_saudara_kandung') }}">
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Angkat</label>
                                <input type="number" name="jumlah_saudara_angkat"
                                    value="{{ old('jumlah_saudara_angkat') }}">
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Tiri</label>
                                <input type="number" name="jumlah_saudara_tiri" value="{{ old('jumlah_saudara_tiri') }}">
                            </div>
                            <div class="form-group">
                                <label>Status Anak</label>
                                <div class="select-wrapper">
                                    <select name="status_anak">
                                        <option value="">Pilih</option>
                                        <option value="Yatim">Yatim</option>
                                        <option value="Piatu">Piatu</option>
                                        <option value="Yatim Piatu">Yatim Piatu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full">
                                <label>Bahasa Sehari-hari di Rumah</label>
                                <input type="text" name="bahasa_rumah" value="{{ old('bahasa_rumah') }}">
                            </div>
                        </div>
                    </div>
                    <!-- STEP 2 -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Tempat Tinggal & Kesehatan</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group full">
                                <label>Alamat Lengkap</label>
                                <textarea rows="4" name="alamat">{{ old('alamat') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>RT / RW</label>
                                <input type="text" placeholder="contoh: 01/02" name="rt_rw"
                                    value="{{ old('rt_rw') }}">
                            </div>
                            <div class="form-group">
                                <label>Desa</label>
                                <input type="text" name="desa" value="{{ old('desa') }}">
                            </div>
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}">
                            </div>
                            <div class="form-group">
                                <label>Kabupaten</label>
                                <input type="text" name="kabupaten" value="{{ old('kabupaten') }}">
                            </div>
                            <div class="form-group">
                                <label>Tempat Tinggal</label>
                                <div class="select-wrapper">
                                    <select name="tempat_tinggal">
                                        <option value="">Pilih</option>
                                        <option value="Pada Orang Tua">Pada Orang Tua</option>
                                        <option value="Menumpang">Menumpang</option>
                                        <option value="Di Asrama">Di Asrama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Jarak Rumah ke Pondok</label>
                                <input type="text" placeholder="Contoh: 10 KM" name="jarak_rumah"
                                    value="{{ old('jarak_rumah') }}">
                            </div>
                            <div class="form-group">
                                <label>No HP Orang Tua / Wali</label>
                                <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}">
                            </div>
                            <div class="form-group">
                                <label>Berat Badan</label>
                                <input type="text" name="berat_badan" value="{{ old('berat_badan') }}">
                            </div>
                            <div class="form-group">
                                <label>Tinggi Badan</label>
                                <input type="text" name="tinggi_badan" value="{{ old('tinggi_badan') }}">
                            </div>
                            <div class="form-group full">
                                <label>Penyakit yang Pernah Diderita</label>
                                <textarea rows="3" name="riwayat_penyakit">{{ old('riwayat_penyakit') }}</textarea>
                            </div>
                            <div class="form-group full">
                                <label>Kelainan Jasmani</label>
                                <textarea rows="3" name="kelainan_jasmani">{{ old('kelainan_jasmani') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- STEP 3 -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Pendidikan Formal yang dipilih dan Asal Sekolah</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Jenjang Pendidikan</label>
                                <div class="select-wrapper">
                                    <select name="jenjang_pendidikan" id="jenjang_pendidikan">
                                        <option value="">Pilih</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMK">SMK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group smk-jurusan">
                                <label>Jurusan SMK</label>
                                <div class="select-wrapper">
                                    <select name="jurusan" id="jurusan">
                                        <option value="">Pilih Jurusan</option>
                                        <option value="DKV">DKV</option>
                                        <option value="TBSM">TBSM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sekolah Asal</label>
                                <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}">
                            </div>
                            <div class="form-group">
                                <label>Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal & Nomor Ijazah</label>
                                <input type="text" name="tanggal_nomor_ijazah"
                                    value="{{ old('tanggal_nomor_ijazah') }}">
                            </div>
                            <div class="form-group">
                                <label>NISN</label>
                                <input type="text" name="nisn" value="{{ old('nisn') }}">
                            </div>
                            <div class="form-group">
                                <label>Lama Belajar</label>
                                <input type="text" placeholder="Contoh: 3 Tahun" name="lama_belajar"
                                    value="{{ old('lama_belajar') }}">
                            </div>
                        </div>
                    </div>
                    <!-- STEP 4 -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Data Ayah</h3>
                        </div>
                        <div class="form-grid" id="data-ayah">
                            <div class="form-group">
                                <label>Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Status Ayah</label>
                                <div class="select-wrapper">
                                    <select name="status_ayah">
                                        <option value="Masih Hidup">Masih Hidup</option>
                                        <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_ayah" value="{{ old('agama_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_ayah" value="{{ old('pendidikan_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}">
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah') }}">
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <textarea rows="3" name="alamat_ayah" id="alamat_ayah">{{ old('alamat_ayah') }}</textarea>
                            </div>
                        </div>
                        <div class="step-title">
                            <h3>Data Ibu</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Status Ibu</label>
                                <div class="select-wrapper">
                                    <select name="status_ibu">
                                        <option value="Masih Hidup">Masih Hidup</option>
                                        <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_ibu" value="{{ old('agama_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_ibu" value="{{ old('pendidikan_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}">
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu') }}">
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <div class="form-copy">
                                    <p>Aamat sama dengan Ayah</p>
                                    <input type="checkbox" id="alamat-sama">
                                </div>
                                <textarea rows="3" name="alamat_ibu" id="alamat_ibu">{{ old('alamat_ibu') }}</textarea>
                            </div>
                        </div>
                        <div class="step-title">
                            <h3>Data Wali</h3>
                        </div>
                        <div class="form-copy">
                            <label>Data sama dengan Ayah</label>
                            <input type="checkbox" id="data-sama">
                        </div>
                        <div class="form-grid" id="data-wali">
                            <div class="form-group">
                                <label>Nama Wali</label>
                                <input type="text" name="nama_wali" value="{{ old('nama_wali') }}">
                            </div>
                            <div class="form-group">
                                <label>Status Wali</label>
                                <div class="select-wrapper">
                                    <select name="status_wali">
                                        <option value="Masih Hidup">Masih Hidup</option>
                                        <option value="Sudah Meninggal">Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_wali" value="{{ old('tempat_lahir_wali') }}">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_wali" value="{{ old('tanggal_lahir_wali') }}">
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_wali" value="{{ old('agama_wali') }}">
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_wali" value="{{ old('pendidikan_wali') }}">
                                </input>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_wali" value="{{ old('pekerjaan_wali') }}">
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_wali" value="{{ old('penghasilan_wali') }}">
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <textarea rows="3" name="alamat_wali">{{ old('alamat_wali') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- STEP KEPESANTRENAN -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Kemampuan Kepesantrenan</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Kemampuan Membaca Al-Qur'an</label>
                                <div class="select-wrapper">
                                    <select name="kemampuan_quran">
                                        <option value="Baik">Baik</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Kurang Baik">Kurang Baik</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Hafalan Surat Pendek</label>
                                <div class="select-wrapper">
                                    <select name="hafalan">
                                        <option value="1-5 Surat">1-5 Surat</option>
                                        <option value="5-10 Surat">5-10 Surat</option>
                                        <option value="10-15 Surat">10-15 Surat</option>
                                        <option value="Diatas 15 Surat">Diatas 15 Surat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Membaca Pegon</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="baca_pegon" value="1">
                                        Bisa
                                    </label>
                                    <label>
                                        <input type="radio" name="baca_pegon" value="0">
                                        Belum Bisa
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Menulis Pegon</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="tulis_pegon" value="1">
                                        Bisa
                                    </label>
                                    <label>
                                        <input type="radio" name="tulis_pegon" value="0">
                                        Belum Bisa
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- STEP EKSTRA -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Minat & Ekstrakurikuler</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group full">
                                <label>Bakat & Prestasi</label>
                                <textarea rows="4" name="bakat_prestasi">{{ old('bakat_prestasi') }}</textarea>
                            </div>
                            <div class="form-group full">
                                <label>Ekstrakurikuler yang Diminati</label>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Pramuka">
                                        Pramuka
                                    </label>
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Kaligrafi">
                                        Kaligrafi
                                    </label>
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Hadrah">
                                        Hadrah
                                    </label>
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Drumband">
                                        Drumband
                                    </label>
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Tilawah">
                                        Tilawah
                                    </label>
                                    <label>
                                        <input type="checkbox" name="ekstrakurikuler[]" value="Futsal">
                                        Futsal
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- STEP FINAL -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Finalisasi</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Size Seragam Pondok</label>
                                <div class="select-wrapper">
                                    <select name="size_seragam_pondok">
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Size Seragam Formal</label>
                                <div class="select-wrapper">
                                    <select name="size_seragam_formal">
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full">
                                <label>Informasi Masuk Pesantren Dari</label>
                                <div class="checkbox-group">
                                    <label>
                                        <input type="checkbox" name="sumber_info[]" value="Media Sosial">
                                        Media Sosial
                                    </label>
                                    <label>
                                        <input type="checkbox" name="sumber_info[]" value="Alumni">
                                        Alumni
                                    </label>
                                    <label>
                                        <input type="checkbox" name="sumber_info[]" value="Wali Santri">
                                        Wali Santri
                                    </label>
                                    <label>
                                        <input type="checkbox" name="sumber_info[]" value="Lain-lain">
                                        Lain-lain
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- STEP DOKUMEN -->
                    <div class="form-step">
                        <div class="step-title">
                            <h3>Upload Dokumen</h3>
                        </div>
                        <div class="form-grid">
                            <div class="upload-card">
                                <label>Akta Kelahiran</label>
                                <div class="upload-box">
                                    <input type="file" name="kk" class="file-input">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>KTP Orang Tua</label>
                                <div class="upload-box">
                                    <input type="file" name="ktp_ortu">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>Kartu Keluarga</label>
                                <div class="upload-box">
                                    <input type="file" name="kk">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>Ijazah / SKL</label>
                                <div class="upload-box">
                                    <input type="file" name="ijazah">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>NISN</label>
                                <div class="upload-box">
                                    <input type="file" name="nisn_file">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>KKS / SKTM / PKH / KIP</label>
                                <div class="upload-box">
                                    <input type="file" name="kip">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>Foto Berwarna 3x4</label>
                                <div class="upload-box">
                                    <input type="file" name="foto_warna">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-card">
                                <label>Foto Hitam Putih 3x4</label>
                                <div class="upload-box">
                                    <input type="file" name="foto_bw">
                                    <div class="upload-content">
                                        <div class="upload-left">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                            <span>Pilih File</span>
                                        </div>
                                        <div class="upload-right">
                                            Belum Dipilih
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- NAVIGATION -->
                    <div class="form-navigation">
                        <button type="button" class="btn-form secondary" id="prevBtn" style="display: none;">
                            <i class="fa-solid fa-arrow-left"></i>
                            Sebelumnya
                        </button>
                        <button type="button" class="btn-form primary" id="nextBtn">
                            Selanjutnya
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn-form primary" id="submitBtn" style="display: none;">
                            <i class="fa-solid fa-paper-plane"></i>
                            Kirim Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @include('components.footer')
    @push('script')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById("formPendaftaran");
                const steps = document.querySelectorAll(".form-step");
                const progress = document.querySelectorAll(".step-item");
                const nextBtn = document.getElementById("nextBtn");
                const prevBtn = document.getElementById("prevBtn");
                const submitBtn = document.getElementById("submitBtn");
                let currentStep = 0;

                showStep(currentStep);

                function showStep(index) {
                    steps.forEach(step => step.classList.remove("active"));
                    progress.forEach(item => item.classList.remove("active"));

                    steps[index].classList.add("active");

                    for (let i = 0; i <= index; i++) {
                        progress[i]?.classList.add("active");
                    }

                    // toggle tombol previous
                    prevBtn.style.display = index === 0 ? "none" : "flex";

                    // toggle next vs submit
                    const isLastStep = index === steps.length - 1;
                    nextBtn.style.display = isLastStep ? "none" : "flex";
                    submitBtn.style.display = isLastStep ? "flex" : "none";
                }

                nextBtn.addEventListener("click", function() {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);

                        window.scrollTo({
                            top: document.querySelector(".form-pendaftaran").offsetTop - 80,
                            behavior: "smooth"
                        });
                    }
                });

                prevBtn.addEventListener("click", function() {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);

                        window.scrollTo({
                            top: document.querySelector(".form-pendaftaran").offsetTop - 80,
                            behavior: "smooth"
                        });
                    }
                });

                // Optional: tampilkan loading saat submit (tidak ganggu token)
                document.getElementById("formPendaftaran").addEventListener("submit", function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...`;
                });

                // ======================================================
                // LOAD DATA DRAFT
                // ======================================================

                const savedData = JSON.parse(
                    localStorage.getItem("draft_pendaftaran") || "{}"
                );

                if (Object.keys(savedData).length > 0) {

                    Object.keys(savedData).forEach(name => {

                        // skip csrf token
                        if (name === "_token") return;

                        const field = form.querySelector(`[name="${name}"]`);

                        if (!field) return;

                        // checkbox
                        if (field.type === "checkbox") {

                            const checkboxes = form.querySelectorAll(
                                `[name="${name}"]`
                            );

                            checkboxes.forEach(cb => {

                                if (
                                    Array.isArray(savedData[name]) &&
                                    savedData[name].includes(cb.value)
                                ) {
                                    cb.checked = true;
                                }

                            });

                        }

                        // radio
                        else if (field.type === "radio") {

                            const radios = form.querySelectorAll(
                                `[name="${name}"]`
                            );

                            radios.forEach(radio => {

                                if (radio.value == savedData[name]) {
                                    radio.checked = true;
                                }

                            });

                        }

                        // file tidak bisa direstore
                        else if (field.type === "file") {

                            return;

                        }

                        // input biasa
                        else {

                            field.value = savedData[name] ?? "";

                        }

                    });

                }
                // ======================================================
                // SAVE DATA DRAFT
                // ======================================================

                form.addEventListener("input", function() {

                    let formData = {};

                    const inputs = form.querySelectorAll(
                        "input, textarea, select"
                    );

                    inputs.forEach(input => {

                        // skip csrf token
                        if (input.name === "_token") return;

                        // skip file upload
                        if (input.type === "file") return;

                        // checkbox
                        if (input.type === "checkbox") {

                            if (!formData[input.name]) {
                                formData[input.name] = [];
                            }

                            if (input.checked) {
                                formData[input.name].push(input.value);
                            }

                        }

                        // radio
                        else if (input.type === "radio") {

                            if (input.checked) {
                                formData[input.name] = input.value;
                            }

                        }

                        // normal input
                        else {

                            formData[input.name] = input.value;

                        }

                    });

                    localStorage.setItem(
                        "draft_pendaftaran",
                        JSON.stringify(formData)
                    );

                });

                // ======================================================
                // JURUSAN
                // ======================================================

                const jenjangPendidikan = document.getElementById("jenjang_pendidikan");
                const jurusan = document.getElementById("jurusan");
                const jurusanGroup = document.querySelector(".smk-jurusan");

                function toggleJurusan() {

                    if (jenjangPendidikan.value === "SMK") {

                        jurusanGroup.style.display = "block";

                    } else {

                        jurusanGroup.style.display = "none";
                        jurusan.value = "";
                    }
                }

                // saat halaman pertama dibuka
                toggleJurusan();

                // saat pilihan jenjang berubah
                jenjangPendidikan.addEventListener("change", toggleJurusan);

                // ======================================================
                // DATA ORANG TUA / WALI
                // ======================================================

                const dataAyah =
                    document.getElementById(
                        "data-ayah"
                    );

                const dataWali =
                    document.getElementById(
                        "data-wali"
                    );

                const dataSama =
                    document.getElementById(
                        "data-sama"
                    );

                function syncDataWali() {

                    const waliInputs =
                        dataWali.querySelectorAll(
                            "input, select, textarea"
                        );

                    if (dataSama.checked) {

                        waliInputs.forEach(input => {

                            // skip checkbox
                            if (
                                input.type ===
                                "checkbox"
                            ) return;

                            // ubah nama wali -> ayah
                            const ayahName =
                                input.name.replace(
                                    "wali",
                                    "ayah"
                                );

                            const ayahInput =
                                dataAyah.querySelector(
                                    `[name="${ayahName}"]`
                                );

                            if (!ayahInput) return;

                            // copy value
                            input.value =
                                ayahInput.value;

                            // lock field
                            if (
                                input.tagName ===
                                "INPUT" ||
                                input.tagName ===
                                "TEXTAREA"
                            ) {

                                input.readOnly =
                                    true;

                            } else if (
                                input.tagName ===
                                "SELECT"
                            ) {

                                input.disabled =
                                    true;
                            }
                        });

                    } else {

                        waliInputs.forEach(input => {

                            if (
                                input.type ===
                                "checkbox"
                            ) return;

                            input.value = "";

                            input.readOnly =
                                false;

                            input.disabled =
                                false;
                        });
                    }
                }

                // saat checkbox berubah
                dataSama.addEventListener(
                    "change",
                    syncDataWali
                );

                // realtime update kalau data ayah diubah
                dataAyah.addEventListener(
                    "input",
                    function() {

                        if (
                            dataSama.checked
                        ) {

                            syncDataWali();
                        }
                    }
                );


                // ======================================================
                // ALAMAT SAMA
                // ======================================================

                const alamatAyah =
                    document.getElementById(
                        "alamat_ayah"
                    );

                const alamatIbu =
                    document.getElementById(
                        "alamat_ibu"
                    );

                const alamatSama =
                    document.getElementById(
                        "alamat-sama"
                    );

                function syncAlamat() {

                    if (
                        alamatSama.checked
                    ) {

                        alamatIbu.value =
                            alamatAyah.value;

                        alamatIbu.readOnly =
                            true;

                    } else {

                        alamatIbu.value = "";

                        alamatIbu.readOnly =
                            false;
                    }
                }

                // checkbox alamat
                alamatSama.addEventListener(
                    "change",
                    syncAlamat
                );

                // realtime kalau alamat ayah berubah
                alamatAyah.addEventListener(
                    "input",
                    function() {

                        if (
                            alamatSama.checked
                        ) {

                            alamatIbu.value =
                                alamatAyah.value;
                        }
                    }
                );

                // jalankan saat page load
                syncDataWali();
                syncAlamat();

                // ======================================================
                //          Dokumen Upload                   //
                // ======================================================
                document.querySelectorAll(".file-input")
                    .forEach(input => {
                        input.addEventListener("change", function() {
                            const file = this.files[0];
                            if (!file) return;
                            const uploadBox =
                                this.closest(".upload-box");
                            const status =
                                uploadBox.querySelector(".upload-right");
                            status.innerHTML =
                                `<i class="fa-solid fa-circle-check"></i>
                                ${file.name}`;
                            uploadBox.classList.add("uploaded");
                        });
                    });
            });
        </script>
    @endpush
@endsection
