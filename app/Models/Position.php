<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
