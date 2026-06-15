<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropForeign(['registration_path_id']);
            $table->dropColumn('registration_path_id');
        });
    }

    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->foreignId('registration_path_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};