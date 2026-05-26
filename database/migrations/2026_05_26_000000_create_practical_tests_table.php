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
        Schema::create('practical_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('token')->unique();
            $table->integer('score')->nullable();
            $table->integer('passing_score')->default(70);
            $table->text('answers')->nullable(); // JSON string or text containing candidate answers
            $table->boolean('is_suitable')->nullable(); // score >= passing_score
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practical_tests');
    }
};
