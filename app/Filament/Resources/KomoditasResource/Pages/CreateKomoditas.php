<?php

namespace App\Filament\Resources\KomoditasResource\Pages;

use App\Filament\Resources\KomoditasResource;
use Filament\Actions;
use App\Filament\Traits\HasIndonesianFormActions;
use Filament\Resources\Pages\CreateRecord;

class CreateKomoditas extends CreateRecord
{
    use HasIndonesianFormActions;

    protected static string $resource = KomoditasResource::class;
}
