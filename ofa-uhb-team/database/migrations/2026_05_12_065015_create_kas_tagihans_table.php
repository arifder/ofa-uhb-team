<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('dosens')->cascadeOnDelete();
            $table->foreignId('fakultas_id')->constrained('fakultas')->cascadeOnDelete();
            $table->tinyInteger('bulan');
            $table->year('tahun');
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->decimal('dibayar_amount', 12, 2)->default(0);
            $table->date('dibayar_tanggal')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dosen_id', 'bulan', 'tahun']);
            $table->index(['status', 'tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_tagihans');
    }
};