<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_lengkap');
            $table->string('nidn_nip')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('peran_tim')->default('Anggota'); // Ketua / Anggota Dosen / Anggota Mahasiswa
            $table->text('tugas_tim')->nullable();
            $table->enum('status_konfirmasi', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('tgl_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_members');
    }
};
