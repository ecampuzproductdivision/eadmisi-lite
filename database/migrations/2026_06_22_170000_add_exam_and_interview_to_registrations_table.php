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
        Schema::table('registrations', function (Blueprint $table) {
            $table->boolean('memerlukan_ujian')->default(false)->after('status');
            $table->boolean('memerlukan_wawancara')->default(false)->after('memerlukan_ujian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['memerlukan_ujian', 'memerlukan_wawancara']);
        });
    }
};
