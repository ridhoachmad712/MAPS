<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Filament telah dicabut — tabel pendukung impor/ekspor antrian dan
 * notifikasi database-nya tidak terpakai lagi. Impor/ekspor kini berjalan
 * sinkron tanpa tabel pelacak.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('failed_import_rows'); // FK ke imports — hapus duluan
        Schema::dropIfExists('imports');
        Schema::dropIfExists('exports');
        Schema::dropIfExists('notifications');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('imports', function (Blueprint $table): void {
            $table->id();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('importer');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('successful_rows')->default(0);
            $table->foreignId('user_user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('exports', function (Blueprint $table): void {
            $table->id();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_disk');
            $table->string('file_name')->nullable();
            $table->string('exporter');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('successful_rows')->default(0);
            $table->foreignId('user_user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('failed_import_rows', function (Blueprint $table): void {
            $table->id();
            $table->json('data');
            $table->foreignId('import_id')->constrained()->cascadeOnDelete();
            $table->text('validation_error')->nullable();
            $table->timestamps();
        });
    }
};
