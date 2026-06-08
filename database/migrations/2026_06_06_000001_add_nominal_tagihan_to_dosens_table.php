<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('dosens', 'nominal_tagihan')) {
            return;
        }

        Schema::table('dosens', function (Blueprint $table) {
            $table->unsignedBigInteger('nominal_tagihan')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('dosens', 'nominal_tagihan')) {
            return;
        }

        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('nominal_tagihan');
        });
    }
};
