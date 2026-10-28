<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_id',
        'user_id',
        'judul',
        'kategori',
        'bidang_fokus',
        'tkt',
        'lama_kegiatan',
        'lokasi_kegiatan',
        'total_dana_usulan',
        'dana_disetujui',
        'ringkasan',
        'status',
        'catatan_administrasi',
        'sk_penetapan_no',
        'sk_penetapan_tgl',
    ];

    protected $casts = [
        'total_dana_usulan' => 'decimal:2',
        'dana_disetujui' => 'decimal:2',
        'sk_penetapan_tgl' => 'date',
        'tkt' => 'integer',
        'lama_kegiatan' => 'integer',
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ProposalMember::class);
    }

    public function rabs(): HasMany
    {
        return $this->hasMany(ProposalRab::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProposalDocument::class);
    }

    public function reviewerAssignments(): HasMany
    {
        return $this->hasMany(ReviewerAssignment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class);
    }

    public function progressReports(): HasMany
    {
        return $this->hasMany(ProgressReport::class);
    }

    public function latestProgressReport(): HasOne
    {
        return $this->hasOne(ProgressReport::class)->latestOfMany();
    }

    public function finalReports(): HasMany
    {
        return $this->hasMany(FinalReport::class);
    }

    public function latestFinalReport(): HasOne
    {
        return $this->hasOne(FinalReport::class)->latestOfMany();
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(Output::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Check if all members confirmed
    public function areAllMembersConfirmed(): bool
    {
        $nonKetua = $this->members()->where('user_id', '!=', $this->user_id)->get();
        if ($nonKetua->isEmpty()) {
            return true;
        }
        return $nonKetua->every(fn($m) => $m->status_konfirmasi === 'approved');
    }

    // RAB analysis per category and percentage compliance according to Bab 7 SOP
    public function getRabAnalysisAttribute(): array
    {
        $total = (float) $this->total_dana_usulan;
        $grouped = $this->rabs->groupBy('kategori_biaya');

        $limits = [
            'honorarium' => 30.0,
            'bahan_peralatan' => 40.0,
            'perjalanan' => 20.0,
            'publikasi_luaran' => 15.0,
            'lain_lain' => 10.0,
        ];

        $analysis = [];
        $isValid = true;

        foreach ($limits as $cat => $maxPct) {
            $sum = $grouped->has($cat) ? (float) $grouped->get($cat)->sum('total_biaya') : 0.0;
            $pct = $total > 0 ? round(($sum / $total) * 100, 2) : 0.0;
            $passed = $pct <= $maxPct;
            if (!$passed) {
                $isValid = false;
            }

            $analysis[$cat] = [
                'nominal' => $sum,
                'persentase' => $pct,
                'maks_persentase' => $maxPct,
                'is_valid' => $passed,
            ];
        }

        return [
            'total' => $total,
            'categories' => $analysis,
            'is_all_valid' => $isValid,
        ];
    }

    // Average score from reviewers
    public function getAverageScoreAttribute(): ?float
    {
        $reviews = $this->reviews;
        if ($reviews->isEmpty()) {
            return null;
        }
        return round($reviews->avg('nilai_akhir'), 2);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draf (Penyusunan)',
            'submitted' => 'Diajukan (Menunggu Verifikasi)',
            'admin_review' => 'Verifikasi Administrasi',
            'admin_revision' => 'Revisi Administrasi',
            'substantive_review' => 'Review Substansi',
            'approved' => 'Ditetapkan (Lolos Didanai)',
            'rejected' => 'Tidak Didanai / Ditolak',
            'contract' => 'Penandatanganan Kontrak',
            'in_progress' => 'Pelaksanaan Berjalan',
            'monev' => 'Monitoring & Evaluasi',
            'final_report' => 'Laporan Akhir & Luaran',
            'completed' => 'Selesai & Arsip',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800 border-gray-300',
            'submitted' => 'bg-blue-100 text-blue-800 border-blue-300',
            'admin_review' => 'bg-amber-100 text-amber-800 border-amber-300',
            'admin_revision' => 'bg-orange-100 text-orange-800 border-orange-300',
            'substantive_review' => 'bg-purple-100 text-purple-800 border-purple-300',
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            'contract' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            'in_progress' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
            'monev' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'final_report' => 'bg-teal-100 text-teal-800 border-teal-300',
            'completed' => 'bg-green-100 text-green-800 border-green-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
