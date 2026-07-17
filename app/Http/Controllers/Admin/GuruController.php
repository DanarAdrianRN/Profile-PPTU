<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    private const KATEGORI_GURU = [
        'Madrasah Diniyah',
        'SMP',
        'SMK',
        "Madrasah Al-Qur'an",
        'TPQ',
    ];

    public function index()
    {
        $gurus = Guru::latest()->get();

        $totalPengajar = Guru::count();
        $guruSMP = Guru::where('kategori', 'SMP')->count();
        $guruSMK = Guru::where('kategori', 'SMK')->count();
        $ustadzMadin = Guru::where('kategori', 'Madrasah Diniyah')->count();
        $ustadzMadqur = Guru::where('kategori', "Madrasah Al-Qur'an")->count();
        $ustadzTPQ = Guru::where('kategori', 'TPQ')->count();
        $kategoriGuru = self::KATEGORI_GURU;

        return view('pages.admin.media.guru', compact(
            'gurus',
            'totalPengajar',
            'guruSMP',
            'guruSMK',
            'ustadzMadin',
            'ustadzMadqur',
            'ustadzTPQ',
            'kategoriGuru'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:7680',
            'nama_lengkap' => 'required',
            'kategori' => 'required|in:' . implode(',', self::KATEGORI_GURU),
            'mapel_bidang' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'alamat' => 'required',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('guru', 'public');
        }

        Guru::create($validated);

        return back()->with('success', 'Data guru berhasil ditambahkan');
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:7680',
            'nama_lengkap' => 'required',
            'kategori' => 'required|in:' . implode(',', self::KATEGORI_GURU),
            'mapel_bidang' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'alamat' => 'required',
        ]);

        // UPDATE FOTO
        if ($request->hasFile('foto')) {

            // HAPUS FOTO LAMA
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {

                Storage::disk('public')->delete($guru->foto);

            }

            // SIMPAN FOTO BARU
            $validated['foto'] =
                $request->file('foto')
                ->store('guru', 'public');
        }

        $guru->update($validated);

        return back()->with(
            'success',
            'Data guru berhasil diperbarui'
        );
    }

    public function destroy(Guru $guru)
    {
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return back()->with('success', 'Data guru berhasil dihapus');
    }
}
