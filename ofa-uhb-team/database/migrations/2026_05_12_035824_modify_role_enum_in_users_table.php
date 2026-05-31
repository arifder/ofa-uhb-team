<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah sementara ke string agar bisa menampung role baru
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });

        // 2. Update user yang sudah ada
        DB::table('users')->where('username', 'adminfst')->update([
            'name' => 'Admin Kas FST',
            'role' => 'admin_kas_fst'
        ]);

        DB::table('users')->where('username', 'adminfis')->update([
            'name' => 'Admin Notulensi FIS',
            'role' => 'admin_notulensi_fis'
        ]);

        // Pastikan tidak ada lagi role 'admin_fakultas'
        DB::table('users')->where('role', 'admin_fakultas')->update(['role' => 'dosen']);

        // 3. Kembalikan ke enum dengan daftar role baru
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'super_admin',
                'admin_kas_fst',
                'admin_notulensi_fst',
                'admin_kas_fis',
                'admin_notulensi_fis',
                'kepala_unit',
                'dosen'
            ])->default('dosen')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
