<?php

namespace App\Filament\Widgets;

use App\Models\Produksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProduksiChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Produksi per Bulan';
    protected static ?int $sort = 2;

    public static function canView(): bool
{
    return Auth::check() && Auth::user()->role === 'admin';
}

    protected function getData(): array
    {
        $data = Produksi::selectRaw('MONTH(tanggal_panen) as bulan, SUM(hasil_panen_kg) as total')
            ->whereYear('tanggal_panen', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $values = [];

        for ($i = 1; $i <= 12; $i++) {
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Hasil Panen (Kg)',
                    'data'            => $values,
                    'backgroundColor' => '#16a34a',
                    'borderColor'     => '#16a34a',
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