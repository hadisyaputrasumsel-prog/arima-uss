<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Output extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'jenis_luaran',
        'target_uraian',
        'status_capaian',
        'bukti_file',
        'tautan_luaran',
        'keterangan',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function getStatusCapaianLabelAttribute(): string
    {
        return match ($this->status_capaian) {
            'draft' => 'Draf / Belum Diajukan',
            'submitted' => 'Telah Diserahkan (Submitted)',
            'accepted' => 'Diterima (Accepted)',
            'published' => 'Terbit / Publikasi (Published)',
            'granted' => 'Granted / Sertifikat Terbit',
            default => ucfirst($this->status_capaian),
        };
    }
}
