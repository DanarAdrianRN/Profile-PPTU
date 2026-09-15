<?php

namespace Tests\Feature;

use App\Http\Controllers\LandingPage\DaftarUlangController;
use App\Models\{Pembayaran, Pendaftaran, Periode, Promo};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoQuotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_quota_counts_students_once_and_preserves_existing_discounts(): void
    {
        // Kolom ini tersedia pada database lama aplikasi, belum pada migrasi awal.
        if (! \Illuminate\Support\Facades\Schema::hasColumn('pendaftarans', 'periode_id')) {
            \Illuminate\Support\Facades\Schema::table('pendaftarans', function ($table) {
                $table->foreignId('periode_id')->nullable()->constrained('periodes');
            });
        }
        $period = Periode::create(['nama_periode' => '2026/2027', 'is_active' => true]);
        $payments = collect(['Seragam', 'Kesantrian'])->map(fn ($name) => Pembayaran::create([
            'periode_id' => $period->id, 'jenjang' => 'SMP', 'kategori' => 'Biaya Tahunan',
            'nama_pembayaran' => $name, 'nominal' => 100000, 'is_active' => true,
        ]));
        $promo = Promo::create([
            'periode_id' => $period->id, 'nama_promo' => 'Pendaftar pertama',
            'tipe' => 'nominal', 'nilai' => 20000, 'kuota' => 1, 'is_active' => true,
        ]);
        $promo->pembayarans()->sync($payments->pluck('id'));
        $students = collect([1, 2])->map(fn ($id) => Pendaftaran::create([
            'periode_id' => $period->id, 'kode_pendaftaran' => 'TEST-' . $id,
            'nama_lengkap' => 'Santri ' . $id, 'jenis_kelamin' => 'L', 'agama' => 'Islam',
            'tempat_lahir' => 'Magetan', 'tanggal_lahir' => '2012-01-01',
        ]));
        $controller = new DaftarUlangController;
        $first = $controller->show($students[0])->getData()['tagihan'];
        $this->assertEquals(40000, $first->potongan_promo);
        $this->assertEquals(1, $promo->fresh()->terpakai);

        // Simulasikan penghitung lama yang belum pernah bertambah.
        $promo->update(['terpakai' => 0]);
        $second = $controller->show($students[1])->getData()['tagihan'];
        $this->assertEquals(0, $second->potongan_promo);
        $this->assertEquals(200000, $second->nominal_akhir);
        $this->assertEquals(1, $promo->fresh()->terpakai);

        $again = $controller->show($students[0])->getData()['tagihan'];
        $this->assertEquals(40000, $again->potongan_promo);
        $this->assertCount(2, $again->details);
        $this->assertEquals(1, $promo->fresh()->terpakai);

        $newPayment = Pembayaran::create([
            'periode_id' => $period->id, 'jenjang' => 'SMP', 'kategori' => 'Biaya Bulanan',
            'nama_pembayaran' => 'Makan', 'nominal' => 100000, 'is_active' => true,
        ]);
        $promo->pembayarans()->attach($newPayment);
        $again = $controller->show($students[0])->getData()['tagihan'];
        $this->assertEquals(60000, $again->potongan_promo);
        $this->assertEquals(1, $promo->fresh()->terpakai);

        $promo->update(['kuota' => null]);
        $second = $controller->show($students[1])->getData()['tagihan'];
        $this->assertEquals(60000, $second->potongan_promo);
        $this->assertEquals(2, $promo->fresh()->terpakai);
    }
}
