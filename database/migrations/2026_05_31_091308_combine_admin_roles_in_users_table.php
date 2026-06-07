<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah sementara ke string
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });

        // 2. Update existing users
        DB::table('users')->whereIn('role', ['admin_kas_fst', 'admin_notulensi_fst'])->update(['role' => 'admin_fst']);
        DB::table('users')->whereIn('role', ['admin_kas_fis', 'admin_notulensi_fis'])->update(['role' => 'admin_fis']);

        // 3. Kembalikan ke enum
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'super_admin',
                'admin_fst',
                'admin_fis',
                'kepala_unit',
                'dosen'
            ])->default('dosen')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });

        // Revert to old mapping if possible, though exact previous role might be lost
        DB::table('users')->where('role', 'admin_fst')->update(['role' => 'admin_kas_fst']);
        DB::table('users')->where('role', 'admin_fis')->update(['role' => 'admin_notulensi_fis']);

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
};
