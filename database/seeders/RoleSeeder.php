<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permission
        $permissions = [
            'kelola petani',
            'kelola komoditas',
            'input produksi',
            'lihat produksi',
            'lihat laporan',
            'lihat dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Role Admin → semua permission
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // Buat Role Petani → permission terbatas
        $petani = Role::firstOrCreate(['name' => 'petani']);
        $petani->syncPermissions([
            'input produksi',
            'lihat produksi',
            'lihat dashboard',
        ]);

        // Buat User Admin
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $userAdmin->assignRole('admin');

        // Buat User Petani (contoh)
        $userPetani = User::firstOrCreate(
            ['email' => 'petani@gmail.com'],
            [
                'name'     => 'Petani Demo',
                'password' => Hash::make('password'),
            ]
        );
        $userPetani->assignRole('petani');
    }
}