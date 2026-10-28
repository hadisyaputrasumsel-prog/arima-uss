<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_skema');
            $table->string('tahun_anggaran', 4);
            $table->enum('kategori', ['penelitian', 'pengabdian']);
            $table->decimal('pagu_anggaran', 15, 2)->default(0);
            $table->decimal('maks_dana_usulan', 15, 2)->default(0);
            $table->date('tgl_mulai_pengusulan');
            $table->date('tgl_akhir_pengusulan');
            $table->date('tgl_mulai_review')->nullable();
            $table->date('tgl_akhir_review')->nullable();
            $table->date('tgl_pengumuman')->nullable();
            $table->date('tgl_monev')->nullable();
            $table->date('tgl_laporan_akhir')->nullable();
            $table->string('panduan_file')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schemes');
    }
};
