<?php

namespace App\Filament\Resources\PetaniResource\Pages;

use App\Filament\Resources\PetaniResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditPetani extends EditRecord
{
    protected static string $resource = PetaniResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load email dari relasi user ke form
        $data['user']['email'] = $this->record->user?->email;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Update data user
        if ($this->record->user) {
            $updateData = ['email' => $data['user']['email']];

            // Update password hanya kalau diisi
            if (!empty($data['user']['password'])) {
                $updateData['password'] = Hash::make($data['user']['password']);
            }

            // Update nama user sesuai nama petani
            $updateData['name'] = $data['nama'];

            $this->record->user->update($updateData);
        }

        unset($data['user']);

        return $data;
    }
}