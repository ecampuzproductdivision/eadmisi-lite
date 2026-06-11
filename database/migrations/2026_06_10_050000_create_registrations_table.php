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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_path_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('program_studi_1_id')->nullable()->constrained('program_studis')->onDelete('set null');
            $table->foreignId('program_studi_2_id')->nullable()->constrained('program_studis')->onDelete('set null');
            $table->string('nama_lengkap', 200);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('nik', 16)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('nama_sekolah', 200)->nullable();
            $table->string('jurusan', 200)->nullable();
            $table->string('tahun_lulus', 4)->nullable();
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'accepted', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};