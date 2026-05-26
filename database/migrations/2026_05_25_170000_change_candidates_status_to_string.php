<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop the check constraint if it exists
        DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");

        // 2. Alter column status type from native enum to varchar(50) so we can save any string status
        // Utilizing USING status::varchar handles PostgreSQL cast conversion perfectly
        DB::statement("ALTER TABLE candidates ALTER COLUMN status TYPE VARCHAR(50) USING status::VARCHAR");

        // 3. Re-add the check constraint with all the new pipeline statuses
        DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','wawancara_hr','wawancara_user','evaluasi_spk','hired','rejected'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE candidates DROP CONSTRAINT IF EXISTS candidates_status_check");
        DB::statement("ALTER TABLE candidates ADD CONSTRAINT candidates_status_check CHECK (status IN ('berkas','tes_praktis','evaluasi_spk','hired','rejected'))");
    }
};
