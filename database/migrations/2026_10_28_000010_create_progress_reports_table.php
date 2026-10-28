<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->unsignedTinyInteger('persentase_capaian')->default(0);
            $table->text('ringkasan_kemajuan');
            $table->decimal('dana_terpakai', 15, 2)->default(0);
            $table->decimal('sisa_dana', 15, 2)->default(0);
            $table->string('file_laporan');
            $table->string('file_dokumentasi')->nullable();
            
            // Evaluasi Monev
            $table->enum('status_monev', ['submitted', 'need_revision', 'approved'])->default('submitted');
            $table->enum('rekomendasi_monev', ['lanjut_tanpa_catatan', 'lanjut_dengan_perbaikan', 'belum_dapat_diterima'])->nullable();
            $table->text('catatan_monev')->nullable();
            $table->foreignId('monev_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tgl_monev')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
    }
};
