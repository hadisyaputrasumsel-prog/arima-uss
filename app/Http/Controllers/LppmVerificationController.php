<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\User;
use App\Models\ReviewerAssignment;
use App\Models\Contract;
use App\Models\Disbursement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LppmVerificationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!Auth::user() || !Auth::user()->isAdminLppm()) {
                    abort(403, 'Akses khusus Admin LPPM.');
                }
                return $next($request);
            }),
        ];
    }

    // 1. Verifikasi Administrasi (Tahap 3 SOP & SKPL-F-12, F-13)
    public function administrativeVerification(Request $request, Proposal $proposal)
    {
        $request->validate([
            'action' => 'required|in:pass,revision,reject',
            'catatan' => 'required_if:action,revision,reject|nullable|string',
        ]);

        $action = $request->input('action');
        $catatan = $request->input('catatan');

        if ($action === 'pass') {
            $proposal->update([
                'status' => 'admin_review',
                'catatan_administrasi' => 'Lolos verifikasi administrasi dan kelayakan format proposal.',
            ]);
            ActivityLog::log('Verifikasi Administrasi Lolos', 'Proposal lolos verifikasi administrasi', $proposal->id);
            return back()->with('success', 'Proposal dinyatakan LOLOS verifikasi administrasi. Silakan lanjutkan penugasan reviewer.');
        } elseif ($action === 'revision') {
            $proposal->update([
                'status' => 'admin_revision',
                'catatan_administrasi' => $catatan,
            ]);
            ActivityLog::log('Verifikasi Administrasi - Revisi', 'Proposal dikembalikan ke pengusul untuk revisi: ' . $catatan, $proposal->id);
            return back()->with('warning', 'Proposal dikembalikan ke pengusul untuk perbaikan administrasi.');
        } else {
            $proposal->update([
                'status' => 'rejected',
                'catatan_administrasi' => 'Ditolak pada tahap verifikasi administrasi: ' . $catatan,
            ]);
            ActivityLog::log('Verifikasi Administrasi - Ditolak', 'Proposal ditolak administrasi: ' . $catatan, $proposal->id);
            return back()->with('error', 'Proposal telah ditolak pada tahap administrasi.');
        }
    }

    // 2. Penugasan Reviewer (Tahap 4 SOP & SKPL-F-14)
    public function assignReviewer(Request $request, Proposal $proposal)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
            'catatan_penugasan' => 'nullable|string',
        ]);

        $reviewer = User::findOrFail($request->reviewer_id);

        // Conflict of Interest check: must not be the same person or same prodi (Bab 8 SOP)
        if ($reviewer->id === $proposal->user_id) {
            return back()->with('error', 'Reviewer tidak boleh merupakan ketua pengusul usulan ini!');
        }

        if (!empty($reviewer->program_studi) && $reviewer->program_studi === $proposal->user->program_studi) {
            return back()->with('error', 'Benturan Kepentingan: Reviewer berasal dari Program Studi yang sama dengan pengusul (' . $reviewer->program_studi . '). Silakan pilih reviewer lintas prodi/fakultas sesuai Bab 8 SOP.');
        }

        // Check already assigned
        $exists = ReviewerAssignment::where('proposal_id', $proposal->id)
            ->where('reviewer_id', $reviewer->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Reviewer tersebut sudah ditugaskan pada proposal ini.');
        }

        ReviewerAssignment::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'assigned_by' => Auth::id(),
            'status' => 'pending',
            'catatan_penugasan' => $request->catatan_penugasan,
        ]);

        // Transition to substantive_review if not already
        if ($proposal->status === 'admin_review') {
            $proposal->update(['status' => 'substantive_review']);
        }

        ActivityLog::log('Penugasan Reviewer', 'Menugaskan reviewer (' . $reviewer->name . ') secara blind review', $proposal->id);

        return back()->with('success', 'Reviewer internal ' . $reviewer->name . ' berhasil ditugaskan.');
    }

    public function removeReviewer(Proposal $proposal, ReviewerAssignment $assignment)
    {
        $name = $assignment->reviewer->name;
        $assignment->delete();

        ActivityLog::log('Pembatalan Penugasan Reviewer', 'Membatalkan penugasan reviewer: ' . $name, $proposal->id);

        return back()->with('success', 'Penugasan reviewer berhasil dibatalkan.');
    }

    // 3. Rapat Penetapan Pemenang (Tahap 5 SOP, SKPL-F-18, SKPL-F-19)
    public function rankingIndex()
    {
        $proposals = Proposal::whereIn('status', ['substantive_review', 'approved', 'rejected', 'contract', 'in_progress'])
            ->with(['scheme', 'user', 'reviews.reviewer'])
            ->get()
            ->sortByDesc(fn($p) => $p->average_score ?? 0);

        return view('lppm.ranking', compact('proposals'));
    }

    public function determineWinner(Request $request, Proposal $proposal)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'dana_disetujui' => 'required_if:action,approve|nullable|numeric|min:1',
            'sk_penetapan_no' => 'required_if:action,approve|nullable|string',
            'sk_penetapan_tgl' => 'required_if:action,approve|nullable|date',
        ]);

        $action = $request->input('action');

        if ($action === 'approve') {
            $danaDisetujui = $request->input('dana_disetujui', $proposal->total_dana_usulan);
            $proposal->update([
                'status' => 'approved',
                'dana_disetujui' => $danaDisetujui,
                'sk_penetapan_no' => $request->sk_penetapan_no,
                'sk_penetapan_tgl' => $request->sk_penetapan_tgl ?? now(),
            ]);

            // Auto-create draft contract
            Contract::updateOrCreate(
                ['proposal_id' => $proposal->id],
                [
                    'nomor_kontrak' => 'SPK/' . date('Y') . '/LPPM-USS/' . sprintf('%03d', $proposal->id),
                    'tanggal_kontrak' => now(),
                    'nilai_kontrak' => $danaDisetujui,
                    'status_ttd' => 'draft',
                ]
            );

            ActivityLog::log('Penetapan Pemenang Hibah', 'Proposal ditetapkan LOLOS didanai dengan alokasi Rp ' . number_format($danaDisetujui, 0, ',', '.'), $proposal->id);

            return back()->with('success', 'Proposal berhasil ditetapkan sebagai penerima hibah internal! Draf kontrak telah otomatis disiapkan.');
        } else {
            $proposal->update(['status' => 'rejected']);
            ActivityLog::log('Penetapan Hibah - Ditolak', 'Proposal tidak didanai berdasarkan hasil rapat penetapan pemenang', $proposal->id);
            return back()->with('error', 'Proposal ditetapkan TIDAK DIDANAI.');
        }
    }

    // 4. Kontrak & Penerbitan SPK (Tahap 6 & 7 SOP, SKPL-F-20, SKPL-F-22)
    public function signContractLppm(Request $request, Contract $contract)
    {
        $contract->update([
            'status_ttd' => 'active',
            'ttd_lppm_tgl' => now(),
        ]);

        $proposal = $contract->proposal;
        $proposal->update(['status' => 'contract']);

        // Auto-create Disbursement records:
        // Termin 1: 70%
        // Termin 2: 30%
        $nilai = $contract->nilai_kontrak;
        $termin1 = round($nilai * 0.70, 2);
        $termin2 = $nilai - $termin1;

        Disbursement::updateOrCreate(
            ['proposal_id' => $proposal->id, 'termin' => 'termin_1'],
            [
                'persentase' => 70,
                'nominal' => $termin1,
                'status' => 'pending',
                'catatan' => 'Kontrak telah ditandatangani LPPM. Menunggu pencairan termin 1 (70%) oleh Keuangan.',
            ]
        );

        Disbursement::updateOrCreate(
            ['proposal_id' => $proposal->id, 'termin' => 'termin_2'],
            [
                'persentase' => 30,
                'nominal' => $termin2,
                'status' => 'pending',
                'catatan' => 'Menunggu pelaksanaan monev, penyerahan laporan akhir, dan validasi luaran wajib.',
            ]
        );

        ActivityLog::log('Pengesahan Kontrak LPPM', 'LPPM menandatangani kontrak SPK. Tagihan pencairan Termin 1 dikirim ke Bagian Keuangan.', $proposal->id);

        return back()->with('success', 'Kontrak berhasil disahkan! Notifikasi dan tagihan pencairan Tahap I (70%) telah otomatis diteruskan ke Bagian Keuangan.');
    }
}
