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
        Schema::table('test_questions', function (Blueprint $table) {
            $table->integer('max_score')->default(100)->after('placeholder_text');
        });

        Schema::table('practical_tests', function (Blueprint $table) {
            $table->json('question_scores')->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->dropColumn('max_score');
        });

        Schema::table('practical_tests', function (Blueprint $table) {
            $table->dropColumn('question_scores');
        });
    }
};
