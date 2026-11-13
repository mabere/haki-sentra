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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('nidn', 20)->nullable()->unique();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('front_title', 30)->nullable();
            $table->string('back_title', 30)->nullable();
            $table->string('academic_rank', 50)->nullable(); // Asisten Ahli, Lektor, Lektor Kepala, Guru Besar
            $table->string('sinta_id', 50)->nullable();
            $table->string('scopus_id', 50)->nullable();
            $table->string('google_scholar_id', 50)->nullable();
            $table->string('orcid_id', 50)->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};

