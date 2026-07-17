<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GelombangPendaftaran;
use Illuminate\Http\Request;

class GelombangPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gelombangs = GelombangPendaftaran::with('promos')
            ->withCount('pendaftarans')
            ->orderBy('urutan')
            ->latest()
            ->get();

        return view(
            'pages.admin.administrasi.informasi-pendaftaran.gelombang',
            compact('gelombangs')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|max:255',

            'tanggal_mulai' => 'required|date',

            'tanggal_selesai' =>
                'required|date|after_or_equal:tanggal_mulai',

            'urutan' => 'nullable|integer',

            'is_publish' => 'nullable|boolean',

        ]);

        $gelombang = GelombangPendaftaran::create([
            'nama_gelombang' => $request->nama_gelombang,

            'tanggal_mulai' => $request->tanggal_mulai,

            'tanggal_selesai' => $request->tanggal_selesai,

            'urutan' => $request->urutan ?? 1,

            'is_publish' => $request->boolean('is_publish', true),
        ]);

        return back()->with(
            'success',
            'Gelombang berhasil ditambahkan'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gelombang = GelombangPendaftaran::findOrFail($id);

        $request->validate([
            'nama_gelombang' => 'required|max:255',

            'tanggal_mulai' => 'required|date',

            'tanggal_selesai' =>
                'required|date|after_or_equal:tanggal_mulai',

            'urutan' => 'nullable|integer',

            'is_publish' => 'nullable|boolean',

        ]);

        $gelombang->update([
            'nama_gelombang' => $request->nama_gelombang,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'urutan' => $request->urutan ?? 1,
            'is_publish' => $request->boolean('is_publish'),
        ]);

        return back()->with(
            'success',
            'Gelombang berhasil diperbarui'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gelombang = GelombangPendaftaran::findOrFail($id);

        $gelombang->delete();

        return back()->with(
            'success',
            'Gelombang berhasil dihapus'
        );
    }

}
