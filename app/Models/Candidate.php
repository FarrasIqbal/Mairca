<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'name',
        'email',
        'phone',
        'resume_path',
        'status',
    ];

    public static array $statuses = [
        'berkas'         => 'Seleksi Berkas',
        'tes_praktis'    => 'Tes Praktis',
        'wawancara_hr'   => 'Wawancara HR',
        'wawancara_user' => 'Wawancara User',
        'evaluasi_spk'   => 'Evaluasi SPK',
        'hired'          => 'Diterima',
        'rejected'       => 'Ditolak',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedule::class);
    }

    public function getStatusLabel(): string
    {
        return self::$statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'berkas'         => 'gray',
            'tes_praktis'    => 'blue',
            'wawancara_hr'   => 'purple',
            'wawancara_user' => 'indigo',
            'evaluasi_spk'   => 'amber',
            'hired'          => 'green',
            'rejected'       => 'red',
            default          => 'gray',
        };
    }
}
