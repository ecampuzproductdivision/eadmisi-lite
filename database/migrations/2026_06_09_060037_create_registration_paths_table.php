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
        Schema::create('registration_paths', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Kode jalur, misal: SNBP, SNBT, MANDIRI');
            $table->string('name', 200)->comment('Nama jalur pendaftaran');
            $table->text('description')->nullable()->comment('Deskripsi jalur');
            $table->date('registration_start')->nullable()->comment('Tanggal mulai pendaftaran');
            $table->date('registration_end')->nullable()->comment('Tanggal akhir pendaftaran');
            $table->decimal('fee', 15, 2)->default(0)->comment('Biaya pendaftaran');
            $table->string('color', 20)->nullable()->comment('Warna badge, misal: primary, success, warning');
            $table->string('icon', 100)->nullable()->comment('Icon jalur');
            $table->integer('quota')->nullable()->comment('Kuota pendaftar');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_paths');
    }
};