<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti', function (Blueprint $table) {
            $table->id('bukti_id');
            $table->foreignId('portofolio_id')->constrained('portofolio', 'portofolio_id')->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file', 50);
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti');
    }
};
