<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix kas_transactions table
        if (Schema::hasTable('kas_transactions') && !Schema::hasColumn('kas_transactions', 'jenis')) {
            Schema::table('kas_transactions', function (Blueprint $table) {
                $table->enum('jenis', ['masuk', 'keluar'])->after('id');
                $table->decimal('jumlah', 12, 2)->after('jenis')->default(0);
                $table->date('tanggal')->after('jumlah')->nullable();
                $table->text('keterangan')->after('tanggal')->nullable();
                $table->foreignId('fakultas_id')->nullable()->after('keterangan')->constrained('fakultas')->nullOnDelete();
                $table->foreignId('user_id')->after('fakultas_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('referensi_id')->after('user_id')->nullable();
                $table->string('referensi_type')->after('referensi_id')->nullable();

                $table->index(['jenis', 'tanggal']);
                $table->index('fakultas_id');
            });
        }

        // Fix kas_tagihans table
        if (Schema::hasTable('kas_tagihans') && !Schema::hasColumn('kas_tagihans', 'dosen_id')) {
            Schema::table('kas_tagihans', function (Blueprint $table) {
                $table->foreignId('dosen_id')->after('id')->constrained('dosens')->cascadeOnDelete();
                $table->foreignId('fakultas_id')->after('dosen_id')->constrained('fakultas')->cascadeOnDelete();
                $table->tinyInteger('bulan')->after('fakultas_id');
                $table->year('tahun')->after('bulan');
                $table->decimal('jumlah', 12, 2)->after('tahun')->default(0);
                $table->date('tanggal_jatuh_tempo')->after('jumlah')->nullable();
                $table->enum('status', ['belum_lunas', 'lunas'])->after('tanggal_jatuh_tempo')->default('belum_lunas');
                $table->decimal('dibayar_amount', 12, 2)->after('status')->default(0);
                $table->date('dibayar_tanggal')->after('dibayar_amount')->nullable();
                $table->foreignId('user_id')->after('dibayar_tanggal')->constrained('users')->cascadeOnDelete();

                $table->unique(['dosen_id', 'bulan', 'tahun']);
                $table->index(['status', 'tahun', 'bulan']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kas_transactions') && Schema::hasColumn('kas_transactions', 'jenis')) {
            Schema::table('kas_transactions', function (Blueprint $table) {
                $table->dropForeign(['fakultas_id']);
                $table->dropForeign(['user_id']);
                $table->dropColumn([
                    'jenis',
                    'jumlah',
                    'tanggal',
                    'keterangan',
                    'fakultas_id',
                    'user_id',
                    'referensi_id',
                    'referensi_type'
                ]);
            });
        }

        if (Schema::hasTable('kas_tagihans') && Schema::hasColumn('kas_tagihans', 'dosen_id')) {
            Schema::table('kas_tagihans', function (Blueprint $table) {
                $table->dropForeign(['dosen_id']);
                $table->dropForeign(['fakultas_id']);
                $table->dropForeign(['user_id']);
                $table->dropColumn([
                    'dosen_id',
                    'fakultas_id',
                    'bulan',
                    'tahun',
                    'jumlah',
                    'tanggal_jatuh_tempo',
                    'status',
                    'dibayar_amount',
                    'dibayar_tanggal',
                    'user_id'
                ]);
            });
        }
    }
};
