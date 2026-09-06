<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\JadwalPendaftaran;
use App\Models\Periode;
use Illuminate\Http\Request;

class JadwalPendaftaranController extends Controller
{
    use FilterByPeriode;

    use LogsAdminActivity;

    public function index()
    {
        $selectedPeriode = $this->resolvePeriode();
        $selectedPeriodeId = $selectedPeriode?->id;
        $isArsip = $selectedPeriode && ! $selectedPeriode->is_active;

        $jadwals = JadwalPendaftaran::untukPeriode($selectedPeriodeId)->with('periode')
            ->orderBy('urutan')
            ->orderBy('tanggal')
            ->get();

        $periodes = Periode::latest()->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.jadwal',
            compact('jadwals', 'periodes', 'selectedPeriode', 'selectedPeriodeId', 'isArsip')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'nama_jadwal' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'urutan' => 'nullable|integer|min:1',
            'is_publish' => 'nullable|boolean',
        ]);

        $jadwal = JadwalPendaftaran::create([
            'periode_id' => $validated['periode_id'] ?? null,
            'nama_jadwal' => $validated['nama_jadwal'],
            'tanggal' => $validated['tanggal'],
            'urutan' => $validated['urutan'] ?? 1,
            'is_publish' => $request->boolean('is_publish', true),
        ]);

        $this->catatAktivitas('create', 'Menambahkan jadwal pendaftaran: ' . $jadwal->nama_jadwal, $jadwal);

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil ditambahkan'
        );
    }

    public function update(Request $request, JadwalPendaftaran $jadwal)
    {
        $validated = $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'nama_jadwal' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'urutan' => 'nullable|integer|min:1',
            'is_publish' => 'nullable|boolean',
        ]);

        $jadwal->update([
            'periode_id' => $validated['periode_id'] ?? null,
            'nama_jadwal' => $validated['nama_jadwal'],
            'tanggal' => $validated['tanggal'],
            'urutan' => $validated['urutan'] ?? 1,
            'is_publish' => $request->boolean('is_publish'),
        ]);

        $this->catatAktivitas('update', 'Memperbarui jadwal pendaftaran: ' . $jadwal->nama_jadwal, $jadwal);

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil diperbarui'
        );
    }

    public function destroy(JadwalPendaftaran $jadwal)
    {
        $namaJadwal = $jadwal->nama_jadwal;

        $jadwal->delete();

        $this->catatAktivitas('delete', 'Menghapus jadwal pendaftaran: ' . $namaJadwal);

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil dihapus'
        );
    }
}