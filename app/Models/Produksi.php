<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
protected $table = 'produksi';

    protected $fillable = [
        'petani_id',
        'komoditas_id',
        'tanggal_panen',
        'hasil_panen_kg',
        'harga_per_kg',
        'biaya_produksi',
        'catatan',
    ];

    // Kalkulasi otomatis
    public function getPendapatanAttribute(): float
    {
        return $this->hasil_panen_kg * $this->harga_per_kg;
    }

    public function getKeuntunganAttribute(): float
    {
        return $this->pendapatan - $this->biaya_produksi;
    }

    public function petani()
    {
        return $this->belongsTo(Petani::class);
    }

    public function komoditas()
    {
        return $this->belongsTo(Komoditas::class);
    }
}