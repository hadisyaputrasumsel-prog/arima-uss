<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'user_id',
        'nama_lengkap',
        'nidn_nip',
        'program_studi',
        'fakultas',
        'peran_tim',
        'tugas_tim',
        'status_konfirmasi',
        'tgl_konfirmasi',
    ];

    protected $casts = [
        'tgl_konfirmasi' => 'datetime',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
