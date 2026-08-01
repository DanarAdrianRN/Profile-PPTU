<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendaftaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private int $nomor = 0;

    public function __construct(private Collection $pendaftarans)
    {
    }

    public function collection(): Collection
    {
        return $this->pendaftarans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NISN',
            'Jenjang',
            'Jurusan',
            'Wali (Ayah)',
            'Status Pendaftaran',
            'Status Pembayaran',
            'Tanggal Daftar',
        ];
    }

    public function map($pendaftaran): array
    {
        $this->nomor++;

        $ayah = $pendaftaran->orangTuas->where('tipe', 'ayah')->first();

        $tagihan = $pendaftaran->tagihanSantri;
        $totalItem = $tagihan?->details?->count() ?? 0;
        $totalLunas = $tagihan?->details?->where('status_pembayaran', 'lunas')->count() ?? 0;
        $isLunas = $totalItem > 0 && $totalItem === $totalLunas;

        return [
            $this->nomor,
            $pendaftaran->nama_lengkap,
            $pendaftaran->pendidikan->nisn ?? '-',
            $pendaftaran->pendidikan->jenjang_pendidikan ?? '-',
            $pendaftaran->pendidikan->jurusan ?? '-',
            $ayah->nama ?? '-',
            ucfirst(str_replace('_', ' ', $pendaftaran->status)),
            $isLunas ? 'Lunas' : 'Belum Lunas',
            optional($pendaftaran->created_at)->format('d-m-Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}