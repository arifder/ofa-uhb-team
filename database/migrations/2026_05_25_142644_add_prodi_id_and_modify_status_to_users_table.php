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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('prodi_id')->nullable()->after('fakultas_id')->constrained('prodis')->onDelete('set null');
        });
        
        \Illuminate\Support\Facades\DB::statement("UPDATE users SET status = 'aktif' WHERE status = 'nonaktif'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('aktif', 'arsip') DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("UPDATE users SET status = 'aktif' WHERE status = 'arsip'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['prodi_id']);
            $table->dropColumn('prodi_id');
        });
    }
};
