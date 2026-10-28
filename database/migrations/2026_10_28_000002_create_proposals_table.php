<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('schemes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Ketua Pengusul
            $table->string('judul');
            $table->enum('kategori', ['penelitian', 'pengabdian']);
            $table->string('bidang_fokus');
            $table->unsignedTinyInteger('tkt')->nullable(); // 1 - 9
            $table->unsignedSmallInteger('lama_kegiatan')->default(6); // bulan
            $table->string('lokasi_kegiatan');
            $table->decimal('total_dana_usulan', 15, 2)->default(0);
            $table->decimal('dana_disetujui', 15, 2)->nullable();
            $table->text('ringkasan')->nullable();
            $table->enum('status', [
                'draft',
                'submitted',
                'admin_review',
                'admin_revision',
                'substantive_review',
                'approved',
                'rejected',
                'contract',
                'in_progress',
                'monev',
                'final_report',
                'completed'
            ])->default('draft');
            $table->text('catatan_administrasi')->nullable();
            $table->string('sk_penetapan_no')->nullable();
            $table->date('sk_penetapan_tgl')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
