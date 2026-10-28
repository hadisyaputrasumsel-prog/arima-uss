<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'nomor_kontrak',
        'tanggal_kontrak',
        'nilai_kontrak',
        'file_kontrak',
        'status_ttd',
        'ttd_pengusul_tgl',
        'ttd_lppm_tgl',
        'catatan',
    ];

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'nilai_kontrak' => 'decimal:2',
        'ttd_pengusul_tgl' => 'datetime',
        'ttd_lppm_tgl' => 'datetime',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
