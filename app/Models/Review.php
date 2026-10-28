<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'proposal_id',
        'reviewer_id',
        'skor_metodologi',
        'catatan_metodologi',
        'skor_rab',
        'catatan_rab',
        'skor_luaran',
        'catatan_luaran',
        'skor_rekam_jejak',
        'catatan_rekam_jejak',
        'nilai_akhir',
        'rekomendasi',
        'catatan_umum',
    ];

    protected $casts = [
        'nilai_akhir' => 'decimal:2',
        'skor_metodologi' => 'integer',
        'skor_rab' => 'integer',
        'skor_luaran' => 'integer',
        'skor_rekam_jejak' => 'integer',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ReviewerAssignment::class, 'assignment_id');
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Helper to calculate total score based on Lampiran 5:
    // Metodologi: 30%, RAB: 20%, Luaran: 30%, Rekam Jejak: 20%
    public static function calculateScore(int $metodologi, int $rab, int $luaran, int $rekamJejak): float
    {
        // On scale 100: (0.30 * $metodologi + 0.20 * $rab + 0.30 * $luaran + 0.20 * $rekamJejak) * 20
        return round((0.30 * $metodologi + 0.20 * $rab + 0.30 * $luaran + 0.20 * $rekamJejak) * 20, 2);
    }

    public function getRekomendasiLabelAttribute(): string
    {
        return match ($this->rekomendasi) {
            'didanai' => 'Didanai Penuh',
            'didanai_revisi' => 'Didanai dengan Revisi',
            'tidak_didanai' => 'Tidak Didanai',
            default => ucfirst(str_replace('_', ' ', $this->rekomendasi)),
        };
    }
}
