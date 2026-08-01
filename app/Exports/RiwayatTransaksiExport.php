<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RiwayatTransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private Collection $transaksis)
    {
    }

    public function collection(): Collection
    {
        return $this->transaksis;
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal Bayar',
            'Nama Santri',
            'NISN',
            'Item Dibayar',
            'Nominal',
            'Sumber',
            'Metode',
            'Status',
            'Dicatat Oleh',
        ];
    }

    public function map($transaksi): array
    {
        $items = $transaksi->details
            ->map(fn ($d) => $d->tagihanSantriDetail->nama_pembayaran ?? '-')
            ->implode(', ');

        return [
            $transaksi->kode_transaksi ?? $transaksi->order_id ?? '-',
            optional($transaksi->tanggal_bayar)->format('d-m-Y H:i') ?? '-',
            $transaksi->pendaftaran->nama_lengkap ?? '-',
            $transaksi->pendaftaran->pendidikan->nisn ?? '-',
            $items,
            $transaksi->nominal,
            $transaksi->sumber_pembayaran === 'manual' ? 'Manual' : 'Midtrans',
            ucfirst(str_replace('_', ' ', $transaksi->payment_type ?? '-')),
            ucfirst($transaksi->status),
            $transaksi->dicatatOlehAdmin->nama_lengkap ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}