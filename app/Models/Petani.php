<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    protected $table = 'petani';

    protected $fillable = [
        'user_id',
        'nama',
        'alamat',
        'no_hp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produksis()
    {
        return $this->hasMany(Produksi::class);
    }
}