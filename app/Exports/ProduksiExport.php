<?php

namespace App\Exports;

use App\Models\Produksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProduksiExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(
        protected ?string $petani_id = null,
        protected ?string $komoditas_id = null,
        protected ?string $bulan = null,
        protected ?string $tahun = null,
    ) {}

    public function query()
    {
        return Produksi::with(['petani', 'komoditas'])
            ->when($this->petani_id, fn($q) => $q->where('petani_id', $this->petani_id))
            ->when($this->komoditas_id, fn($q) => $q->where('komoditas_id', $this->komoditas_id))
            ->when($this->bulan, fn($q) => $q->whereMonth('tanggal_panen', $this->bulan))
            ->when($this->tahun, fn($q) => $q->whereYear('tanggal_panen', $this->tahun));
    }

    public function headings(): array
    {
        return [
            'No',
            'Petani',
            'Komoditas',
            'Tanggal Panen',
            'Hasil Panen (Kg)',
            'Harga/Kg (Rp)',
            'Biaya Produksi (Rp)',
            'Pendapatan (Rp)',
            'Keuntungan (Rp)',
            'Catatan',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->petani->nama,
            $row->komoditas->nama_komoditas,
            $row->tanggal_panen,
            $row->hasil_panen_kg,
            $row->harga_per_kg,
            $row->biaya_produksi,
            $row->hasil_panen_kg * $row->harga_per_kg,
            ($row->hasil_panen_kg * $row->harga_per_kg) - $row->biaya_produksi,
            $row->catatan,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '16a34a']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}