<?php

namespace App\Filament\Resources\PetaniResource\Pages;

use App\Filament\Resources\PetaniResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreatePetani extends CreateRecord
{
    protected static string $resource = PetaniResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buat user baru
        $user = User::create([
            'name'     => $data['nama'],
            'email'    => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
            'role'     => 'petani',
        ]);

        // Simpan user_id ke data petani
        $data['user_id'] = $user->id;

        // Hapus key user agar tidak masuk ke tabel petani
        unset($data['user']);

        return $data;
    }
}