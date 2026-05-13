<?php

namespace App\Filament\Widgets;

use App\Models\Produksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class KomoditasUnggulanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Komoditas Unggulan';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    protected function getData(): array
{
    $data = Produksi::with('komoditas')
        ->selectRaw('komoditas_id, SUM(hasil_panen_kg) as total_kg')
        ->groupBy('komoditas_id')
        ->orderByDesc('total_kg')
        ->get();

    $colors = ['#16a34a', '#2563eb', '#d97706'];

    $datasets = [];
    foreach ($data as $i => $row) {
        $datasets[] = [
            'label'           => $row->komoditas?->nama_komoditas ?? 'Unknown',
            'data'            => [$row->total_kg],
            'backgroundColor' => $colors[$i] ?? '#6b7280',
            'borderWidth'     => 1,
        ];
    }

    return [
        'datasets' => $datasets,
        'labels'   => ['Total Hasil Panen (Kg)'],
    ];
}

protected function getType(): string
{
    return 'bar';
}
}