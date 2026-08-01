<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private int $nomor = 0;

    public function __construct(private Collection $tagihans)
    {
    }

    public function collection(): Collection
    {
        return $this->tagihans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'NISN',
            'Jenjang',
            'Total Tagihan',
            'Sisa Tagihan',
            'Status Pembayaran',
        ];
    }

    public function map($tagihan): array
    {
        $this->nomor++;

        return [
            $this->nomor,
            $tagihan->pendaftaran->nama_lengkap ?? '-',
            $tagihan->pendaftaran->pendidikan->nisn ?? '-',
            $tagihan->jenjang,
            $tagihan->nominal_akhir,
            $tagihan->sisa_tagihan,
            ucfirst(str_replace('_', ' ', $tagihan->status_pembayaran)),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}