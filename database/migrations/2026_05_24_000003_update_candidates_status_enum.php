<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: alter enum by adding new values
        // Add new status values to the enum
        DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");
        DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','wawancara_hr','wawancara_user','evaluasi_spk','hired','rejected'))");

        // Add new columns
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('resume_path')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['phone', 'resume_path']);
        });

        DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");
        DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','evaluasi_spk','hired','rejected'))");
    }
};
