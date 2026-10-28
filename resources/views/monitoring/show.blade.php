@extends('layouts.app')

@section('title', 'Detail Monev - ' . $proposal->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('monitoring.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring & Evaluasi Kemajuan (Tahap 9 SOP)</h1>
            <p class="text-xs text-slate-500">Pelaporan kemajuan pelaksanaan dan berita acara monev.</p>
        </div>
    </div>

    <!-- Proposal Header -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-2 text-xs">
        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $proposal->kategori }}</span>
        <h2 class="text-lg font-black text-slate-900 leading-snug">{{ $proposal->judul }}</h2>
        <p class="text-slate-500">
            Ketua: <strong class="text-slate-700">{{ $proposal->user->name }}</strong> &bull; Total Dana: Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}
        </p>
    </div>

    <!-- Riwayat Laporan Kemajuan & Berita Acara -->
    @if($proposal->progressReports->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">Laporan Kemajuan yang Telah Diunggah</h3>

        @foreach($proposal->progressReports as $report)
        <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-black text-blue-700">Persentase Capaian: {{ $report->persentase_capaian }}%</span>
                    <span class="px-2 py-0.5 rounded-full font-bold text-[10px] {{ $report->status_monev === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ strtoupper($report->status_monev) }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ asset('storage/' . $report->file_laporan) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-blue-600 font-bold hover:bg-slate-100">
                        <i class="fa-solid fa-file-pdf text-rose-600 mr-1"></i> Berkas Laporan
                    </a>
                    @if($report->status_monev === 'approved')
                    <a href="{{ route('monitoring.print.ba', [$proposal, $report]) }}" target="_blank" class="px-3 py-1 bg-slate-800 text-white rounded-lg font-bold hover:bg-slate-900">
                        <i class="fa-solid fa-print mr-1"></i> Berita Acara Monev (Lampiran 8)
                    </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <span class="text-slate-400 block">Dana Tahap I Terpakai:</span>
                    <strong class="text-slate-800">Rp {{ number_format($report->dana_terpakai, 0, ',', '.') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Sisa Dana:</span>
                    <strong class="text-slate-800">Rp {{ number_format($report->sisa_dana, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div>
                <span class="font-bold text-slate-700 block mb-1">Uraian Realisasi & Kendala:</span>
                <p class="text-slate-600 leading-relaxed bg-white p-3 rounded-lg border border-slate-200 whitespace-pre-line">{{ $report->ringkasan_kemajuan }}</p>
            </div>

            @if($report->rekomendasi_monev)
            <div class="p-3 bg-white rounded-lg border border-slate-200">
                <span class="font-bold text-slate-800 block mb-1">Hasil Evaluasi Tim Monev Reviewer:</span>
                <span class="px-2 py-0.5 rounded font-bold text-[10px] bg-blue-100 text-blue-800 inline-block mb-1">
                    {{ $report->rekomendasi_label }}
                </span>
                <p class="text-slate-600">{{ $report->catatan_monev }}</p>
                <span class="text-[10px] text-slate-400 block mt-1">Dievaluasi pada: {{ $report->tgl_monev ? $report->tgl_monev->format('d M Y') : '-' }}</span>
            </div>
            @endif

            <!-- Reviewer / LPPM Form Evaluasi Monev -->
            @if((Auth::user()->isReviewer() || Auth::user()->isAdminLppm()) && $report->status_monev !== 'approved')
            <div class="mt-4 pt-3 border-t border-slate-200">
                <h4 class="font-bold text-slate-800 mb-2">Form Evaluasi Monev (Lampiran 8 Berita Acara)</h4>
                <form method="POST" action="{{ route('monitoring.evaluate', $report) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Rekomendasi Tim Monev</label>
                        <select name="rekomendasi_monev" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg">
                            <option value="lanjut_tanpa_catatan">1. Kegiatan dapat dilanjutkan tanpa catatan</option>
                            <option value="lanjut_dengan_perbaikan">2. Kegiatan dapat dilanjutkan dengan perbaikan rekomendasi</option>
                            <option value="belum_dapat_diterima">3. Kegiatan/luaran belum dapat diterima (wajib diperbaiki)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Aspek Temuan & Saran</label>
                        <textarea name="catatan_monev" rows="3" required placeholder="Catatan hasil monitoring kemajuan..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-sm">
                        Simpan Evaluasi Monev
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Form Upload Laporan Kemajuan (Dosen Only) -->
    @if(Auth::id() === $proposal->user_id)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <h3 class="font-extrabold text-slate-900 text-base mb-1">Unggah Laporan Kemajuan (Lampiran 6)</h3>
        <p class="text-xs text-slate-500 mb-4">Pengusul melaporkan persentase realisasi kegiatan dan pertanggungjawaban serapan dana termin I (70%).</p>

        <form method="POST" action="{{ route('monitoring.progress.store', $proposal) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Persentase Capaian Kegiatan (%)</label>
                <input type="number" name="persentase_capaian" min="1" max="100" value="{{ old('persentase_capaian', 50) }}" required
                    class="w-full sm:w-1/3 px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl font-bold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Dana Tahap I Terpakai (Rp)</label>
                    <input type="number" name="dana_terpakai" min="0" step="100000" value="{{ old('dana_terpakai', 12000000) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sisa Dana Tahap I (Rp)</label>
                    <input type="number" name="sisa_dana" min="0" step="100000" value="{{ old('sisa_dana', 4800000) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Uraian Realisasi Kegiatan, Kendala, & Rencana Selanjutnya</label>
                <textarea name="ringkasan_kemajuan" rows="4" required placeholder="Jelaskan tahapan yang telah tercapai, kendala lapang yang dihadapi, dan tindak lanjut..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl leading-relaxed">{{ old('ringkasan_kemajuan') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Berkas Laporan Kemajuan (PDF, maks 15MB)</label>
                    <input type="file" name="file_laporan" accept=".pdf" required class="block w-full text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Dokumentasi Foto / Bukti Lapangan (PDF/ZIP, Opsional)</label>
                    <input type="file" name="file_dokumentasi" accept=".pdf,.zip,.jpg,.jpeg,.png" class="block w-full text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-slate-700 file:text-white">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md">
                    Kirim Laporan Kemajuan &rarr;
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
