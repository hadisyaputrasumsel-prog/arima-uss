<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\FinalReport;
use App\Models\Output;
use App\Models\Disbursement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Proposal::whereIn('status', ['in_progress', 'monev', 'final_report', 'completed'])
            ->with(['scheme', 'user', 'latestFinalReport', 'outputs'])
            ->latest();

        if ($user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        $proposals = $query->paginate(10);
        return view('final_reports.index', compact('proposals'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['scheme', 'user', 'finalReports.validator', 'outputs', 'contract', 'disbursements']);
        return view('final_reports.show', compact('proposal'));
    }

    // Submit Laporan Akhir (Dosen - Lampiran 7)
    public function store(Request $request, Proposal $proposal)
    {
        if ($proposal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'ringkasan_hasil' => 'required|string|max:3000',
            'realisasi_honorarium' => 'required|numeric|min:0',
            'realisasi_bahan_peralatan' => 'required|numeric|min:0',
            'realisasi_perjalanan' => 'required|numeric|min:0',
            'realisasi_publikasi' => 'required|numeric|min:0',
            'realisasi_lain_lain' => 'required|numeric|min:0',
            'kendala_rekomendasi' => 'nullable|string|max:2000',
            'file_laporan_akhir' => 'required|file|mimes:pdf|max:20480', // max 20MB
            'file_sptb' => 'nullable|file|mimes:pdf|max:15360',
        ]);

        $totalRealisasi = $validated['realisasi_honorarium']
            + $validated['realisasi_bahan_peralatan']
            + $validated['realisasi_perjalanan']
            + $validated['realisasi_publikasi']
            + $validated['realisasi_lain_lain'];

        $validated['total_realisasi_dana'] = $totalRealisasi;
        $validated['proposal_id'] = $proposal->id;
        $validated['status_validasi'] = 'submitted';

        if ($request->hasFile('file_laporan_akhir')) {
            $validated['file_laporan_akhir'] = $request->file('file_laporan_akhir')->store('final_reports/' . $proposal->id, 'public');
        }

        if ($request->hasFile('file_sptb')) {
            $validated['file_sptb'] = $request->file('file_sptb')->store('final_reports/' . $proposal->id, 'public');
        }

        FinalReport::create($validated);
        $proposal->update(['status' => 'final_report']);

        ActivityLog::log('Unggah Laporan Akhir & Luaran', 'Pengusul mengunggah dokumen laporan akhir kegiatan dan bukti luaran', $proposal->id);

        return back()->with('success', 'Laporan akhir dan rekapitulasi realisasi dana berhasil diserahkan. Menunggu validasi luaran oleh Reviewer / LPPM.');
    }

    // Update Output proof by Dosen
    public function updateOutputProof(Request $request, Output $output)
    {
        if ($output->proposal->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status_capaian' => 'required|in:draft,submitted,accepted,published,granted',
            'tautan_luaran' => 'nullable|url|max:500',
            'keterangan' => 'nullable|string|max:500',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:15360',
        ]);

        if ($request->hasFile('bukti_file')) {
            $validated['bukti_file'] = $request->file('bukti_file')->store('outputs/' . $output->proposal_id, 'public');
        }

        $output->update($validated);

        ActivityLog::log('Pembaruan Bukti Luaran', 'Pengusul memperbarui bukti luaran: ' . $output->jenis_luaran, $output->proposal_id);

        return back()->with('success', 'Bukti luaran berhasil diperbarui.');
    }

    // Validasi Luaran oleh Reviewer / LPPM (Tahap 11 SOP & SKPL-F-28)
    public function validateLuaran(Request $request, FinalReport $report)
    {
        $user = Auth::user();
        if (!$user->isReviewer() && !$user->isAdminLppm()) {
            abort(403);
        }

        $validated = $request->validate([
            'status_validasi' => 'required|in:valid,revision_needed',
            'catatan_validasi' => 'required|string|max:2000',
        ]);

        $validated['validated_by'] = $user->id;
        $validated['validated_at'] = now();

        $report->update($validated);
        $proposal = $report->proposal;

        if ($validated['status_validasi'] === 'valid') {
            // Verify all outputs
            $proposal->outputs()->update(['is_verified' => true]);

            // Notify Keuangan that Termin 2 is now ready for payout
            $termin2 = Disbursement::where('proposal_id', $proposal->id)->where('termin', 'termin_2')->first();
            if ($termin2) {
                $termin2->update(['status' => 'verified']);
            }

            ActivityLog::log(
                'Validasi Luaran Berhasil',
                'Luaran wajib dinyatakan SAH & VALID oleh ' . $user->name . '. Tagihan pelunasan Termin 2 (30%) diteruskan ke Keuangan.',
                $proposal->id
            );

            return back()->with('success', 'Luaran dinyatakan SAH & VALID! Rekomendasi pelunasan Termin 2 (30%) telah diteruskan ke Bagian Keuangan.');
        } else {
            ActivityLog::log(
                'Validasi Luaran Memerlukan Perbaikan',
                'Reviewer/LPPM meminta perbaikan bukti luaran: ' . $validated['catatan_validasi'],
                $proposal->id
            );

            return back()->with('warning', 'Catatan perbaikan luaran telah disampaikan kepada pengusul.');
        }
    }

    // Print Berita Acara Validasi Luaran (Lampiran 8)
    public function printValidasi(Proposal $proposal, FinalReport $report)
    {
        $proposal->load(['scheme', 'user', 'outputs']);
        return view('final_reports.print_validasi', compact('proposal', 'report'));
    }

    // Print / Generate Lembar SPTB Akhir (100%)
    public function printSptb(Proposal $proposal)
    {
        $proposal->load(['scheme', 'user', 'latestFinalReport', 'contract']);
        $totalDana = $proposal->dana_disetujui ?? $proposal->total_dana_usulan;
        $report = $proposal->latestFinalReport;

        return view('final_reports.print_sptb', compact('proposal', 'report', 'totalDana'));
    }
}
