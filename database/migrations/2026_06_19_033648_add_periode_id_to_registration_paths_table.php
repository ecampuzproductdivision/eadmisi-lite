<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add periode_id foreign key to registration_paths table.
     */
    public function up(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->foreignId('periode_id')
                ->nullable()
                ->after('id')
                ->constrained('periode')
                ->onDelete('set null')
                ->comment('ID periode akademik aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_paths', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};