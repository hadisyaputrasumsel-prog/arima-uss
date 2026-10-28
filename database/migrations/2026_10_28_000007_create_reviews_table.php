<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('reviewer_assignments')->onDelete('cascade');
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            
            // Skor skala 1 - 5
            $table->unsignedTinyInteger('skor_metodologi'); // Bobot 30%
            $table->text('catatan_metodologi')->nullable();
            
            $table->unsignedTinyInteger('skor_rab'); // Bobot 20%
            $table->text('catatan_rab')->nullable();
            
            $table->unsignedTinyInteger('skor_luaran'); // Bobot 30%
            $table->text('catatan_luaran')->nullable();
            
            $table->unsignedTinyInteger('skor_rekam_jejak'); // Bobot 20%
            $table->text('catatan_rekam_jejak')->nullable();
            
            $table->decimal('nilai_akhir', 5, 2); // (0.3*skor1 + 0.2*skor2 + 0.3*skor3 + 0.2*skor4) * 20 -> 100 scale or 1-5 scale
            $table->enum('rekomendasi', ['didanai', 'didanai_revisi', 'tidak_didanai']);
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
