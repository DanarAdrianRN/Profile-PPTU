<?php

namespace App\Console\Commands;

use App\Models\Pendaftaran;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BersihkanPendaftaranBelumBayar extends Command
{
    protected $signature = 'pendaftaran:bersihkan-belum-bayar {--hari=3 : Batas hari sejak pendaftaran sebelum dihapus}';

    protected $description = 'Hapus pendaftaran yang statusnya masih belum_bayar dan sudah melewati batas waktu, termasuk berkas dan transaksi terkait.';

    public function handle(): int
    {
        $batasHari = (int) $this->option('hari');

        $pendaftarans = Pendaftaran::where('status', 'belum_bayar')
            ->where('created_at', '<=', now()->subDays($batasHari))
            ->with('dokumens')
            ->get();

        if ($pendaftarans->isEmpty()) {
            $this->info('Tidak ada pendaftaran belum bayar yang perlu dibersihkan.');
            return self::SUCCESS;
        }

        foreach ($pendaftarans as $pendaftaran) {
            // Hapus berkas fisik yang sudah diupload supaya tidak jadi sampah di storage.
            foreach ($pendaftaran->dokumens as $dokumen) {
                if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
                    Storage::disk('public')->delete($dokumen->file);
                }
            }

            // Transaksi tidak punya cascade delete ke pendaftarans, jadi dihapus manual
            // (transaksi_details ikut terhapus lewat cascade dari transaksis).
            Transaksi::where('pendaftaran_id', $pendaftaran->id)->delete();

            // pendidikan, orang_tuas, dokumens, tagihan_santris (& detailnya) otomatis
            // ikut terhapus lewat cascade delete di database.
            $pendaftaran->delete();

            $this->line("Dihapus: {$pendaftaran->nama_lengkap} ({$pendaftaran->kode_pendaftaran})");
        }

        $this->info("Selesai. {$pendaftarans->count()} pendaftaran belum bayar dibersihkan.");

        return self::SUCCESS;
    }
}