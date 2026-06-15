<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_path_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('field_type', 50); // text, textarea, select, radio, checkbox, file, date, email, number, tel, color
            $table->string('field_name', 100); // snake_case field name
            $table->string('field_label', 255);
            $table->string('placeholder', 255)->nullable();
            $table->text('help_text')->nullable();
            $table->text('options')->nullable(); // for select, radio, checkbox
            $table->text('validation_rules')->nullable();
            $table->string('section', 100)->nullable(); // grouping: Data Pribadi, Kontak & Alamat, etc.
            $table->integer('sort_order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('width', 20)->default('col-12'); // col-12, col-md-6, col-md-4, etc.
            $table->string('default_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};