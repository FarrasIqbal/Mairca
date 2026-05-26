<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'token',
        'started_at',
        'score',
        'passing_score',
        'answers',
        'question_scores',
        'reviewer_notes',
        'is_suitable',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'question_scores' => 'array',
        'is_suitable' => 'boolean',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
