<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeriodeController extends Controller
{
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

        DB::transaction(function () use ($validated) {
            $isActive = (bool) ($validated['is_active'] ?? false);

            if ($isActive) {
                Periode::query()->update(['is_active' => false]);
            }

            Periode::create([
                'nama_periode' => $validated['nama_periode'],
                'is_active' => $isActive || Periode::count() === 0,
            ]);
        });

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

        DB::transaction(function () use ($validated, $periode) {
            $isActive = (bool) ($validated['is_active'] ?? false);

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

        $periode->delete();

        return back()->with(
            'success',
            'Periode berhasil dihapus'
        );
    }
}
