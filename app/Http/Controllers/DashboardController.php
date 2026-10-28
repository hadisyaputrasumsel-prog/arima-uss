<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalMember;
use App\Models\Scheme;
use App\Models\ReviewerAssignment;
use App\Models\Disbursement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Common metrics
        $schemes = Scheme::where('is_active', true)->get();
        $recentLogs = ActivityLog::with('user', 'proposal')->latest()->take(8)->get();

        // Role-specific metrics
        $data = [
            'user' => $user,
            'schemes' => $schemes,
            'recentLogs' => $recentLogs,
        ];

        if ($user->isAdminLppm()) {
            $data['totalProposals'] = Proposal::count();
            $data['submittedProposals'] = Proposal::where('status', 'submitted')->count();
            $data['reviewProposals'] = Proposal::where('status', 'substantive_review')->count();
            $data['approvedProposals'] = Proposal::whereIn('status', ['approved', 'contract', 'in_progress', 'monev', 'final_report', 'completed'])->count();
            $data['totalFundingApproved'] = Proposal::whereNotNull('dana_disetujui')->sum('dana_disetujui');
            $data['pendingVerifications'] = Proposal::whereIn('status', ['submitted', 'admin_review'])->latest()->take(5)->get();
            $data['needReviewerAssignment'] = Proposal::where('status', 'admin_review')->whereDoesntHave('reviewerAssignments')->take(5)->get();
        } elseif ($user->isDosen()) {
            $data['myProposals'] = Proposal::where('user_id', $user->id)->with('scheme')->latest()->get();
            $data['memberInvitations'] = ProposalMember::where('user_id', $user->id)
                ->where('peran_tim', '!=', 'Ketua Pengusul')
                ->with('proposal.user', 'proposal.scheme')
                ->latest()
                ->get();
            $data['totalMyProposals'] = $data['myProposals']->count();
            $data['activeMyProposals'] = $data['myProposals']->whereIn('status', ['approved', 'contract', 'in_progress', 'monev'])->count();
        } elseif ($user->isReviewer()) {
            $data['assignedReviews'] = ReviewerAssignment::where('reviewer_id', $user->id)
                ->with('proposal.scheme', 'review')
                ->latest()
                ->get();
            $data['pendingReviewsCount'] = $data['assignedReviews']->where('status', 'pending')->count();
            $data['completedReviewsCount'] = $data['assignedReviews']->where('status', 'completed')->count();
        } elseif ($user->isKeuangan()) {
            $data['pendingDisbursements'] = Disbursement::where('status', 'pending')->with('proposal.user')->latest()->get();
            $data['paidDisbursements'] = Disbursement::where('status', 'paid')->with('proposal.user')->latest()->take(5)->get();
            $data['totalPaidAmount'] = Disbursement::where('status', 'paid')->sum('nominal');
            $data['totalPendingAmount'] = Disbursement::where('status', 'pending')->sum('nominal');
        } elseif ($user->isPimpinan()) {
            $data['totalProposals'] = Proposal::count();
            $data['approvedCount'] = Proposal::whereIn('status', ['approved', 'contract', 'in_progress', 'monev', 'final_report', 'completed'])->count();
            $data['totalDisbursed'] = Disbursement::where('status', 'paid')->sum('nominal');
            $data['facultyDistribution'] = Proposal::join('users', 'proposals.user_id', '=', 'users.id')
                ->selectRaw('users.fakultas, count(*) as total')
                ->groupBy('users.fakultas')
                ->get();
            $data['topProposals'] = Proposal::whereIn('status', ['approved', 'substantive_review'])
                ->with('user', 'scheme', 'reviews')
                ->get()
                ->sortByDesc('average_score')
                ->take(6);
        }

        return view('dashboard.index', $data);
    }
}
