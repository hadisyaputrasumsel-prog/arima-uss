<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->enum('termin', ['termin_1', 'termin_2']); // 70% dan 30%
            $table->unsignedTinyInteger('persentase'); // 70 atau 30
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['pending', 'verified', 'paid'])->default('pending');
            $table->date('tanggal_cair')->nullable();
            $table->string('nomor_transaksi')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // Bagian Keuangan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
