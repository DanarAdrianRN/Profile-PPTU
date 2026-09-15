<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:Y-m-d|before_or_equal:today',
            'kewarganegaraan' => 'required|string|max:255',
            'anak_ke' => 'required|integer|min:1',
            'jumlah_saudara_kandung' => 'required|integer|min:0',
            'jumlah_saudara_angkat' => 'required|integer|min:0',
            'jumlah_saudara_tiri' => 'required|integer|min:0',
            'status_anak' => 'required|string|max:255',
            'bahasa_rumah' => 'required|string|max:255',
            'alamat' => 'required|string|max:5000',
            'rt_rw' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'tempat_tinggal' => 'required|string|max:255',
            'jarak_rumah' => 'required|string|max:255',
            'no_hp_ortu' => 'required|string|max:255',
            'berat_badan' => 'required|string|max:255',
            'tinggi_badan' => 'required|string|max:255',
            'riwayat_penyakit' => 'required|string|max:5000',
            'kelainan_jasmani' => 'required|string|max:5000',
            'jenjang_pendidikan' => 'required|in:SMP,SMK',
            'jurusan' => 'required_if:jenjang_pendidikan,SMK|nullable|in:DKV,TBSM',
            'sekolah_asal' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:1900|max:2100',
            'tanggal_nomor_ijazah' => 'required|string|max:255',
            'nisn' => 'required|string|max:20',
            'lama_belajar' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'status_ayah' => 'required|string|max:255',
            'tempat_lahir_ayah' => 'required|string|max:255',
            'tanggal_lahir_ayah' => 'required|date_format:Y-m-d|before_or_equal:today',
            'agama_ayah' => 'required|string|max:255',
            'pendidikan_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'penghasilan_ayah' => 'required|string|max:255',
            'alamat_ayah' => 'required|string|max:5000',
            'nama_ibu' => 'required|string|max:255',
            'status_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'required|string|max:255',
            'tanggal_lahir_ibu' => 'required|date_format:Y-m-d|before_or_equal:today',
            'agama_ibu' => 'required|string|max:255',
            'pendidikan_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'penghasilan_ibu' => 'required|string|max:255',
            'alamat_ibu' => 'required|string|max:5000',
            'nama_wali' => 'required|string|max:255',
            'status_wali' => 'required|string|max:255',
            'tempat_lahir_wali' => 'required|string|max:255',
            'tanggal_lahir_wali' => 'required|date_format:Y-m-d|before_or_equal:today',
            'agama_wali' => 'required|string|max:255',
            'pendidikan_wali' => 'required|string|max:255',
            'pekerjaan_wali' => 'required|string|max:255',
            'penghasilan_wali' => 'required|string|max:255',
            'alamat_wali' => 'required|string|max:5000',
            'kemampuan_quran' => 'required|string|max:255',
            'hafalan' => 'required|string|max:255',
            'baca_pegon' => 'required|boolean',
            'tulis_pegon' => 'required|boolean',
            'bakat_prestasi' => 'required|string|max:5000',
            'ekstrakurikuler' => 'required|array|min:1',
            'size_seragam_pondok' => 'required|string|max:255',
            'size_seragam_formal' => 'required|string|max:255',
            'sumber_info' => 'required|array|min:1',
            'akta_kelahiran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ktp_ortu' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'ijazah' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'nisn_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'kip' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'foto_warna' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'foto_bw' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'ekstrakurikuler.*' => 'required|in:Pramuka,Kaligrafi,Hadrah,Drumband,Tilawah,Futsal,-',
            'sumber_info.*' => 'required|in:Media Sosial,Alumni,Wali Santri,Lain-lain,-',
        ];
        if ($this->input('nisn') !== '-') {
            $rules['nisn'] = ['required', 'string', 'max:20', Rule::unique('pendaftaran_pendidikans', 'nisn')];
        }
        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['nisn' => trim((string) $this->input('nisn'))]);
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'required_if' => 'Kolom :attribute wajib diisi untuk jenjang SMK.',
            'max.file' => 'Ukuran :attribute maksimal 5 MB.',
            'mimes' => 'Format :attribute tidak sesuai dengan format yang diperbolehkan.',
        ];
    }
}
