<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_id', 'criteria_id', 'user_id', 'score'];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
