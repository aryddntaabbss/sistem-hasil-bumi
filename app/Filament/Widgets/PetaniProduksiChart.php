<?php

namespace App\Filament\Widgets;

use App\Models\Petani;
use App\Models\Produksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PetaniProduksiChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Produksi Saya per Bulan';
    protected static ?int $sort = 3;

    public ?string $filter = null;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'petani';
    }

    protected function getFilters(): ?array
    {
        $petani = Petani::where('user_id', Auth::id())->first();

        if (!$petani) return [];

        $tahuns = Produksi::where('petani_id', $petani->id)
            ->selectRaw('YEAR(tanggal_panen) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->toArray();

        $options = [];
        foreach ($tahuns as $tahun) {
            $options[(string) $tahun] = (string) $tahun;
        }

        if (is_null($this->filter) && !empty($tahuns)) {
            $this->filter = (string) $tahuns[0];
        }

        return $options;
    }

    protected function getData(): array
    {
        $petani = Petani::where('user_id', Auth::id())->first();
        $tahun  = $this->filter ?? now()->year;

        if (!$petani) {
            return [
                'datasets' => [['label' => 'Tidak ada data', 'data' => array_fill(0, 12, 0)]],
                'labels'   => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            ];
        }

        $data = Produksi::where('petani_id', $petani->id)
            ->selectRaw('MONTH(tanggal_panen) as bulan, SUM(hasil_panen_kg) as total')
            ->whereYear('tanggal_panen', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $values[] = (float) ($data[$i] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Hasil Panen Saya (Kg) - ' . $tahun,
                    'data'            => $values,
                    'backgroundColor' => '#2563eb',
                    'borderColor'     => '#2563eb',
                    'borderWidth'     => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}