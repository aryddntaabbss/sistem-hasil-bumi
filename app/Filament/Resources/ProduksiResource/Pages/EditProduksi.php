<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Filament\Resources\ProduksiResource;
use Filament\Actions;
use App\Filament\Traits\HasIndonesianFormActions;
use Filament\Resources\Pages\EditRecord;

class EditProduksi extends EditRecord
{
    use HasIndonesianFormActions;

    protected static string $resource = ProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->label('Hapus'),
        ];
    }
}
