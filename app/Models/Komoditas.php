<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    protected $fillable = ['nama_komoditas', 'jenis'];

    public function produksi()
    {
        return $this->hasMany(Produksi::class);
    }
}