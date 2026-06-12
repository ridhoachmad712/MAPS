<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->foreignId('user_id')->unique()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('nim', 20)->unique();
            $table->string('nama_lengkap');
            $table->year('angkatan');
            $table->string('program_studi', 100)->default('Manajemen');
            $table->string('foto')->nullable();
            $table->boolean('konsen_publik')->default(false);
            $table->timestamps();

            $table->index('angkatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
