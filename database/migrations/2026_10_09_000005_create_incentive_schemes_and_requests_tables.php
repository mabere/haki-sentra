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
        Schema::create('incentive_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 50)->index(); // publication, ipr
            $table->string('tier', 50)->nullable(); // Q1, Q2, S1, paten_granted, hak_cipta
            $table->decimal('amount', 15, 2);
            $table->year('fiscal_year')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('incentive_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('incentive_scheme_id')->constrained()->cascadeOnDelete();
            $table->morphs('claimable'); // claimable_type & claimable_id (polymorphic)
            $table->decimal('requested_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->string('bank_name', 50);
            $table->string('bank_account_number', 50);
            $table->string('bank_account_holder', 100);
            $table->string('statement_letter_file')->nullable();
            $table->string('transfer_proof_file')->nullable();
            $table->string('status', 50)->default('submitted')->index(); // submitted, verified, approved_finance, disbursed, rejected
            $table->date('disbursed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incentive_requests');
        Schema::dropIfExists('incentive_schemes');
    }
};

