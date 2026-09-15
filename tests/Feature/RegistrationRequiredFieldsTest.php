<?php
namespace Tests\Feature;

use App\Http\Requests\StorePendaftaranRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegistrationRequiredFieldsTest extends TestCase
{
    private function validateData(array $data)
    {
        $request = StorePendaftaranRequest::create('/', 'POST', ['nisn' => '-']);
        return Validator::make($data, $request->rules(), $request->messages());
    }

    public function test_missing_fields_and_all_documents_are_rejected(): void
    {
        $errors = $this->validateData([])->errors();
        foreach (['nama_panggilan', 'tanggal_lahir', 'alamat_ibu', 'alamat_wali',
            'ekstrakurikuler', 'sumber_info', 'akta_kelahiran', 'ktp_ortu', 'kk',
            'ijazah', 'nisn_file', 'kip', 'foto_warna', 'foto_bw'] as $field) {
            $this->assertTrue($errors->has($field), $field);
        }
    }

    public function test_placeholder_zero_and_smp_without_major_are_accepted(): void
    {
        $errors = $this->validateData([
            'nisn' => '-', 'alamat_ibu' => '-', 'riwayat_penyakit' => '-',
            'jumlah_saudara_kandung' => 0, 'baca_pegon' => '0', 'tulis_pegon' => '0',
            'jenjang_pendidikan' => 'SMP', 'ekstrakurikuler' => ['-'], 'sumber_info' => ['-'],
        ])->errors();
        foreach (['nisn', 'alamat_ibu', 'riwayat_penyakit', 'jumlah_saudara_kandung',
            'baca_pegon', 'tulis_pegon', 'jurusan', 'ekstrakurikuler', 'sumber_info'] as $field) {
            $this->assertFalse($errors->has($field), $field);
        }
        $this->assertTrue($this->validateData(['jenjang_pendidikan' => 'SMK'])->errors()->has('jurusan'));
    }

    public function test_upload_limit_and_format_are_checked_on_server(): void
    {
        $errors = $this->validateData([
            'akta_kelahiran' => UploadedFile::fake()->create('akta.pdf', 5120, 'application/pdf'),
            'kk' => UploadedFile::fake()->create('kk.pdf', 5121, 'application/pdf'),
            'ktp_ortu' => UploadedFile::fake()->create('ktp.txt', 1, 'text/plain'),
            'foto_warna' => UploadedFile::fake()->create('foto.pdf', 1, 'application/pdf'),
        ])->errors();
        $this->assertFalse($errors->has('akta_kelahiran'));
        $this->assertTrue($errors->has('kk'));
        $this->assertTrue($errors->has('ktp_ortu'));
        $this->assertTrue($errors->has('foto_warna'));
    }
}
