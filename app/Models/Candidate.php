<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['position_id', 'name', 'email', 'status'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
