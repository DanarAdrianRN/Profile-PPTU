<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\FilterByPeriode;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class MasterPembayaranController extends Controller
{
    use FilterByPeriode;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedPeriode = $this->resolvePeriode();
        $selectedPeriodeId = $selectedPeriode?->id;
        $isArsip = $selectedPeriode && ! $selectedPeriode->is_active;

        $pembayarans = Pembayaran::untukPeriode($selectedPeriodeId)->latest()->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.pembayaran',
            compact('pembayarans', 'selectedPeriode', 'selectedPeriodeId', 'isArsip')
        );
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([

            'jenjang' => 'required',
            'kategori' => 'required',
            'nama_pembayaran' => 'required',
            'nominal' => 'required',
        ]);

        $periodeId = $request->input('periode_id', $this->resolvePeriode()?->id);
        validator(['periode_id' => $periodeId], ['periode_id' => 'required|exists:periodes,id'])->validate();

        Pembayaran::create([
            'periode_id' => $periodeId,

            'jenjang' => $request->jenjang,
            'kategori' => $request->kategori,
            'nama_pembayaran' => $request->nama_pembayaran,
            'nominal' => preg_replace(
                '/[^0-9]/',
                '',
                $request->nominal
            ),

            'is_active' => true,
        ]);

        return redirect()->back()
            ->with(
                'success',
                'Data pembayaran berhasil ditambahkan'
            );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([

            'jenjang' => 'required',

            'kategori' => 'required',

            'nama_pembayaran' => 'required',

            'nominal' => 'required',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([

            'jenjang' => $request->jenjang,

            'kategori' => $request->kategori,

            'nama_pembayaran' => $request->nama_pembayaran,

            'nominal' => preg_replace(
                '/[^0-9]/',
                '',
                $request->nominal
            ),
        ]);

        return redirect()->back()
            ->with(
                'success',
                'Data pembayaran berhasil diupdate'
            );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->delete();

        return redirect()->back()
            ->with(
                'success',
                'Data pembayaran berhasil dihapus'
            );
    }
}
