<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_skema',
        'tahun_anggaran',
        'kategori',
        'pagu_anggaran',
        'maks_dana_usulan',
        'tgl_mulai_pengusulan',
        'tgl_akhir_pengusulan',
        'tgl_mulai_review',
        'tgl_akhir_review',
        'tgl_pengumuman',
        'tgl_monev',
        'tgl_laporan_akhir',
        'panduan_file',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
        'maks_dana_usulan' => 'decimal:2',
        'tgl_mulai_pengusulan' => 'date',
        'tgl_akhir_pengusulan' => 'date',
        'tgl_mulai_review' => 'date',
        'tgl_akhir_review' => 'date',
        'tgl_pengumuman' => 'date',
        'tgl_monev' => 'date',
        'tgl_laporan_akhir' => 'date',
        'is_active' => 'boolean',
    ];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function isOpenForSubmission(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        $today = now()->startOfDay();
        return $today->gte($this->tgl_mulai_pengusulan) && $today->lte($this->tgl_akhir_pengusulan);
    }
}
