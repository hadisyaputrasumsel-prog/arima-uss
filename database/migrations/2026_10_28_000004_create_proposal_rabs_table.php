<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_rabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->enum('kategori_biaya', [
                'honorarium',
                'bahan_peralatan',
                'perjalanan',
                'publikasi_luaran',
                'lain_lain'
            ]);
            $table->string('item_biaya');
            $table->decimal('volume', 10, 2)->default(1);
            $table->string('satuan'); // mis: OK, Bulan, Unit, Paket
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_rabs');
    }
};
