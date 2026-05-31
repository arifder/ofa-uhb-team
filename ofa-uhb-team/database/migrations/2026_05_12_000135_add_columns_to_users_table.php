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
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['super_admin', 'admin_fakultas', 'kepala_unit', 'dosen'])->default('dosen')->after('email');
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->onDelete('set null')->after('role');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fakultas_id']);
            $table->dropColumn(['username', 'role', 'fakultas_id', 'status']);
        });
    }
};
