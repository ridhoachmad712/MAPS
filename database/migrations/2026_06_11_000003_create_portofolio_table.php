<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portofolio', function (Blueprint $table) {
            $table->id('portofolio_id');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa', 'mahasiswa_id')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori', 'kategori_id')->restrictOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->year('tahun_pencapaian');
            $table->enum('level', ['regional', 'nasional', 'internasional']);
            $table->string('penyelenggara')->nullable();
            $table->string('peran_capaian')->nullable();
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'revisi', 'ditolak', 'dipublikasikan'])->default('draft');
            $table->boolean('is_publik')->default(false);
            $table->timestamps();

            $table->index('status');
            $table->index('tahun_pencapaian');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portofolio');
    }
};
