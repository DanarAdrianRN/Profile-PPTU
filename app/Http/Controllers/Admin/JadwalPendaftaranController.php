<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPendaftaran;
use App\Models\Periode;
use Illuminate\Http\Request;

class JadwalPendaftaranController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPendaftaran::with('periode')
            ->orderBy('urutan')
            ->orderBy('tanggal')
            ->get();

        $periodes = Periode::latest()->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.jadwal',
            compact('jadwals', 'periodes')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_id' => 'nullable|exists:periodes,id',
            'nama_jadwal' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'urutan' => 'nullable|integer|min:1',
            'is_publish' => 'nullable|boolean',
        ]);

        JadwalPendaftaran::create([
            'periode_id' => $validated['periode_id'] ?? null,
            'nama_jadwal' => $validated['nama_jadwal'],
            'tanggal' => $validated['tanggal'],
            'urutan' => $validated['urutan'] ?? 1,
            'is_publish' => $request->boolean('is_publish', true),
        ]);

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil ditambahkan'
        );
    }

    public function update(Request $request, JadwalPendaftaran $jadwal)
    {
        $validated = $request->validate([
            'periode_id' => 'nullable|exists:periodes,id',
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

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil diperbarui'
        );
    }

    public function destroy(JadwalPendaftaran $jadwal)
    {
        $jadwal->delete();

        return back()->with(
            'success',
            'Jadwal pendaftaran berhasil dihapus'
        );
    }
}
