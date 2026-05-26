<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'question_text',
        'placeholder_text',
        'grading_guide',
        'max_score',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
