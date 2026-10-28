@extends('layouts.app')

@section('title', 'Formulir Rubrik Penilaian Reviewer (Lampiran 5)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    skorMetodologi: {{ old('skor_metodologi', $existingReview->skor_metodologi ?? 4) }},
    skorRab: {{ old('skor_rab', $existingReview->skor_rab ?? 4) }},
    skorLuaran: {{ old('skor_luaran', $existingReview->skor_luaran ?? 4) }},
    skorRekamJejak: {{ old('skor_rekam_jejak', $existingReview->skor_rekam_jejak ?? 4) }},
    get totalScore() {
        let val = (0.30 * this.skorMetodologi + 0.20 * this.skorRab + 0.30 * this.skorLuaran + 0.20 * this.skorRekamJejak) * 20;
        return val.toFixed(2);
    }
}">
    <div class="flex items-center space-x-3">
        <a href="{{ route('reviews.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rubrik Penilaian Reviewer (Lampiran 5)</h1>
            <p class="text-xs text-slate-500">Evaluasi substansi proposal secara objektif & rahasia (Blind Review).</p>
        </div>
    </div>

    <!-- Proposal Summary for Reviewer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3 text-xs">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $proposal->kategori }}</span>
            <span class="text-slate-500">Skema: {{ $proposal->scheme->nama_skema }}</span>
        </div>
        <h2 class="text-base font-black text-slate-900 leading-snug">{{ $proposal->judul }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 py-2 border-y border-slate-100">
            <div><span class="text-slate-400">Bidang Fokus:</span> <strong>{{ $proposal->bidang_fokus }}</strong></div>
            <div><span class="text-slate-400">TKT:</span> <strong>{{ $proposal->tkt ? 'TKT ' . $proposal->tkt : 'N/A' }}</strong></div>
            <div><span class="text-slate-400">Usulan RAB:</span> <strong class="text-blue-700">Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}</strong></div>
        </div>
        
        <div>
            <span class="font-bold text-slate-700 block mb-1">Ringkasan Usulan:</span>
            <p class="text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $proposal->ringkasan }}</p>
        </div>

        <!-- Download Buttons for Reviewer -->
        <div class="pt-2 flex flex-wrap items-center gap-2">
            @php $substansi = $proposal->documents->where('jenis_dokumen', 'substansi_proposal')->first(); @endphp
            @if($substansi)
            <a href="{{ asset('storage/' . $substansi->file_path) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold hover:bg-blue-100 flex items-center space-x-1.5">
                <i class="fa-solid fa-file-pdf text-rose-600"></i>
                <span>Unduh Berkas Proposal Lengkap (PDF)</span>
            </a>
            @endif
            <a href="{{ route('proposals.show', $proposal) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">
                Lihat Rincian RAB & Luaran &rarr;
            </a>
        </div>
    </div>

    <!-- Review Form -->
    <form method="POST" action="{{ route('reviews.store', $proposal) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- Real-time Score Counter Card -->
        <div class="p-4 rounded-xl bg-gradient-to-r from-blue-900 to-indigo-900 text-white flex items-center justify-between shadow-md">
            <div>
                <span class="text-xs text-blue-200 block font-bold uppercase tracking-wider">Nilai Total Akhir (Bobot &times; Skor)</span>
                <p class="text-xs text-blue-300">Skala 0 s.d. 100 terhitung otomatis</p>
            </div>
            <div class="text-3xl font-black text-amber-300" x-text="totalScore"></div>
        </div>

        <!-- Rubric Criteria 1: Kualitas Metodologi (30%) -->
        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-extrabold text-slate-900 text-sm">1. Kualitas Metodologi (Bobot 30%)</span>
                    <p class="text-slate-500">Ketepatan pendekatan, kejelasan tahapan pelaksanaan, dan kelayakan teknis.</p>
                </div>
                <div class="text-right">
                    <label class="block font-bold text-slate-700 mb-1">Skor (1 - 5):</label>
                    <select name="skor_metodologi" x-model.number="skorMetodologi" required class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg font-bold text-sm">
                        <option value="1">1 - Sangat Kurang</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Baik</option>
                        <option value="5">5 - Sangat Baik</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-500 mb-1">Catatan Metodologi:</label>
                <textarea name="catatan_metodologi" rows="2" placeholder="Ulasan ketepatan metode..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg">{{ old('catatan_metodologi', $existingReview->catatan_metodologi ?? '') }}</textarea>
            </div>
        </div>

        <!-- Rubric Criteria 2: Kelayakan RAB (20%) -->
        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-extrabold text-slate-900 text-sm">2. Kelayakan Anggaran / RAB (Bobot 20%)</span>
                    <p class="text-slate-500">Kewajaran biaya, kepatuhan batas plafon persentase, dan efisiensi belanja.</p>
                </div>
                <div class="text-right">
                    <label class="block font-bold text-slate-700 mb-1">Skor (1 - 5):</label>
                    <select name="skor_rab" x-model.number="skorRab" required class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg font-bold text-sm">
                        <option value="1">1 - Sangat Kurang</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Baik</option>
                        <option value="5">5 - Sangat Baik</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-500 mb-1">Catatan Kelayakan Anggaran:</label>
                <textarea name="catatan_rab" rows="2" placeholder="Ulasan kewajaran rincian RAB..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg">{{ old('catatan_rab', $existingReview->catatan_rab ?? '') }}</textarea>
            </div>
        </div>

        <!-- Rubric Criteria 3: Potensi Luaran (30%) -->
        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-extrabold text-slate-900 text-sm">3. Potensi Ketercapaian Luaran (Bobot 30%)</span>
                    <p class="text-slate-500">Kejelasan target jurnal SINTA, HKI, modul pengabdian, dan realistis untuk dicapai.</p>
                </div>
                <div class="text-right">
                    <label class="block font-bold text-slate-700 mb-1">Skor (1 - 5):</label>
                    <select name="skor_luaran" x-model.number="skorLuaran" required class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg font-bold text-sm">
                        <option value="1">1 - Sangat Kurang</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Baik</option>
                        <option value="5">5 - Sangat Baik</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-500 mb-1">Catatan Potensi Luaran:</label>
                <textarea name="catatan_luaran" rows="2" placeholder="Ulasan target luaran..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg">{{ old('catatan_luaran', $existingReview->catatan_luaran ?? '') }}</textarea>
            </div>
        </div>

        <!-- Rubric Criteria 4: Rekam Jejak Tim (20%) -->
        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-3 text-xs">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-extrabold text-slate-900 text-sm">4. Rekam Jejak Tim Pengusul (Bobot 20%)</span>
                    <p class="text-slate-500">Relevansi keahlian, publikasi terdahulu, dan komposisi tim.</p>
                </div>
                <div class="text-right">
                    <label class="block font-bold text-slate-700 mb-1">Skor (1 - 5):</label>
                    <select name="skor_rekam_jejak" x-model.number="skorRekamJejak" required class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg font-bold text-sm">
                        <option value="1">1 - Sangat Kurang</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Baik</option>
                        <option value="5">5 - Sangat Baik</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-slate-500 mb-1">Catatan Rekam Jejak:</label>
                <textarea name="catatan_rekam_jejak" rows="2" placeholder="Ulasan rekam jejak pengusul..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg">{{ old('catatan_rekam_jejak', $existingReview->catatan_rekam_jejak ?? '') }}</textarea>
            </div>
        </div>

        <!-- Rekomendasi Akhir -->
        <div class="space-y-3 text-xs">
            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Rekomendasi Reviewer</label>
                <select name="rekomendasi" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold">
                    <option value="didanai" {{ old('rekomendasi', $existingReview->rekomendasi ?? '') === 'didanai' ? 'selected' : '' }}>Direkomendasikan Didanai Penuh</option>
                    <option value="didanai_revisi" {{ old('rekomendasi', $existingReview->rekomendasi ?? '') === 'didanai_revisi' ? 'selected' : '' }}>Direkomendasikan Didanai dengan Revisi</option>
                    <option value="tidak_didanai" {{ old('rekomendasi', $existingReview->rekomendasi ?? '') === 'tidak_didanai' ? 'selected' : '' }}>Tidak Direkomendasikan Didanai (Ditolak)</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Umum / Saran Perbaikan untuk Pengusul (Blind Review)</label>
                <textarea name="catatan_umum" rows="4" required placeholder="Tuliskan catatan komprehensif, masukan metodologi, atau revisi yang disarankan untuk pengusul..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm">{{ old('catatan_umum', $existingReview->catatan_umum ?? '') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-[11px] text-slate-400">Identitas reviewer tetap dirahasiakan oleh LPPM sesuai SOP.</span>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white font-bold text-xs shadow-md">
                Simpan Penilaian Rubrik &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
