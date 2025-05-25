<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa', 255);
			$table->string('nis', 15);
			$table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->restrictOnUpdate()->nullOnDelete();
			$table->foreignId('kelas_id')->nullable()->constrained('kelas')->restrictOnUpdate()->nullOnDelete();
            $table->foreignId('ruang_ujian_id')->nullable()->constrained('ruang_ujian')->restrictOnUpdate()->nullOnDelete();
			$table->string('password', 50);
			$table->string('token', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
