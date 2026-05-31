<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_rapat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulensi_id')
                  ->constrained('notulensi')
                  ->cascadeOnDelete();
            $table->foreignId('dosen_id')
                  ->constrained('dosens')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_rapat');
    }
};
