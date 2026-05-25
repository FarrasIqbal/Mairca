<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan interview_type ke evaluations
        Schema::table('evaluations', function (Blueprint $table) {
            $table->enum('interview_type', ['hr', 'user'])->default('hr')->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn('interview_type');
        });
    }
};
