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
        Schema::table('notulensi', function (Blueprint $table) {
            $table->text('agenda_rapat')->nullable()->after('agenda');
        });
    }

    public function down(): void
    {
        Schema::table('notulensi', function (Blueprint $table) {
            $table->dropColumn('agenda_rapat');
        });
    }
};
