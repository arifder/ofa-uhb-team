<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi_notulensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notulensi_id')
                  ->constrained('notulensi')
                  ->cascadeOnDelete();
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_notulensi');
    }
};
