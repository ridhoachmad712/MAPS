<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukti', function (Blueprint $table) {
            // 'tautan' = bukti berupa URL (Google Drive, dll.) — hemat penyimpanan server
            $table->enum('sumber', ['berkas', 'tautan'])->default('berkas')->after('portofolio_id');
            $table->string('url', 1000)->nullable()->after('sumber');

            // Tautan tidak punya berkas fisik
            $table->string('path_file')->nullable()->change();
            $table->string('tipe_file', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bukti', function (Blueprint $table) {
            $table->dropColumn(['sumber', 'url']);
        });
    }
};
