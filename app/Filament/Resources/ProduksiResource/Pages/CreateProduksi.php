<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Filament\Resources\ProduksiResource;
use Filament\Actions;
use App\Filament\Traits\HasIndonesianFormActions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduksi extends CreateRecord
{
    use HasIndonesianFormActions;
    protected static string $resource = ProduksiResource::class;
}
