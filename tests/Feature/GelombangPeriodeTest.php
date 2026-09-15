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

    public function test_period_can_be_saved_and_changed_but_not_cleared(): void
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
        $this->expectException(ValidationException::class);
        $controller->update(Request::create('/', 'POST', $data), $wave->id);
    }

    public function test_reversed_dates_are_rejected_when_creating_and_editing(): void
    {
        $period = Periode::create(['nama_periode' => '2026/2027', 'is_active' => true]);
        $wave = GelombangPendaftaran::create([
            'nama_gelombang' => 'Gelombang 1', 'periode_id' => $period->id,
            'tanggal_mulai' => '2026-09-10', 'tanggal_selesai' => '2026-09-15',
            'urutan' => 1, 'is_publish' => true,
        ]);
        $controller = new GelombangPendaftaranController;
        $data = [
            'nama_gelombang' => 'Gelombang Terbalik', 'periode_id' => $period->id,
            'tanggal_mulai' => '2026-09-10', 'tanggal_selesai' => '2026-09-09',
        ];

        foreach (['store', 'update'] as $method) {
            try {
                $request = Request::create('/', 'POST', $data);
                $method === 'store' ? $controller->store($request) : $controller->update($request, $wave->id);
                $this->fail('Tanggal terbalik harus ditolak.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('tanggal_selesai', $exception->errors());
                $this->assertSame(
                    'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
                    $exception->errors()['tanggal_selesai'][0]
                );
            }
            $this->assertDatabaseCount('gelombang_pendaftarans', 1);
            $this->assertSame('2026-09-15', $wave->fresh()->tanggal_selesai->format('Y-m-d'));
            $this->assertSame('Gelombang 1', $wave->fresh()->nama_gelombang);
        }

        $data['tanggal_selesai'] = $data['tanggal_mulai'];
        $controller->update(Request::create('/', 'POST', $data), $wave->id);
        $this->assertSame('2026-09-10', $wave->fresh()->tanggal_selesai->format('Y-m-d'));
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
