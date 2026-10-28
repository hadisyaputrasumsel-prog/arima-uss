<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'persentase_capaian',
        'ringkasan_kemajuan',
        'dana_terpakai',
        'sisa_dana',
        'file_laporan',
        'file_dokumentasi',
        'status_monev',
        'rekomendasi_monev',
        'catatan_monev',
        'monev_by',
        'tgl_monev',
    ];

    protected $casts = [
        'persentase_capaian' => 'integer',
        'dana_terpakai' => 'decimal:2',
        'sisa_dana' => 'decimal:2',
        'tgl_monev' => 'date',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monev_by');
    }

    public function getRekomendasiLabelAttribute(): string
    {
        return match ($this->rekomendasi_monev) {
            'lanjut_tanpa_catatan' => 'Kegiatan dapat dilanjutkan tanpa catatan',
            'lanjut_dengan_perbaikan' => 'Kegiatan dapat dilanjutkan dengan perbaikan rekomendasi',
            'belum_dapat_diterima' => 'Kegiatan/luaran belum dapat diterima (wajib perbaikan)',
            default => 'Belum Dievaluasi',
        };
    }
}
