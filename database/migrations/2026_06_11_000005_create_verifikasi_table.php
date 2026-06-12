<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi', function (Blueprint $table) {
            $table->id('verifikasi_id');
            $table->foreignId('portofolio_id')->constrained('portofolio', 'portofolio_id')->cascadeOnDelete();
            $table->foreignId('verifikator_id')->constrained('users', 'user_id');
            $table->enum('hasil', ['diverifikasi', 'ditolak', 'revisi']);
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_verifikasi')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi');
    }
};
