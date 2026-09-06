<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\GelombangPendaftaranController;
use App\Http\Controllers\Admin\JadwalPendaftaranController;
use App\Http\Controllers\Admin\MasterPembayaranController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\LandingPage\MasterPembayaranController as PublicInformationController;
use App\Models\GelombangPendaftaran;
use App\Models\JadwalPendaftaran;
use App\Models\Pembayaran;
use App\Models\Periode;
use App\Models\Promo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegistrationPeriodArchiveTest extends TestCase
{
    use RefreshDatabase;

    private function seedPeriod(bool $active): array
    {
        $period = Periode::create(['nama_periode' => $active ? '2026/2027' : '2025/2026', 'is_active' => $active]);
        $wave = GelombangPendaftaran::create(['periode_id' => $period->id, 'nama_gelombang' => 'Gelombang 1',
            'tanggal_mulai' => now()->subDay(), 'tanggal_selesai' => now()->addDays(10), 'is_publish' => true]);
        $future = GelombangPendaftaran::create(['periode_id' => $period->id, 'nama_gelombang' => 'Gelombang 2',
            'tanggal_mulai' => now()->addDays(20), 'tanggal_selesai' => now()->addDays(30), 'is_publish' => true]);
        $schedule = JadwalPendaftaran::create(['periode_id' => $period->id, 'nama_jadwal' => 'Tes',
            'tanggal' => now(), 'is_publish' => true]);
        $fee = Pembayaran::create(['periode_id' => $period->id, 'nama_pembayaran' => 'Pendaftaran Pondok',
            'jenjang' => 'SMP', 'kategori' => 'Biaya Tahunan', 'nominal' => 100000, 'is_active' => true]);
        $promo = Promo::create(['periode_id' => $period->id, 'nama_promo' => 'Promo', 'tipe' => 'nominal',
            'nilai' => 10000, 'is_active' => true]);
        $promo->pembayarans()->attach($fee);
        return compact('period', 'wave', 'future', 'schedule', 'fee', 'promo');
    }

    private function assertAdminPeriod(array $expected, bool $archive): void
    {
        foreach ([
            [new GelombangPendaftaranController, 'gelombangs', [$expected['wave']->id, $expected['future']->id]],
            [new JadwalPendaftaranController, 'jadwals', [$expected['schedule']->id]],
            [new MasterPembayaranController, 'pembayarans', [$expected['fee']->id]],
            [new PromoController, 'promos', [$expected['promo']->id]],
        ] as [$controller, $key, $ids]) {
            $data = $controller->index()->getData();
            $this->assertEqualsCanonicalizing($ids, $data[$key]->modelKeys());
            $this->assertEquals($expected['period']->id, $data['selectedPeriodeId']);
            $this->assertSame($archive, $data['isArsip']);
        }
        $data = (new PromoController)->index()->getData();
        $this->assertSame([$expected['future']->id], $data['promoGelombangs']->modelKeys());
        $this->assertSame([$expected['fee']->id], $data['promoPembayarans']->modelKeys());
    }

    public function test_admin_lists_and_promo_options_follow_active_or_viewed_archive(): void
    {
        $old = $this->seedPeriod(false);
        $active = $this->seedPeriod(true);
        $this->assertAdminPeriod($active, false);
        session(['viewing_periode_id' => $old['period']->id]);
        $this->assertAdminPeriod($old, true);
        session()->forget('viewing_periode_id');
        $this->assertAdminPeriod($active, false);
    }

    public function test_public_information_ignores_admin_archive_and_only_shows_active_period(): void
    {
        $old = $this->seedPeriod(false);
        $active = $this->seedPeriod(true);
        session(['viewing_periode_id' => $old['period']->id]);
        $data = (new PublicInformationController)->index()->getData();
        $this->assertEqualsCanonicalizing([$active['wave']->id, $active['future']->id], $data['gelombangs']->modelKeys());
        $this->assertSame($active['wave']->id, $data['gelombangAktif']->id);
        $this->assertSame([$active['schedule']->id], $data['jadwalPendaftarans']->modelKeys());
        $this->assertSame([$active['fee']->id], $data['pembayarans']->modelKeys());
        $this->assertSame([$active['wave']->id], GelombangPendaftaran::aktif()->pluck('id')->all());
        $active['period']->update(['is_active' => false]);
        $old['period']->update(['is_active' => true]);
        $data = (new PublicInformationController)->index()->getData();
        $this->assertSame($old['wave']->id, $data['gelombangAktif']->id);
        $this->assertSame([$old['fee']->id], $data['pembayarans']->modelKeys());
    }

    public function test_no_active_period_does_not_expose_archive(): void
    {
        $this->seedPeriod(false);
        $data = (new PublicInformationController)->index()->getData();
        $this->assertEmpty($data['gelombangs']);
        $this->assertEmpty($data['pembayarans']);
        $this->assertEmpty($data['jadwalPendaftarans']);
        $this->assertNull($data['gelombangAktif']);
        $this->assertEmpty((new GelombangPendaftaranController)->index()->getData()['gelombangs']);
    }

    public function test_new_fees_and_global_promos_are_bound_to_selected_period(): void
    {
        $old = $this->seedPeriod(false);
        $this->seedPeriod(true);
        session(['viewing_periode_id' => $old['period']->id]);
        (new MasterPembayaranController)->store(Request::create('/', 'POST', [
            'nama_pembayaran' => 'Biaya Baru', 'jenjang' => 'SMP', 'kategori' => 'Biaya Tahunan', 'nominal' => '10000',
        ]));
        $fee = Pembayaran::where('nama_pembayaran', 'Biaya Baru')->firstOrFail();
        $this->assertEquals($old['period']->id, $fee->periode_id);
        (new PromoController)->store(Request::create('/', 'POST', [
            'nama_promo' => 'Promo Baru', 'cakupan_gelombang' => 'semua', 'cakupan_biaya' => 'semua',
            'tipe' => 'nominal', 'nilai' => 1000,
        ]));
        $promo = Promo::where('nama_promo', 'Promo Baru')->firstOrFail();
        $this->assertEquals($old['period']->id, $promo->periode_id);
        $this->assertEqualsCanonicalizing([$old['fee']->id, $fee->id], $promo->pembayarans->modelKeys());
    }

    public function test_promo_cannot_reference_wave_or_fee_from_another_period(): void
    {
        $old = $this->seedPeriod(false);
        $active = $this->seedPeriod(true);
        try {
            (new PromoController)->store(Request::create('/', 'POST', [
                'periode_id' => $active['period']->id, 'nama_promo' => 'Invalid',
                'cakupan_gelombang' => 'satu', 'gelombang_pendaftaran_id' => $old['wave']->id,
                'cakupan_biaya' => 'satu', 'pembayaran_ids' => [$old['fee']->id], 'tipe' => 'nominal', 'nilai' => 1000,
            ]));
            $this->fail('Cross-period promo must be rejected.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('gelombang_pendaftaran_id', $exception->errors());
            $this->assertArrayHasKey('pembayaran_ids.0', $exception->errors());
        }
        $this->assertFalse(Promo::where('nama_promo', 'Invalid')->exists());
    }

    public function test_legacy_backfill_preserves_existing_periods_and_links_promos_to_waves(): void
    {
        $old = $this->seedPeriod(false);
        $active = $this->seedPeriod(true);
        $migration = require database_path('migrations/2026_09_06_000001_scope_registration_information_to_periods.php');
        $migration->down();
        $active['wave']->update(['periode_id' => null]);
        $active['schedule']->update(['periode_id' => null]);
        $old['promo']->update(['gelombang_pendaftaran_id' => $old['wave']->id]);
        $migration->up();
        $this->assertEquals($old['period']->id, $old['wave']->fresh()->periode_id);
        $this->assertEquals($old['period']->id, $old['promo']->fresh()->periode_id);
        $this->assertEquals($active['period']->id, $active['wave']->fresh()->periode_id);
        $this->assertEquals($active['period']->id, $active['schedule']->fresh()->periode_id);
        $this->assertEquals($active['period']->id, $active['fee']->fresh()->periode_id);
    }
}
