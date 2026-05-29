<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function interviewsAsInterviewer()
    {
        return $this->hasMany(InterviewSchedule::class, 'interviewer_id');
    }

    public function isHr(): bool
    {
        return $this->role === 'hr';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'hr'       => 'HRD',
            'reviewer' => 'User / Reviewer',
            default    => ucfirst($this->role),
        };
    }
}
