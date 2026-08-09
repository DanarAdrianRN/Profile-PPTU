<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    use LogsAdminActivity;

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

        $this->catatAktivitas('create', 'Menambahkan data guru: ' . $validated['nama_lengkap']);

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

        $this->catatAktivitas('update', 'Memperbarui data guru: ' . $guru->nama_lengkap, $guru);

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

        $namaGuru = $guru->nama_lengkap;

        $guru->delete();

        $this->catatAktivitas('delete', 'Menghapus data guru: ' . $namaGuru);

        return back()->with('success', 'Data guru berhasil dihapus');
    }
}