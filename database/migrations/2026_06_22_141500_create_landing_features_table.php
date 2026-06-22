<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 100)->default('ti-certificate');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('landing_settings')->insert([
            ['key' => 'contact_email', 'value' => 'info@eadmisi.ac.id', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '(021) 1234-5678', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_address', 'value' => 'Jl. Pendidikan No. 123, Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/eadmisi', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/eadmisi', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@eadmisi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed default features
        DB::table('landing_features')->insert([
            ['icon' => 'ti-certificate', 'title' => 'Terakreditasi Unggul', 'description' => 'Terakreditasi A oleh BAN-PT dengan nilai unggul dalam pelaksanaan pendidikan tinggi.', 'sort_order' => 1, 'is_active' => true],
            ['icon' => 'ti-affiliate', 'title' => 'Jaringan Luas', 'description' => 'Kerjasama dengan 200+ perusahaan dan institusi pendidikan nasional maupun internasional.', 'sort_order' => 2, 'is_active' => true],
            ['icon' => 'ti-device-laptop', 'title' => 'Pembelajaran Modern', 'description' => 'Sistem informasi akademik terintegrasi, LMS, dan laboratorium virtual yang mudah diakses 24/7.', 'sort_order' => 3, 'is_active' => true],
            ['icon' => 'ti-users', 'title' => 'Tenaga Pengajar Ahli', 'description' => 'Dosen berkualifikasi S3 dan praktisi industri yang berpengalaman di bidangnya masing-masing.', 'sort_order' => 4, 'is_active' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_settings');
        Schema::dropIfExists('landing_features');
    }
};