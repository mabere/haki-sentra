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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('panduan'); // alur_syarat, template_dokumen, panduan, pengumuman, faq
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // docx, pdf, zip, etc.
            $table->string('file_size')->nullable(); // e.g. 245 KB
            $table->string('external_link')->nullable();
            $table->string('icon')->nullable(); // lucide icon identifier
            $table->string('badge_text')->nullable(); // e.g. "Tahap 01" / "Resmi LPPM"
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
