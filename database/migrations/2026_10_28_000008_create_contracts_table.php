<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->string('nomor_kontrak')->unique();
            $table->date('tanggal_kontrak');
            $table->decimal('nilai_kontrak', 15, 2);
            $table->string('file_kontrak')->nullable();
            $table->enum('status_ttd', ['draft', 'signed_by_researcher', 'signed_by_lppm', 'active'])->default('draft');
            $table->timestamp('ttd_pengusul_tgl')->nullable();
            $table->timestamp('ttd_lppm_tgl')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
