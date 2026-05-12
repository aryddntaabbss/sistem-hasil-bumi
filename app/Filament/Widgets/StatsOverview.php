<?php

namespace App\Filament\Widgets;

use App\Models\Petani;
use App\Models\Komoditas;
use App\Models\Produksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
{
    return Auth::check() && Auth::user()->role === 'admin';
}

    protected function getStats(): array
    {
        $totalPendapatan = Produksi::sum('pendapatan');
        $totalKeuntungan = Produksi::sum('keuntungan');

        return [
            Stat::make('Total Petani', Petani::count())
                ->description('Jumlah petani terdaftar')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Komoditas', Komoditas::count())
                ->description('Jenis komoditas tersedia')
                ->descriptionIcon('heroicon-o-tag')
                ->color('warning'),

            Stat::make('Total Produksi', Produksi::count())
                ->description('Data produksi tercatat')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('info'),

            Stat::make('Total Laporan', Produksi::count())
                ->description('Laporan panen tersedia')
                ->descriptionIcon('heroicon-o-document-chart-bar')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Akumulasi seluruh pendapatan')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Keuntungan', 'Rp ' . number_format($totalKeuntungan, 0, ',', '.'))
                ->description('Akumulasi seluruh keuntungan')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
        ];
    }
}