<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('whatsapp', 50);
            $table->text('pertanyaan');
            $table->enum('status', ['New', 'In Progress', 'Responded', 'Converted'])->default('New');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_leads');
    }
};