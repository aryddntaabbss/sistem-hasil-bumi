<?php

namespace App\Filament\Widgets;

use App\Models\Petani;
use App\Models\Produksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetaniStatsOverview extends BaseWidget
{
    public static function canView(): bool
{
    return Auth::check() && Auth::user()->role === 'petani';
}

    protected function getStats(): array
{
    $petani = Petani::where('user_id', Auth::id())->first();

    $totalProduksi   = 0;
    $totalPendapatan = 0;
    $totalKeuntungan = 0;

    if ($petani) {
        $totalProduksi   = Produksi::where('petani_id', $petani->id)->count();
        $totalPendapatan = Produksi::where('petani_id', $petani->id)
                            ->sum(DB::raw('hasil_panen_kg * harga_per_kg'));
        $totalKeuntungan = Produksi::where('petani_id', $petani->id)
                            ->sum(DB::raw('(hasil_panen_kg * harga_per_kg) - biaya_produksi'));
    }

    return [
        Stat::make('Total Produksi Saya', $totalProduksi)
            ->description('Data produksi yang dicatat')
            ->descriptionIcon('heroicon-o-clipboard-document-list')
            ->color('info'),

        Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
            ->description('Akumulasi pendapatan saya')
            ->descriptionIcon('heroicon-o-banknotes')
            ->color('secondary'),

        Stat::make('Total Keuntungan', 'Rp ' . number_format($totalKeuntungan, 0, ',', '.'))
            ->description('Akumulasi keuntungan saya')
            ->descriptionIcon('heroicon-o-arrow-trending-up')
            ->color('success'),
    ];
}
}