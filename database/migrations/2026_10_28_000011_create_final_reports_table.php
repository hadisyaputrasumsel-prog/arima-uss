<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->text('ringkasan_hasil');
            
            // Realisasi Anggaran 100%
            $table->decimal('realisasi_honorarium', 15, 2)->default(0);
            $table->decimal('realisasi_bahan_peralatan', 15, 2)->default(0);
            $table->decimal('realisasi_perjalanan', 15, 2)->default(0);
            $table->decimal('realisasi_publikasi', 15, 2)->default(0);
            $table->decimal('realisasi_lain_lain', 15, 2)->default(0);
            $table->decimal('total_realisasi_dana', 15, 2)->default(0);
            
            $table->text('kendala_rekomendasi')->nullable();
            $table->string('file_laporan_akhir');
            
            // Validasi Luaran & Akhir oleh Reviewer/LPPM
            $table->enum('status_validasi', ['submitted', 'revision_needed', 'valid'])->default('submitted');
            $table->text('catatan_validasi')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_reports');
    }
};
