<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\GelombangPendaftaranController;
use App\Http\Controllers\Admin\GaleriController;
use App\Models\GelombangPendaftaran;
use App\Models\Periode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class GelombangPeriodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_period_can_be_saved_changed_and_cleared(): void
    {
        $period = Periode::create(['nama_periode' => '2026/2027', 'is_active' => true]);
        $data = ['nama_gelombang' => 'Gelombang 1', 'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-06-30', 'periode_id' => $period->id];
        $controller = new GelombangPendaftaranController;
        $controller->store(Request::create('/', 'POST', $data));
        $wave = GelombangPendaftaran::firstOrFail();
        $this->assertSame('2026/2027', $wave->periode->nama_periode);
        $this->assertTrue($period->gelombangPendaftarans->first()->is($wave));
        $other = Periode::create(['nama_periode' => '2027/2028']);
        $data['periode_id'] = $other->id;
        $controller->update(Request::create('/', 'POST', $data), $wave->id);
        $this->assertEquals($other->id, $wave->fresh()->periode_id);
        $data['periode_id'] = null;
        $controller->update(Request::create('/', 'POST', $data), $wave->id);
        $this->assertNull($wave->fresh()->periode_id);
    }

    public function test_nonexistent_period_is_rejected(): void
    {
        $this->expectException(ValidationException::class);
        (new GelombangPendaftaranController)->store(Request::create('/', 'POST', [
            'nama_gelombang' => 'Gelombang 1', 'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-06-30', 'periode_id' => 999,
        ]));
    }

    public function test_gallery_page_size_is_honoured_and_invalid_size_defaults_to_ten(): void
    {
        foreach ([5 => 5, 15 => 15, 20 => 20, 0 => 10, 999 => 10] as $input => $expected) {
            $view = (new GaleriController)->index(Request::create('/', 'GET', ['per_page' => $input]));
            $this->assertSame($expected, $view->getData()['galeris']->perPage());
        }
    }
}
