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
        Schema::create('intellectual_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('title');
            $table->string('ipr_type', 50)->index(); // hak_cipta, paten, paten_sederhana, merek, desain_industri, lainnya
            $table->string('sub_type', 100)->nullable(); // Program Komputer, Karya Tulis, dll
            $table->string('application_number', 100)->nullable()->index();
            $table->date('application_date')->nullable();
            $table->string('registration_number', 100)->nullable()->index();
            $table->date('grant_date')->nullable();
            $table->string('djki_status', 50)->default('draft')->index(); // draft, diajukan, pemeriksaan, granted, ditolak
            $table->string('rights_holder')->default('Universitas');
            $table->string('certificate_file')->nullable();
            $table->string('description_file')->nullable();
            $table->string('verification_status', 50)->default('pending')->index(); // pending, revision, approved, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_note')->nullable();
            $table->timestamps();
        });

        Schema::create('ipr_inventors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intellectual_property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('inventor_name');
            $table->string('inventor_type', 50)->default('internal_lecturer'); // internal_lecturer, student, external
            $table->string('role', 50)->default('inventor_anggota'); // inventor_utama, inventor_anggota
            $table->unsignedSmallInteger('inventor_order')->default(1);
            $table->decimal('contribution_percentage', 5, 2)->nullable();
            $table->string('affiliation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipr_inventors');
        Schema::dropIfExists('intellectual_properties');
    }
};

