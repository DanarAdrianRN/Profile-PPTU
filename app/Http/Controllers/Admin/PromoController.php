<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use App\Http\Controllers\Admin\Concerns\LogsAdminActivity;
use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
    use FilterByPeriode;

    use LogsAdminActivity;

    public function index()
    {
        $selectedPeriode = $this->resolvePeriode();
        $selectedPeriodeId = $selectedPeriode?->id;
        $isArsip = $selectedPeriode && ! $selectedPeriode->is_active;

        $promos = Promo::untukPeriode($selectedPeriodeId)->with([
            'gelombangPendaftaran',
            'pembayarans',
        ])
            ->latest()
            ->get();

        $promoGelombangs = GelombangPendaftaran::untukPeriode($selectedPeriodeId)->whereDate(
            'tanggal_mulai',
            '>',
            now()
        )
            ->orderBy('urutan')
            ->get();

        $promoPembayarans = Pembayaran::untukPeriode($selectedPeriodeId)->where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('kategori')
            ->orderBy('nama_pembayaran')
            ->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.promo',
            compact('promos', 'promoGelombangs', 'promoPembayarans', 'selectedPeriode', 'selectedPeriodeId', 'isArsip')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $promo = Promo::create($this->payload($validated));

        $promo->pembayarans()->sync(
            $this->pembayaranIds($validated)
        );

        $this->catatAktivitas('create', 'Menambahkan promo: ' . $promo->nama_promo, $promo);

        return back()->with(
            'success',
            'Promo berhasil ditambahkan'
        );
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $this->validatedData($request, $promo);

        $promo->update($this->payload($validated));

        $promo->pembayarans()->sync(
            $this->pembayaranIds($validated)
        );

        $this->catatAktivitas('update', 'Memperbarui promo: ' . $promo->nama_promo, $promo);

        return back()->with(
            'success',
            'Promo berhasil diperbarui'
        );
    }

    public function destroy(Promo $promo)
    {
        $namaPromo = $promo->nama_promo;

        $promo->delete();

        $this->catatAktivitas('delete', 'Menghapus promo: ' . $namaPromo);

        return back()->with(
            'success',
            'Promo berhasil dihapus'
        );
    }

    private function validatedData(Request $request, ?Promo $promo = null): array
    {
        $periodeId = $promo?->periode_id ?? $request->input('periode_id', $this->resolvePeriode()?->id);
        $request->merge(['periode_id' => $periodeId]);

        return $request->validate([
            'periode_id' => 'required|exists:periodes,id',
            'cakupan_gelombang' => 'required|in:semua,satu',
            'gelombang_pendaftaran_id' => ['nullable', 'required_if:cakupan_gelombang,satu', Rule::exists('gelombang_pendaftarans', 'id')->where('periode_id', $periodeId)],
            'jenjang' => 'nullable|in:SMP,SMK',
            'cakupan_biaya' => 'required|in:semua,satu',
            'pembayaran_ids' => 'nullable|array|required_if:cakupan_biaya,satu',
            'pembayaran_ids.*' => [Rule::exists('pembayarans', 'id')->where('periode_id', $periodeId)],
            'tipe' => 'required|in:nominal,persentase,gratis_biaya',
            'nilai' => 'nullable|required_unless:tipe,gratis_biaya|integer|min:0',
            'kuota' => 'nullable|integer|min:1',
            'nama_promo' => 'required|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
    }

    private function payload(array $data): array
    {
        $gelombang = null;

        if ($data['cakupan_gelombang'] === 'satu') {
            $gelombang = GelombangPendaftaran::find(
                $data['gelombang_pendaftaran_id']
            );
        }

        return [
            'periode_id' => $data['periode_id'],
            'gelombang_pendaftaran_id' => $gelombang?->id,
            'nama_promo' => $data['nama_promo'],
            'tipe' => $data['tipe'],
            'nilai' => $data['tipe'] === 'gratis_biaya'
                ? 0
                : ($data['nilai'] ?? 0),
            'cakupan' => $data['cakupan_gelombang'] === 'semua'
                ? 'semua'
                : 'gelombang',
            'jenjang' => $data['jenjang'] ?? null,
            'tanggal_mulai' => $gelombang?->tanggal_mulai,
            'tanggal_selesai' => $gelombang?->tanggal_selesai,
            'kuota' => $data['kuota'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];
    }

    private function pembayaranIds(array $data): array
    {
        if ($data['cakupan_biaya'] === 'satu') {
            return $data['pembayaran_ids'] ?? [];
        }

        return Pembayaran::untukPeriode($data['periode_id'])->where('is_active', true)
            ->when($data['jenjang'] ?? null, function ($query, $jenjang) {
                $query->where('jenjang', $jenjang);
            })
            ->pluck('id')
            ->all();
    }
}