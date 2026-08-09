<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\AdminActivityLog;

trait LogsAdminActivity
{
    /**
     * Catat satu aktivitas admin. Dipanggil dari controller setelah aksi
     * (login, ubah status, catat bayar, tambah/ubah/hapus data, dll)
     * berhasil dilakukan.
     *
     * @param string $aksi Kode aksi: login, logout, create, update, delete,
     *                      status_ubah, catat_bayar, dll.
     * @param string $deskripsi Deskripsi singkat yang bisa dibaca manusia.
     * @param mixed $subjek Model terkait (opsional), disimpan sebagai
     *                      referensi polymorphic.
     * @param int|null $adminId Default: admin yang sedang login.
     */
    protected function catatAktivitas(
        string $aksi,
        string $deskripsi,
        mixed $subjek = null,
        ?int $adminId = null
    ): void {
        AdminActivityLog::create([
            'admin_id' => $adminId ?? session('admin.id'),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'subjek_type' => $subjek ? get_class($subjek) : null,
            'subjek_id' => $subjek?->id,
        ]);
    }
}