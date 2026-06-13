<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // disetujui = akun dibuat admin / petugas / sudah disetujui (default agar data lama aman)
            $table->enum('status_pendaftaran', ['menunggu', 'disetujui', 'ditolak'])
                ->default('disetujui')
                ->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_pendaftaran');
        });
    }
};
