<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->string('file_sptb')->nullable()->after('file_laporan');
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->string('file_sptb')->nullable()->after('file_laporan_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('progress_reports', function (Blueprint $table) {
            $table->dropColumn('file_sptb');
        });

        Schema::table('final_reports', function (Blueprint $table) {
            $table->dropColumn('file_sptb');
        });
    }
};
