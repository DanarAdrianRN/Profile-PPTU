<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use App\Models\Pembayaran;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with([
            'gelombangPendaftaran',
            'pembayarans',
        ])
            ->latest()
            ->get();

        $promoGelombangs = GelombangPendaftaran::whereDate(
            'tanggal_mulai',
            '>',
            now()
        )
            ->orderBy('urutan')
            ->get();

        $promoPembayarans = Pembayaran::where('is_active', true)
            ->orderBy('jenjang')
            ->orderBy('kategori')
            ->orderBy('nama_pembayaran')
            ->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.promo',
            compact('promos', 'promoGelombangs', 'promoPembayarans')
        );
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $promo = Promo::create($this->payload($validated));

        $promo->pembayarans()->sync(
            $this->pembayaranIds($validated)
        );

        return back()->with(
            'success',
            'Promo berhasil ditambahkan'
        );
    }

    public function update(Request $request, Promo $promo)
    {
        $validated = $this->validatedData($request);

        $promo->update($this->payload($validated));

        $promo->pembayarans()->sync(
            $this->pembayaranIds($validated)
        );

        return back()->with(
            'success',
            'Promo berhasil diperbarui'
        );
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return back()->with(
            'success',
            'Promo berhasil dihapus'
        );
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'cakupan_gelombang' => 'required|in:semua,satu',
            'gelombang_pendaftaran_id' => 'nullable|required_if:cakupan_gelombang,satu|exists:gelombang_pendaftarans,id',
            'jenjang' => 'nullable|in:SMP,SMK',
            'cakupan_biaya' => 'required|in:semua,satu',
            'pembayaran_ids' => 'nullable|array|required_if:cakupan_biaya,satu',
            'pembayaran_ids.*' => 'exists:pembayarans,id',
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

        return Pembayaran::where('is_active', true)
            ->when($data['jenjang'] ?? null, function ($query, $jenjang) {
                $query->where('jenjang', $jenjang);
            })
            ->pluck('id')
            ->all();
    }
}
