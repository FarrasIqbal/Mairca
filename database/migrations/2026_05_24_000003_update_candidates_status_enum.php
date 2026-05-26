<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update CHECK constraint in a DB-specific way (Postgres vs MySQL/others)
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");
            DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','wawancara_hr','wawancara_user','evaluasi_spk','hired','rejected'))");
        } else {
            // MySQL: use DROP CHECK (no IF EXISTS) and guard with try/catch because
            // some MySQL versions or engines may not support CHECK constraints.
            try {
                DB::statement("ALTER TABLE candidates DROP CHECK candidates_status_check");
            } catch (\Exception $e) {
                // ignore if it doesn't exist or not supported
            }

            try {
                DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','wawancara_hr','wawancara_user','evaluasi_spk','hired','rejected'))");
            } catch (\Exception $e) {
                // ignore if the server/engine doesn't support check constraints
            }
        }

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

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");
            DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','evaluasi_spk','hired','rejected'))");
        } else {
            try {
                DB::statement("ALTER TABLE candidates DROP CHECK candidates_status_check");
            } catch (\Exception $e) {
            }

            try {
                DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','evaluasi_spk','hired','rejected'))");
            } catch (\Exception $e) {
            }
        }
    }
};
