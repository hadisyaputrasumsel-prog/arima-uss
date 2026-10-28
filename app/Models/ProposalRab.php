<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalRab extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'kategori_biaya',
        'item_biaya',
        'volume',
        'satuan',
        'harga_satuan',
        'total_biaya',
        'keterangan',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori_biaya) {
            'honorarium' => 'Honorarium Tim (maks. 30%)',
            'bahan_peralatan' => 'Bahan Habis Pakai & Alat (maks. 40%)',
            'perjalanan' => 'Perjalanan & Lapangan (maks. 20%)',
            'publikasi_luaran' => 'Publikasi / Luaran (maks. 15%)',
            'lain_lain' => 'Lain-lain / Administrasi (maks. 10%)',
            default => ucfirst($this->kategori_biaya),
        };
    }
}
