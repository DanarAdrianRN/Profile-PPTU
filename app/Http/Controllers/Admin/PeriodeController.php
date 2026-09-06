<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeriodeController extends Controller
{
    use LogsAdminActivity;

    public function index()
    {
        $periodes = Periode::withCount('pendaftarans')
            ->latest()
            ->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.periode',
            compact('periodes')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $periode = DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            if ($isActive) {
                Periode::query()->update(['is_active' => false]);
            }

            return Periode::create([
                'nama_periode' => $validated['nama_periode'],
                'is_active' => $isActive || Periode::count() === 0,
            ]);
        });

        if ($periode->is_active) {
            session()->forget('viewing_periode_id');
        }

        $this->catatAktivitas('create', 'Menambahkan periode: ' . $periode->nama_periode, $periode);

        return back()->with(
            'success',
            'Periode berhasil ditambahkan'
        );
    }

    public function update(Request $request, Periode $periode)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $statusAktifBerubah = false;

        DB::transaction(function () use ($validated, $periode, &$statusAktifBerubah) {
            $isActive = (bool) ($validated['is_active'] ?? false);
            $statusAktifBerubah = $isActive !== $periode->is_active;

            if (! $isActive && $periode->is_active) {
                $hasOtherActive = Periode::where('id', '!=', $periode->id)
                    ->where('is_active', true)
                    ->exists();

                if (! $hasOtherActive) {
                    throw ValidationException::withMessages([
                        'periode' => 'Minimal harus ada satu periode aktif.',
                    ]);
                }
            }

            if ($isActive) {
                Periode::where('id', '!=', $periode->id)
                    ->update(['is_active' => false]);
            }

            $periode->update([
                'nama_periode' => $validated['nama_periode'],
                'is_active' => $isActive,
            ]);
        });

        // Mengaktifkan periode itu aksi penting (ikut mengubah tampilan
        // landing page), jadi dicatat lebih spesifik daripada update biasa.
        if ($statusAktifBerubah && $periode->is_active) {
            session()->forget('viewing_periode_id');
            $this->catatAktivitas('update', 'Mengaktifkan periode: ' . $periode->nama_periode, $periode);
        } else {
            $this->catatAktivitas('update', 'Memperbarui periode: ' . $periode->nama_periode, $periode);
        }

        return back()->with(
            'success',
            'Periode berhasil diperbarui'
        );
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_active) {
            return back()->withErrors([
                'periode' => 'Periode aktif tidak bisa dihapus.',
            ]);
        }

        foreach (['gelombang_pendaftarans', 'jadwal_pendaftarans', 'pembayarans', 'promos'] as $table) {
            if (DB::table($table)->where('periode_id', $periode->id)->exists()) {
                return back()->withErrors(['periode' => 'Periode masih memiliki data informasi pendaftaran. Simpan periode ini sebagai arsip.']);
            }
        }

        $namaPeriode = $periode->nama_periode;

        $periode->delete();

        $this->catatAktivitas('delete', 'Menghapus periode: ' . $namaPeriode);

        return back()->with(
            'success',
            'Periode berhasil dihapus'
        );
    }

    /**
     * Tampilkan data periode tertentu (termasuk arsip) di menu-menu terkait,
     * tanpa mengubah periode mana yang aktif di landing page.
     */
    public function lihatData(Periode $periode)
    {
        session(['viewing_periode_id' => $periode->id]);

        return redirect()
            ->route('admin-pendaftaran')
            ->with('success', "Menampilkan data periode: {$periode->nama_periode}");
    }

    /**
     * Keluar dari mode lihat arsip, kembali ke periode yang sedang aktif.
     */
    public function keluarArsip()
    {
        session()->forget('viewing_periode_id');

        return redirect()
            ->route('admin-pendaftaran')
            ->with('success', 'Kembali menampilkan periode aktif.');
    }
}