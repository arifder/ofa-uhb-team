<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fakultas', function (Blueprint $table) {
            $table->string('nama_dekan')->nullable()->after('nama_fakultas');
        });

        Schema::table('prodis', function (Blueprint $table) {
            $table->string('nama_kaprodi')->nullable()->after('nama_prodi');
        });
    }

    public function down(): void
    {
        Schema::table('fakultas', function (Blueprint $table) {
            $table->dropColumn('nama_dekan');
        });

        Schema::table('prodis', function (Blueprint $table) {
            $table->dropColumn('nama_kaprodi');
        });
    }
};
