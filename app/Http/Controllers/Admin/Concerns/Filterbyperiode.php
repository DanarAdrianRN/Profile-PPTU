<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Periode;

trait FilterByPeriode
{
    /**
     * Tentukan periode mana yang datanya sedang ditampilkan di admin.
     * Default: periode yang aktif (dipakai juga di landing page).
     * Kalau admin sedang "Lihat Data" arsip dari menu Periode, session
     * 'viewing_periode_id' dipakai duluan — ini TIDAK mengubah is_active,
     * jadi landing page tetap menampilkan periode aktif yang sebenarnya.
     */
    protected function resolvePeriode(): ?Periode
    {
        $viewingId = session('viewing_periode_id');

        if ($viewingId) {
            $periode = Periode::find($viewingId);

            if ($periode) {
                return $periode;
            }

            // Periode arsip yang tersimpan di session ternyata sudah dihapus.
            session()->forget('viewing_periode_id');
        }

        return Periode::aktif()->first();
    }
}