<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('produksi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('petani_id')->constrained('petani')->cascadeOnDelete();
        $table->foreignId('komoditas_id')->constrained('komoditas')->cascadeOnDelete();
        $table->date('tanggal_panen');
        $table->float('hasil_panen_kg');
        $table->float('harga_per_kg');
        $table->float('biaya_produksi');
        $table->float('pendapatan')->virtualAs('hasil_panen_kg * harga_per_kg');
        $table->float('keuntungan')->virtualAs('(hasil_panen_kg * harga_per_kg) - biaya_produksi');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
