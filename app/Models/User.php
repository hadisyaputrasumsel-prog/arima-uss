<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nidn',
        'nip',
        'role',
        'jabatan_fungsional',
        'fakultas',
        'program_studi',
        'no_hp',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'user_id');
    }

    public function memberProposals(): HasMany
    {
        return $this->hasMany(ProposalMember::class, 'user_id');
    }

    public function reviewerAssignments(): HasMany
    {
        return $this->hasMany(ReviewerAssignment::class, 'reviewer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function isAdminLppm(): bool
    {
        return $this->role === 'admin_lppm' || $this->role === 'admin_ti';
    }

    public function isAdminTi(): bool
    {
        return $this->role === 'admin_ti';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isReviewer(): bool
    {
        return $this->role === 'reviewer';
    }

    public function isKeuangan(): bool
    {
        return $this->role === 'keuangan';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin_ti' => 'Administrator TI',
            'admin_lppm' => 'Admin LPPM',
            'dosen' => 'Dosen / Pengusul',
            'reviewer' => 'Reviewer Internal',
            'keuangan' => 'Bagian Keuangan',
            'pimpinan' => 'Pimpinan (WR / Dekan)',
            default => ucfirst($this->role),
        };
    }
}
