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
        Schema::table('positions', function (Blueprint $table) {
            $table->integer('test_duration')->nullable()->default(60)->comment('Practical test duration in minutes');
        });

        Schema::table('practical_tests', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('test_duration');
        });

        Schema::table('practical_tests', function (Blueprint $table) {
            $table->dropColumn('started_at');
        });
    }
};
