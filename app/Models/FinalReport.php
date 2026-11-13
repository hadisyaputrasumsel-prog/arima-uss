<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'ringkasan_hasil',
        'realisasi_honorarium',
        'realisasi_bahan_peralatan',
        'realisasi_perjalanan',
        'realisasi_publikasi',
        'realisasi_lain_lain',
        'total_realisasi_dana',
        'kendala_rekomendasi',
        'file_laporan_akhir',
        'file_sptb',
        'status_validasi',
        'catatan_validasi',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'realisasi_honorarium' => 'decimal:2',
        'realisasi_bahan_peralatan' => 'decimal:2',
        'realisasi_perjalanan' => 'decimal:2',
        'realisasi_publikasi' => 'decimal:2',
        'realisasi_lain_lain' => 'decimal:2',
        'total_realisasi_dana' => 'decimal:2',
        'validated_at' => 'date',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getStatusValidasiLabelAttribute(): string
    {
        return match ($this->status_validasi) {
            'submitted' => 'Menunggu Validasi Luaran',
            'revision_needed' => 'Perlu Perbaikan / Kelengkapan Luaran',
            'valid' => 'Luaran Dinyatakan Sah & Valid',
            default => ucfirst($this->status_validasi),
        };
    }
}
