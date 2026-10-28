<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Scheme;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportExportController extends Controller
{
    public function index(Request $request)
    {
        $schemes = Scheme::orderBy('tahun_anggaran', 'desc')->get();
        $query = Proposal::with(['scheme', 'user', 'members', 'reviews', 'contract', 'disbursements', 'outputs']);

        if ($request->filled('scheme_id')) {
            $query->where('scheme_id', $request->scheme_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $proposals = $query->latest()->get();

        if ($request->input('export') === 'csv') {
            return $this->exportCsv($proposals);
        }

        return view('reports.index', compact('proposals', 'schemes'));
    }

    private function exportCsv($proposals)
    {
        $filename = 'rekap_hibah_internal_arima_uss_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($proposals) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel
            fputs($file, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($file, [
                'No',
                'Tahun',
                'Skema',
                'Kategori',
                'Judul Kegiatan',
                'Ketua Pengusul',
                'NIDN Ketua',
                'Fakultas / Prodi',
                'Jumlah Anggota',
                'Dana Diusulkan (Rp)',
                'Dana Disetujui (Rp)',
                'Status Usulan',
                'Nilai Rata-rata Review',
                'No Kontrak / SPK',
                'Realisasi Cair Termin 1 (Rp)',
                'Realisasi Cair Termin 2 (Rp)',
                'Jumlah Luaran Wajib',
            ]);

            $no = 1;
            foreach ($proposals as $p) {
                $cair1 = $p->disbursements->where('termin', 'termin_1')->where('status', 'paid')->sum('nominal');
                $cair2 = $p->disbursements->where('termin', 'termin_2')->where('status', 'paid')->sum('nominal');

                fputcsv($file, [
                    $no++,
                    $p->scheme->tahun_anggaran ?? '-',
                    $p->scheme->nama_skema ?? '-',
                    ucfirst($p->kategori),
                    $p->judul,
                    $p->user->name ?? '-',
                    $p->user->nidn ?? $p->user->nip ?? '-',
                    ($p->user->fakultas ?? '-') . ' / ' . ($p->user->program_studi ?? '-'),
                    $p->members->count(),
                    $p->total_dana_usulan,
                    $p->dana_disetujui ?? 0,
                    $p->status_label,
                    $p->average_score ?? '-',
                    $p->contract->nomor_kontrak ?? '-',
                    $cair1,
                    $cair2,
                    $p->outputs->count(),
                ]);
            }
            fclose($file);
        };

        ActivityLog::log('Ekspor Data Rekap Hibah', 'Pengguna mengekspor data rekapitulasi hibah internal ke CSV/Excel');

        return response()->stream($callback, 200, $headers);
    }
}
