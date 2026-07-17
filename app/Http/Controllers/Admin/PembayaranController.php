<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = Pembayaran::latest()->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.pembayaran',
            compact('pembayarans')
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

        Pembayaran::create([

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
