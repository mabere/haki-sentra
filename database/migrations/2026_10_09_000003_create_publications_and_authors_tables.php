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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('title');
            $table->string('publication_type'); // jurnal_internasional, jurnal_nasional_sinta, prosiding, buku, lainnya
            $table->string('journal_name');
            $table->string('doi')->nullable()->index();
            $table->string('issn_isbn', 50)->nullable();
            $table->string('volume', 50)->nullable();
            $table->string('issue', 50)->nullable();
            $table->string('pages', 50)->nullable();
            $table->year('publication_year')->index();
            $table->date('publication_date')->nullable();
            $table->text('url')->nullable();
            $table->string('indexation', 50)->nullable(); // scopus, wos, sinta, garuda, google_scholar, lainnya
            $table->string('quartile', 10)->nullable(); // Q1, Q2, Q3, Q4, Non-Q
            $table->string('sinta_grade', 10)->nullable(); // S1, S2, S3, S4, S5, S6
            $table->string('file_path')->nullable();
            $table->string('loa_path')->nullable();
            $table->string('verification_status')->default('pending')->index(); // pending, revision, approved, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_note')->nullable();
            $table->timestamps();
        });

        Schema::create('publication_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->string('author_type')->default('internal_lecturer'); // internal_lecturer, student, external
            $table->string('author_role')->default('co_author'); // first_author, corresponding, co_author
            $table->unsignedSmallInteger('author_order')->default(1);
            $table->string('affiliation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_authors');
        Schema::dropIfExists('publications');
    }
};

