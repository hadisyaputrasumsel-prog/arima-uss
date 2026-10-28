<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ReviewerAssignment;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isReviewer() && !$user->isAdminLppm()) {
            abort(403, 'Akses terbatas untuk Reviewer.');
        }

        $assignments = ReviewerAssignment::where('reviewer_id', $user->id)
            ->with(['proposal.scheme', 'review'])
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('assignments'));
    }

    public function show(Proposal $proposal)
    {
        $user = Auth::user();
        $assignment = ReviewerAssignment::where('proposal_id', $proposal->id)
            ->where('reviewer_id', $user->id)
            ->firstOrFail();

        $proposal->load(['scheme', 'rabs', 'documents', 'outputs']);
        $existingReview = Review::where('assignment_id', $assignment->id)->first();
        $rabAnalysis = $proposal->rab_analysis;

        return view('reviews.form', compact('proposal', 'assignment', 'existingReview', 'rabAnalysis'));
    }

    public function store(Request $request, Proposal $proposal)
    {
        $user = Auth::user();
        $assignment = ReviewerAssignment::where('proposal_id', $proposal->id)
            ->where('reviewer_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'skor_metodologi' => 'required|integer|min:1|max:5',
            'catatan_metodologi' => 'nullable|string',
            'skor_rab' => 'required|integer|min:1|max:5',
            'catatan_rab' => 'nullable|string',
            'skor_luaran' => 'required|integer|min:1|max:5',
            'catatan_luaran' => 'nullable|string',
            'skor_rekam_jejak' => 'required|integer|min:1|max:5',
            'catatan_rekam_jejak' => 'nullable|string',
            'rekomendasi' => 'required|in:didanai,didanai_revisi,tidak_didanai',
            'catatan_umum' => 'required|string|max:2000',
        ]);

        $score = Review::calculateScore(
            $validated['skor_metodologi'],
            $validated['skor_rab'],
            $validated['skor_luaran'],
            $validated['skor_rekam_jejak']
        );

        $validated['assignment_id'] = $assignment->id;
        $validated['proposal_id'] = $proposal->id;
        $validated['reviewer_id'] = $user->id;
        $validated['nilai_akhir'] = $score;

        Review::updateOrCreate(
            ['assignment_id' => $assignment->id],
            $validated
        );

        $assignment->update(['status' => 'completed']);

        ActivityLog::log('Penilaian Substansi Selesai', 'Reviewer menyelesaikan penilaian rubrik dengan skor: ' . $score, $proposal->id);

        return redirect()->route('reviews.index')->with('success', 'Penilaian substansi berhasil disimpan dengan nilai akhir: ' . $score . ' / 100.');
    }

    // Print rubrik penilaian (Lampiran 5)
    public function printRubrik(Proposal $proposal, Review $review)
    {
        $proposal->load(['scheme', 'user']);
        return view('reviews.print_rubrik', compact('proposal', 'review'));
    }
}
