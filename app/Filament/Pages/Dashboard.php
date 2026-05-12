<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $slug            = 'dashboard';

    public function getTitle(): string
    {
        return 'Dashboard SIPDHB Kecamatan Gane Barat';
    }
}