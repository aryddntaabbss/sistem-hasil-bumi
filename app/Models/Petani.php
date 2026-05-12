<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $fillable = ['nama', 'alamat', 'no_hp'];

    public function produksi()
    {
        return $this->hasMany(Produksi::class);
    }
}