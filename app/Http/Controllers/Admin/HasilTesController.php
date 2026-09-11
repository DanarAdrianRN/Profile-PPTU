<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use App\Models\Pendaftaran;
use App\Models\PendaftaranHasilTes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HasilTesController extends Controller
{
    use FilterByPeriode;

    public function index()
    {
        $periode = $this->resolvePeriode();
        $selectedPeriodeId = $periode?->id;
        $isArsip = $periode && ! $periode->is_active;

        $hasilTes = PendaftaranHasilTes::with([
            'pendaftaran.pendidikan',
        ])
            ->when($selectedPeriodeId, function ($query) use ($selectedPeriodeId) {
                $query->whereHas('pendaftaran', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_id', $selectedPeriodeId);
                });
            })
            ->latest()
            ->get();

        $pendaftarans = Pendaftaran::with('pendidikan')
            ->where('status', 'diterima')
            ->whereDoesntHave('hasilTes')
            ->when($selectedPeriodeId, function ($query) use ($selectedPeriodeId) {
                $query->where('periode_id', $selectedPeriodeId);
            })
            ->latest()
            ->get();

        return view('pages.admin.administrasi.hasil-tes', [
            'hasilTes' => $hasilTes,
            'hasilTesModal' => $hasilTes,
            'pendaftarans' => $pendaftarans,
            'periode' => $periode,
            'isArsip' => $isArsip,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        PendaftaranHasilTes::create($validated);

        return back()->with('success', 'Hasil tes berhasil ditambahkan');
    }

    public function update(Request $request, PendaftaranHasilTes $hasilTes)
    {
        $validated = $this->validatedData(
            $request,
            $hasilTes->id
        );

        $hasilTes->update($validated);

        return back()->with('success', 'Hasil tes berhasil diperbarui');
    }

    public function destroy(PendaftaranHasilTes $hasilTes)
    {
        $hasilTes->delete();

        return back()->with('success', 'Hasil tes berhasil dihapus');
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:pendaftaran_hasil_tes,pendaftaran_id';

        if ($ignoreId) {
            $uniqueRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'pendaftaran_id' => [
                'required',
                Rule::exists('pendaftarans', 'id')->where('status', 'diterima'),
                $uniqueRule,
            ],
            'baca_tulis_pegon' => 'nullable|integer|min:0|max:100',
            'doa_harian' => 'nullable|integer|min:0|max:100',
            'ubudiyyah' => 'nullable|integer|min:0|max:100',
            'membaca_al_quran' => 'nullable|integer|min:0|max:100',
            'hafalan_surat_pendek' => 'nullable|integer|min:0|max:100',
            'wawancara' => 'nullable|string|max:100',
        ]);
    }
}