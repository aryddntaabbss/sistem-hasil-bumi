<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petani;
use App\Models\Komoditas;
use App\Models\Produksi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Produksi::truncate();
        Petani::truncate();
        Komoditas::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat User Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Data Petani + User sekaligus
        $petaniData = [
            ['nama' => 'Ahmad Basir',     'alamat' => 'Desa Gane Luar, Kec. Gane Barat',  'no_hp' => '081234567801', 'email' => 'ahmad@gmail.com'],
            ['nama' => 'Siti Rahmawati',  'alamat' => 'Desa Gane Dalam, Kec. Gane Barat', 'no_hp' => '081234567802', 'email' => 'siti@gmail.com'],
            ['nama' => 'Umar Djalil',     'alamat' => 'Desa Jikomalamo, Kec. Gane Barat', 'no_hp' => '081234567803', 'email' => 'umar@gmail.com'],
            ['nama' => 'Fatima Soleman',  'alamat' => 'Desa Gane Luar, Kec. Gane Barat',  'no_hp' => '081234567804', 'email' => 'fatima@gmail.com'],
            ['nama' => 'Ruslan Hamid',    'alamat' => 'Desa Gane Dalam, Kec. Gane Barat', 'no_hp' => '081234567805', 'email' => 'ruslan@gmail.com'],
            ['nama' => 'Halima Usman',    'alamat' => 'Desa Jikomalamo, Kec. Gane Barat', 'no_hp' => '081234567806', 'email' => 'halima@gmail.com'],
            ['nama' => 'Ibrahim Saleh',   'alamat' => 'Desa Gane Luar, Kec. Gane Barat',  'no_hp' => '081234567807', 'email' => 'ibrahim@gmail.com'],
            ['nama' => 'Nurhayati Malik', 'alamat' => 'Desa Gane Dalam, Kec. Gane Barat', 'no_hp' => '081234567808', 'email' => 'nurhayati@gmail.com'],
        ];

        foreach ($petaniData as $p) {
            // Buat user role petani
            $user = User::create([
                'name'     => $p['nama'],
                'email'    => $p['email'],
                'password' => Hash::make('password'),
                'role'     => 'petani',
            ]);

            // Buat petani terhubung ke user
            Petani::create([
                'user_id' => $user->id,
                'nama'    => $p['nama'],
                'alamat'  => $p['alamat'],
                'no_hp'   => $p['no_hp'],
            ]);
        }

        // Data Komoditas
        $komoditas = [
            ['nama_komoditas' => 'Kelapa',       'jenis' => 'Perkebunan'],
            ['nama_komoditas' => 'Cengkeh',      'jenis' => 'Perkebunan'],
            ['nama_komoditas' => 'Pala',         'jenis' => 'Perkebunan'],
            ['nama_komoditas' => 'Cabai (Rica)',  'jenis' => 'Hortikultura'],
            ['nama_komoditas' => 'Tomat',        'jenis' => 'Hortikultura'],
            ['nama_komoditas' => 'Jagung',       'jenis' => 'Pertanian'],
            ['nama_komoditas' => 'Sayuran',      'jenis' => 'Hortikultura'],
            ['nama_komoditas' => 'Padi Ladang',  'jenis' => 'Pertanian'],
        ];

        foreach ($komoditas as $k) {
            Komoditas::create($k);
        }

        // Data Produksi
        $allPetani    = Petani::all();
        $allKomoditas = Komoditas::all();

        $produksiData = [
            ['petani' => 'Ahmad Basir',     'komoditas' => 'Kelapa',       'tanggal' => '2025-01-10', 'hasil' => 500,  'harga' => 3500,  'biaya' => 500000,  'catatan' => 'Panen perdana tahun ini'],
            ['petani' => 'Ahmad Basir',     'komoditas' => 'Kelapa',       'tanggal' => '2025-03-15', 'hasil' => 620,  'harga' => 3600,  'biaya' => 550000,  'catatan' => 'Hasil meningkat'],
            ['petani' => 'Ahmad Basir',     'komoditas' => 'Kelapa',       'tanggal' => '2025-06-20', 'hasil' => 580,  'harga' => 3700,  'biaya' => 520000,  'catatan' => null],
            ['petani' => 'Siti Rahmawati',  'komoditas' => 'Cengkeh',      'tanggal' => '2025-02-05', 'hasil' => 200,  'harga' => 85000, 'biaya' => 800000,  'catatan' => 'Musim cengkeh bagus'],
            ['petani' => 'Siti Rahmawati',  'komoditas' => 'Cengkeh',      'tanggal' => '2025-05-10', 'hasil' => 175,  'harga' => 90000, 'biaya' => 750000,  'catatan' => null],
            ['petani' => 'Siti Rahmawati',  'komoditas' => 'Pala',         'tanggal' => '2025-08-18', 'hasil' => 150,  'harga' => 55000, 'biaya' => 600000,  'catatan' => 'Panen pala pertama'],
            ['petani' => 'Umar Djalil',     'komoditas' => 'Pala',         'tanggal' => '2025-01-25', 'hasil' => 300,  'harga' => 50000, 'biaya' => 700000,  'catatan' => null],
            ['petani' => 'Umar Djalil',     'komoditas' => 'Pala',         'tanggal' => '2025-04-12', 'hasil' => 320,  'harga' => 52000, 'biaya' => 720000,  'catatan' => 'Kualitas baik'],
            ['petani' => 'Umar Djalil',     'komoditas' => 'Jagung',       'tanggal' => '2025-07-08', 'hasil' => 800,  'harga' => 5000,  'biaya' => 400000,  'catatan' => null],
            ['petani' => 'Fatima Soleman',  'komoditas' => 'Cabai (Rica)', 'tanggal' => '2025-02-20', 'hasil' => 100,  'harga' => 35000, 'biaya' => 300000,  'catatan' => 'Harga naik'],
            ['petani' => 'Fatima Soleman',  'komoditas' => 'Cabai (Rica)', 'tanggal' => '2025-05-25', 'hasil' => 120,  'harga' => 40000, 'biaya' => 320000,  'catatan' => null],
            ['petani' => 'Fatima Soleman',  'komoditas' => 'Tomat',        'tanggal' => '2025-09-14', 'hasil' => 200,  'harga' => 12000, 'biaya' => 250000,  'catatan' => null],
            ['petani' => 'Ruslan Hamid',    'komoditas' => 'Kelapa',       'tanggal' => '2025-03-05', 'hasil' => 450,  'harga' => 3500,  'biaya' => 480000,  'catatan' => null],
            ['petani' => 'Ruslan Hamid',    'komoditas' => 'Padi Ladang',  'tanggal' => '2025-06-30', 'hasil' => 1200, 'harga' => 8000,  'biaya' => 1500000, 'catatan' => 'Panen raya'],
            ['petani' => 'Ruslan Hamid',    'komoditas' => 'Padi Ladang',  'tanggal' => '2025-10-15', 'hasil' => 1100, 'harga' => 8500,  'biaya' => 1400000, 'catatan' => null],
            ['petani' => 'Halima Usman',    'komoditas' => 'Sayuran',      'tanggal' => '2025-01-15', 'hasil' => 300,  'harga' => 8000,  'biaya' => 200000,  'catatan' => null],
            ['petani' => 'Halima Usman',    'komoditas' => 'Sayuran',      'tanggal' => '2025-04-20', 'hasil' => 350,  'harga' => 9000,  'biaya' => 220000,  'catatan' => 'Permintaan tinggi'],
            ['petani' => 'Halima Usman',    'komoditas' => 'Tomat',        'tanggal' => '2025-07-25', 'hasil' => 180,  'harga' => 13000, 'biaya' => 240000,  'catatan' => null],
            ['petani' => 'Ibrahim Saleh',   'komoditas' => 'Cengkeh',      'tanggal' => '2025-02-28', 'hasil' => 250,  'harga' => 87000, 'biaya' => 850000,  'catatan' => null],
            ['petani' => 'Ibrahim Saleh',   'komoditas' => 'Kelapa',       'tanggal' => '2025-05-18', 'hasil' => 520,  'harga' => 3600,  'biaya' => 510000,  'catatan' => null],
            ['petani' => 'Ibrahim Saleh',   'komoditas' => 'Jagung',       'tanggal' => '2025-08-22', 'hasil' => 750,  'harga' => 5200,  'biaya' => 380000,  'catatan' => 'Hasil jagung bagus'],
            ['petani' => 'Nurhayati Malik', 'komoditas' => 'Pala',         'tanggal' => '2025-03-10', 'hasil' => 280,  'harga' => 51000, 'biaya' => 680000,  'catatan' => null],
            ['petani' => 'Nurhayati Malik', 'komoditas' => 'Cabai (Rica)', 'tanggal' => '2025-06-05', 'hasil' => 90,   'harga' => 38000, 'biaya' => 280000,  'catatan' => null],
            ['petani' => 'Nurhayati Malik', 'komoditas' => 'Sayuran',      'tanggal' => '2025-09-20', 'hasil' => 400,  'harga' => 8500,  'biaya' => 230000,  'catatan' => 'Panen sayuran melimpah'],
        ];

        foreach ($produksiData as $p) {
            $petani    = $allPetani->where('nama', $p['petani'])->first();
            $komoditas = $allKomoditas->where('nama_komoditas', $p['komoditas'])->first();

            if ($petani && $komoditas) {
                Produksi::create([
                    'petani_id'      => $petani->id,
                    'komoditas_id'   => $komoditas->id,
                    'tanggal_panen'  => $p['tanggal'],
                    'hasil_panen_kg' => $p['hasil'],
                    'harga_per_kg'   => $p['harga'],
                    'biaya_produksi' => $p['biaya'],
                    'catatan'        => $p['catatan'],
                ]);
            }
        }
    }
}