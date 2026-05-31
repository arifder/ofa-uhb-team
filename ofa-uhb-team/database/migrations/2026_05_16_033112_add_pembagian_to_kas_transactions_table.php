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
        Schema::table('kas_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('kas_transactions', 'tabungan')) {
                $table->unsignedBigInteger('tabungan')->default(0)->after('jumlah');
            }
            if (!Schema::hasColumn('kas_transactions', 'uang_sosial')) {
                $table->unsignedBigInteger('uang_sosial')->default(0)->after('tabungan');
            }
            if (!Schema::hasColumn('kas_transactions', 'dosen_id')) {
                $table->foreignId('dosen_id')->nullable()->constrained('dosens')->after('fakultas_id')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('kas_transactions', 'dosen_id')) {
                $table->dropForeign(['dosen_id']);
                $table->dropColumn('dosen_id');
            }
            if (Schema::hasColumn('kas_transactions', 'uang_sosial')) {
                $table->dropColumn('uang_sosial');
            }
            if (Schema::hasColumn('kas_transactions', 'tabungan')) {
                $table->dropColumn('tabungan');
            }
        });
    }
};
