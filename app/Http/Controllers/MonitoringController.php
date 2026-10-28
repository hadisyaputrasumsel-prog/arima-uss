<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProgressReport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Proposal::whereIn('status', ['in_progress', 'monev', 'final_report', 'completed'])
            ->with(['scheme', 'user', 'latestProgressReport'])
            ->latest();

        if ($user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        $proposals = $query->paginate(10);
        return view('monitoring.index', compact('proposals'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['scheme', 'user', 'progressReports.reviewer', 'contract', 'disbursements']);
        return view('monitoring.show', compact('proposal'));
    }

    // Submit Laporan Kemajuan (Dosen - Lampiran 6)
    public function storeProgressReport(Request $request, Proposal $proposal)
    {
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Hanya ketua pengusul yang dapat mengunggah laporan kemajuan.');
        }

        $validated = $request->validate([
            'persentase_capaian' => 'required|integer|min:1|max:100',
            'ringkasan_kemajuan' => 'required|string|max:3000',
            'dana_terpakai' => 'required|numeric|min:0',
            'sisa_dana' => 'required|numeric|min:0',
            'file_laporan' => 'required|file|mimes:pdf|max:15360',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,zip,jpg,jpeg,png|max:15360',
        ]);

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')->store('progress_reports/' . $proposal->id, 'public');
        }

        if ($request->hasFile('file_dokumentasi')) {
            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')->store('progress_reports/' . $proposal->id, 'public');
        }

        $validated['proposal_id'] = $proposal->id;
        $validated['status_monev'] = 'submitted';

        $report = ProgressReport::create($validated);
        $proposal->update(['status' => 'monev']);

        ActivityLog::log('Unggah Laporan Kemajuan', 'Pengusul mengunggah laporan kemajuan monev (' . $validated['persentase_capaian'] . '% capaian)', $proposal->id);

        return back()->with('success', 'Laporan kemajuan berhasil diunggah. Menunggu penilaian dan berita acara monev dari Tim Reviewer/LPPM.');
    }

    // Review Monev (Reviewer or LPPM - Lampiran 8)
    public function evaluateMonev(Request $request, ProgressReport $report)
    {
        $user = Auth::user();
        if (!$user->isReviewer() && !$user->isAdminLppm()) {
            abort(403);
        }

        $validated = $request->validate([
            'rekomendasi_monev' => 'required|in:lanjut_tanpa_catatan,lanjut_dengan_perbaikan,belum_dapat_diterima',
            'catatan_monev' => 'required|string|max:2000',
        ]);

        $validated['status_monev'] = $validated['rekomendasi_monev'] === 'belum_dapat_diterima' ? 'need_revision' : 'approved';
        $validated['monev_by'] = $user->id;
        $validated['tgl_monev'] = now();

        $report->update($validated);

        ActivityLog::log('Evaluasi Monev Selesai', 'Monev dievaluasi oleh ' . $user->name . ' dengan rekomendasi: ' . $report->rekomendasi_label, $report->proposal_id);

        return back()->with('success', 'Evaluasi monev berhasil dicatat dan berita acara telah diterbitkan.');
    }

    // Print Berita Acara Monev (Lampiran 8)
    public function printBeritaAcara(Proposal $proposal, ProgressReport $report)
    {
        $proposal->load(['scheme', 'user']);
        return view('monitoring.print_berita_acara', compact('proposal', 'report'));
    }
}
