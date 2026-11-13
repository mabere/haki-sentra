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
        Schema::table('lecturers', function (Blueprint $table) {
            $table->unsignedInteger('sinta_score_overall')->default(0)->nullable()->after('phone');
            $table->unsignedInteger('sinta_score_3year')->default(0)->nullable()->after('sinta_score_overall');
            $table->unsignedInteger('google_scholar_citations')->default(0)->nullable()->after('sinta_score_3year');
            $table->unsignedInteger('google_scholar_h_index')->default(0)->nullable()->after('google_scholar_citations');
            $table->unsignedInteger('google_scholar_i10_index')->default(0)->nullable()->after('google_scholar_h_index');
            $table->timestamp('last_synced_at')->nullable()->after('google_scholar_i10_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn([
                'sinta_score_overall',
                'sinta_score_3year',
                'google_scholar_citations',
                'google_scholar_h_index',
                'google_scholar_i10_index',
                'last_synced_at',
            ]);
        });
    }
};
