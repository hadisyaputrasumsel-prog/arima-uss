<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'termin',
        'persentase',
        'nominal',
        'status',
        'tanggal_cair',
        'nomor_transaksi',
        'bukti_transfer',
        'catatan',
        'processed_by',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_cair' => 'date',
        'persentase' => 'integer',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getTerminLabelAttribute(): string
    {
        return match ($this->termin) {
            'termin_1' => 'Tahap I (70% - Awal Kegiatan)',
            'termin_2' => 'Tahap II (30% - Pelunasan Luaran)',
            default => ucfirst($this->termin),
        };
    }
}
