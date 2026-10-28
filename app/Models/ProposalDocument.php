<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'jenis_dokumen',
        'file_path',
        'file_name',
        'file_size',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_dokumen) {
            'substansi_proposal' => 'Dokumen Substansi Proposal (PDF)',
            'surat_mitra' => 'Surat Kesediaan Mitra (Bermaterai)',
            'dokumen_pendukung_lain' => 'Dokumen Pendukung Lainnya',
            default => ucfirst(str_replace('_', ' ', $this->jenis_dokumen)),
        };
    }
}
