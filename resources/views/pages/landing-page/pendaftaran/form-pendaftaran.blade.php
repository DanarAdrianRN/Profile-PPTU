@extends('layout.app')
@section('content')
@include('components.header')
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
            <div class="registration-instructions" role="note">
                <p><strong>Semua isian dan seluruh dokumen wajib dilengkapi.</strong> Jika tidak ada data pada isian teks, isi dengan tanda <strong>"-"</strong>.</p>
                <p>Isi tanggal dengan tanggal yang benar. Untuk jumlah yang tidak ada, isi 0.</p>
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
                    enctype="multipart/form-data" novalidate>
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
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="jenis_kelamin" value="L"
                                            {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required>
                                        Laki-laki
                                    </label>
                                    <label>
                                        <input type="radio" name="jenis_kelamin" value="P"
                                            {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required>
                                        Perempuan
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama" value="{{ old('agama') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kewarganegaraan</label>
                                <input type="text" name="kewarganegaraan" value="{{ old('kewarganegaraan') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Anak Ke</label>
                                <input type="number" name="anak_ke" value="{{ old('anak_ke') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Kandung</label>
                                <input type="number" name="jumlah_saudara_kandung"
                                    value="{{ old('jumlah_saudara_kandung') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Angkat</label>
                                <input type="number" name="jumlah_saudara_angkat"
                                    value="{{ old('jumlah_saudara_angkat') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Saudara Tiri</label>
                                <input type="number" name="jumlah_saudara_tiri" value="{{ old('jumlah_saudara_tiri') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status Anak</label>
                                <div class="select-wrapper">
                                    <select name="status_anak" required>
                                        <option value="-" {{ old('status_anak') === '-' ? 'selected' : '' }}>Orang tua lengkap</option>
                                        <option value="" {{ old('status_anak') === '' ? 'selected' : '' }}>Pilih</option>
                                        <option value="Yatim" {{ old('status_anak') === 'Yatim' ? 'selected' : '' }}>Yatim</option>
                                        <option value="Piatu" {{ old('status_anak') === 'Piatu' ? 'selected' : '' }}>Piatu</option>
                                        <option value="Yatim Piatu" {{ old('status_anak') === 'Yatim Piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full">
                                <label>Bahasa Sehari-hari di Rumah</label>
                                <input type="text" name="bahasa_rumah" value="{{ old('bahasa_rumah') }}" required>
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
                                <textarea rows="4" name="alamat" required>{{ old('alamat') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>RT / RW</label>
                                <input type="text" placeholder="contoh: 01/02" name="rt_rw"
                                    value="{{ old('rt_rw') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Desa</label>
                                <input type="text" name="desa" value="{{ old('desa') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Kabupaten</label>
                                <input type="text" name="kabupaten" value="{{ old('kabupaten') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tempat Tinggal</label>
                                <div class="select-wrapper">
                                    <select name="tempat_tinggal" required>
                                        <option value="" {{ old('tempat_tinggal') === '' ? 'selected' : '' }}>Pilih</option>
                                        <option value="Pada Orang Tua" {{ old('tempat_tinggal') === 'Pada Orang Tua' ? 'selected' : '' }}>Pada Orang Tua</option>
                                        <option value="Menumpang" {{ old('tempat_tinggal') === 'Menumpang' ? 'selected' : '' }}>Menumpang</option>
                                        <option value="Di Asrama" {{ old('tempat_tinggal') === 'Di Asrama' ? 'selected' : '' }}>Di Asrama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Jarak Rumah ke Pondok</label>
                                <input type="text" placeholder="Contoh: 10 KM" name="jarak_rumah"
                                    value="{{ old('jarak_rumah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>No HP Orang Tua / Wali</label>
                                <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Berat Badan</label>
                                <input type="text" name="berat_badan" value="{{ old('berat_badan') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tinggi Badan</label>
                                <input type="text" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required>
                            </div>
                            <div class="form-group full">
                                <label>Penyakit yang Pernah Diderita</label>
                                <textarea rows="3" name="riwayat_penyakit" required>{{ old('riwayat_penyakit') }}</textarea>
                            </div>
                            <div class="form-group full">
                                <label>Kelainan Jasmani</label>
                                <textarea rows="3" name="kelainan_jasmani" required>{{ old('kelainan_jasmani') }}</textarea>
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
                                    <select name="jenjang_pendidikan" id="jenjang_pendidikan" required>
                                        <option value="" {{ old('jenjang_pendidikan') === '' ? 'selected' : '' }}>Pilih</option>
                                        <option value="SMP" {{ old('jenjang_pendidikan') === 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMK" {{ old('jenjang_pendidikan') === 'SMK' ? 'selected' : '' }}>SMK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group smk-jurusan">
                                <label>Jurusan SMK</label>
                                <div class="select-wrapper">
                                    <select name="jurusan" id="jurusan">
                                        <option value="" {{ old('jurusan') === '' ? 'selected' : '' }}>Pilih Jurusan</option>
                                        <option value="DKV" {{ old('jurusan') === 'DKV' ? 'selected' : '' }}>DKV</option>
                                        <option value="TBSM" {{ old('jurusan') === 'TBSM' ? 'selected' : '' }}>TBSM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sekolah Asal</label>
                                <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tahun Lulus</label>
                                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal & Nomor Ijazah</label>
                                <input type="text" name="tanggal_nomor_ijazah"
                                    value="{{ old('tanggal_nomor_ijazah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>NISN</label>
                                <input type="text" name="nisn" value="{{ old('nisn') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Lama Belajar</label>
                                <input type="text" placeholder="Contoh: 3 Tahun" name="lama_belajar"
                                    value="{{ old('lama_belajar') }}" required>
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
                                <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status Ayah</label>
                                <div class="select-wrapper">
                                    <select name="status_ayah" required>
                                        <option value="Masih Hidup" {{ old('status_ayah') === 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                        <option value="Sudah Meninggal" {{ old('status_ayah') === 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_ayah" value="{{ old('agama_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_ayah" value="{{ old('pendidikan_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_ayah" value="{{ old('penghasilan_ayah') }}" required>
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <textarea rows="3" name="alamat_ayah" id="alamat_ayah" required>{{ old('alamat_ayah') }}</textarea>
                            </div>
                        </div>
                        <div class="step-title">
                            <h3>Data Ibu</h3>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status Ibu</label>
                                <div class="select-wrapper">
                                    <select name="status_ibu" required>
                                        <option value="Masih Hidup" {{ old('status_ibu') === 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                        <option value="Sudah Meninggal" {{ old('status_ibu') === 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_ibu" value="{{ old('agama_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_ibu" value="{{ old('pendidikan_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_ibu" value="{{ old('penghasilan_ibu') }}" required>
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <div class="form-copy">
                                    <p>Aamat sama dengan Ayah</p>
                                    <input type="checkbox" id="alamat-sama">
                                </div>
                                <textarea rows="3" name="alamat_ibu" id="alamat_ibu" required>{{ old('alamat_ibu') }}</textarea>
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
                                <input type="text" name="nama_wali" value="{{ old('nama_wali') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Status Wali</label>
                                <div class="select-wrapper">
                                    <select name="status_wali" required>
                                        <option value="Masih Hidup" {{ old('status_wali') === 'Masih Hidup' ? 'selected' : '' }}>Masih Hidup</option>
                                        <option value="Sudah Meninggal" {{ old('status_wali') === 'Sudah Meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tempat Lahir</label>
                                <input type="text" name="tempat_lahir_wali" value="{{ old('tempat_lahir_wali') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir_wali" value="{{ old('tanggal_lahir_wali') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Agama</label>
                                <input type="text" name="agama_wali" value="{{ old('agama_wali') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <input type="text" name="pendidikan_wali" value="{{ old('pendidikan_wali') }}" required>
                                </input>
                            </div>
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="pekerjaan_wali" value="{{ old('pekerjaan_wali') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Penghasilan Perbulan</label>
                                <input type="text" name="penghasilan_wali" value="{{ old('penghasilan_wali') }}" required>
                            </div>
                            <div class="form-group full">
                                <label>Alamat Rumah</label>
                                <textarea rows="3" name="alamat_wali" required>{{ old('alamat_wali') }}</textarea>
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
                                    <select name="kemampuan_quran" required>
                                        <option value="Baik" {{ old('kemampuan_quran') === 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Sedang" {{ old('kemampuan_quran') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                        <option value="Kurang Baik" {{ old('kemampuan_quran') === 'Kurang Baik' ? 'selected' : '' }}>Kurang Baik</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Hafalan Surat Pendek</label>
                                <div class="select-wrapper">
                                    <select name="hafalan" required>
                                        <option value="1-5 Surat" {{ old('hafalan') === '1-5 Surat' ? 'selected' : '' }}>1-5 Surat</option>
                                        <option value="5-10 Surat" {{ old('hafalan') === '5-10 Surat' ? 'selected' : '' }}>5-10 Surat</option>
                                        <option value="10-15 Surat" {{ old('hafalan') === '10-15 Surat' ? 'selected' : '' }}>10-15 Surat</option>
                                        <option value="Diatas 15 Surat" {{ old('hafalan') === 'Diatas 15 Surat' ? 'selected' : '' }}>Diatas 15 Surat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Membaca Pegon</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="baca_pegon" value="1" required>
                                        Bisa
                                    </label>
                                    <label>
                                        <input type="radio" name="baca_pegon" value="0" required>
                                        Belum Bisa
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Menulis Pegon</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="tulis_pegon" value="1" required>
                                        Bisa
                                    </label>
                                    <label>
                                        <input type="radio" name="tulis_pegon" value="0" required>
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
                                <textarea rows="4" name="bakat_prestasi" required>{{ old('bakat_prestasi') }}</textarea>
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
                                <label><input type="checkbox" name="ekstrakurikuler[]" value="-"> Tidak ada</label>
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
                                    <select name="size_seragam_pondok" required>
                                        <option value="S" {{ old('size_seragam_pondok') === 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('size_seragam_pondok') === 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('size_seragam_pondok') === 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('size_seragam_pondok') === 'XL' ? 'selected' : '' }}>XL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Size Seragam Formal</label>
                                <div class="select-wrapper">
                                    <select name="size_seragam_formal" required>
                                        <option value="S" {{ old('size_seragam_formal') === 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('size_seragam_formal') === 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('size_seragam_formal') === 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('size_seragam_formal') === 'XL' ? 'selected' : '' }}>XL</option>
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
                                <label><input type="checkbox" name="sumber_info[]" value="-"> Tidak ada</label>
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="akta_kelahiran" class="file-input" required accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="ktp_ortu" required class="file-input" accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="kk" required class="file-input" accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="ijazah" required class="file-input" accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="nisn_file" required class="file-input" accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG/PDF</small>
                                <div class="upload-box">
                                    <input type="file" name="kip" required class="file-input" accept=".jpg,.jpeg,.png,.pdf">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG</small>
                                <div class="upload-box">
                                    <input type="file" name="foto_warna" required class="file-input" accept=".jpg,.jpeg,.png">
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
                                <small>Maks. 5 MB, JPG/JPEG/PNG</small>
                                <div class="upload-box">
                                    <input type="file" name="foto_bw" required class="file-input" accept=".jpg,.jpeg,.png">
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
                    if (!validateStep(currentStep)) return;
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

                function validateStep(index) {
                    const step = steps[index];
                    ['ekstrakurikuler[]', 'sumber_info[]'].forEach(name => {
                        const group = step.querySelectorAll('input[name="' + name + '"]');
                        if (!group.length) return;
                        group[0].setCustomValidity(
                            Array.from(group).some(input => input.checked)
                                ? '' : 'Pilih minimal satu opsi atau pilih Tidak ada.'
                        );
                    });
                    const invalid = Array.from(step.querySelectorAll('input, select, textarea'))
                        .find(input => !input.checkValidity());
                    if (!invalid) return true;
                    currentStep = index;
                    showStep(index);
                    invalid.reportValidity();
                    return false;
                }

                form.addEventListener('submit', function(event) {
                    for (let index = 0; index < steps.length; index++) {
                        if (!validateStep(index)) {
                            event.preventDefault();
                            return;
                        }
                    }
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
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
                        jurusan.required = true;
                        jurusan.disabled = false;

                    } else {

                        jurusanGroup.style.display = "none";
                        jurusan.required = false;
                        jurusan.disabled = true;
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
                                    false;
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
                if (dataSama.checked) syncDataWali();
                if (alamatSama.checked) syncAlamat();

                // ======================================================
                //          Dokumen Upload                   //
                // ======================================================
                form.querySelectorAll('.file-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const file = this.files[0];
                        const uploadBox = this.closest('.upload-box');
                        const status = uploadBox.querySelector('.upload-right');
                        const extension = file ? '.' + file.name.split('.').pop().toLowerCase() : '';
                        const error = file && file.size > 5 * 1024 * 1024
                            ? 'Ukuran file maksimal 5 MB.'
                            : file && !this.accept.split(',').includes(extension)
                                ? 'Format file harus ' + this.accept + '.' : '';
                        this.setCustomValidity(error);
                        status.textContent = error || (file ? file.name : 'Belum Dipilih');
                        uploadBox.classList.toggle('uploaded', !!file && !error);
                        if (error) this.reportValidity();
                    });
                });
            });
        </script>
    @endpush
@endsection
