<?php

namespace App\Http\Controllers;

use App\Models\Disbursement;
use App\Models\Proposal;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FinanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $user = Auth::user();
                if (!$user || (!$user->isKeuangan() && !$user->isAdminLppm())) {
                    abort(403, 'Akses terbatas untuk Bagian Keuangan.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $pendingDisbursements = Disbursement::where('status', '!=', 'paid')
            ->with(['proposal.user', 'proposal.scheme', 'proposal.contract'])
            ->latest()
            ->get();

        $paidDisbursements = Disbursement::where('status', 'paid')
            ->with(['proposal.user', 'proposal.scheme', 'processedBy'])
            ->latest()
            ->paginate(15);

        return view('finance.index', compact('pendingDisbursements', 'paidDisbursements'));
    }

    public function process(Request $request, Disbursement $disbursement)
    {
        $validated = $request->validate([
            'tanggal_cair' => 'required|date',
            'nomor_transaksi' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'bukti_transfer' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('disbursements', 'public');
            $validated['bukti_transfer'] = $path;
        }

        $validated['status'] = 'paid';
        $validated['processed_by'] = Auth::id();

        $disbursement->update($validated);
        $proposal = $disbursement->proposal;

        if ($disbursement->termin === 'termin_1') {
            // Termin 1 paid -> Proposal moves to 'in_progress'
            $proposal->update(['status' => 'in_progress']);
            ActivityLog::log(
                'Pencairan Dana Tahap I Selesai',
                'Bagian Keuangan mencairkan Termin 1 (70%) sebesar Rp ' . number_format($disbursement->nominal, 0, ',', '.') . '. Status kegiatan kini: BERJALAN.',
                $proposal->id
            );
        } else {
            // Termin 2 paid -> Proposal moves to 'completed'
            $proposal->update(['status' => 'completed']);
            ActivityLog::log(
                'Pencairan Dana Tahap II (Pelunasan) Selesai',
                'Bagian Keuangan mencairkan Termin 2 (30%) sebesar Rp ' . number_format($disbursement->nominal, 0, ',', '.') . '. Seluruh kewajiban hibah SELESAI.',
                $proposal->id
            );
        }

        return back()->with('success', 'Pencairan ' . $disbursement->termin_label . ' sebesar Rp ' . number_format($disbursement->nominal, 0, ',', '.') . ' berhasil dicatat dan dikonfirmasi.');
    }
}
