<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->string('jenis_luaran');
            $table->text('target_uraian');
            $table->enum('status_capaian', [
                'draft',
                'submitted',
                'accepted',
                'published',
                'granted'
            ])->default('draft');
            $table->string('bukti_file')->nullable();
            $table->string('tautan_luaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outputs');
    }
};
