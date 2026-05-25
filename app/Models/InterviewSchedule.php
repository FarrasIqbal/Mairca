<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'interviewer_id',
        'type',
        'scheduled_at',
        'zoom_link',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'hr'   => 'Wawancara HR',
            'user' => 'Wawancara User',
            default => ucfirst($this->type),
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'scheduled'  => 'Terjadwal',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
