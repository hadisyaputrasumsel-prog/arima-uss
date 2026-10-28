@extends('layouts.app')

@section('title', 'Laporan Akhir - ' . $proposal->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('final-reports.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Akhir & Validasi Luaran (Tahap 10 & 11)</h1>
            <p class="text-xs text-slate-500">Penyerahan bukti luaran wajib (Lampiran 7) & validasi kelayakan pelunasan dana.</p>
        </div>
    </div>

    <!-- Proposal Header -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-2 text-xs">
        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $proposal->kategori }}</span>
        <h2 class="text-lg font-black text-slate-900 leading-snug">{{ $proposal->judul }}</h2>
        <p class="text-slate-500">
            Ketua: <strong class="text-slate-700">{{ $proposal->user->name }}</strong> &bull; Total Kontrak: <strong>Rp {{ number_format($proposal->contract->nilai_kontrak ?? $proposal->total_dana_usulan, 0, ',', '.') }}</strong>
        </p>
    </div>

    <!-- Daftar Bukti Luaran Wajib -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">Status & Bukti Ketercapaian Luaran Wajib</h3>

        <div class="space-y-3">
            @foreach($proposal->outputs as $output)
            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-black text-slate-900 text-sm">{{ $output->jenis_luaran }}</span>
                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] {{ $output->is_verified ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $output->is_verified ? 'TERVALIDASI' : $output->status_capaian_label }}
                    </span>
                </div>
                <p class="text-slate-600">{{ $output->target_uraian }}</p>

                <div class="flex flex-wrap items-center gap-3 pt-1">
                    @if($output->tautan_luaran)
                    <a href="{{ $output->tautan_luaran }}" target="_blank" class="text-blue-600 font-bold hover:underline">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka Tautan Luaran
                    </a>
                    @endif

                    @if($output->bukti_file)
                    <a href="{{ asset('storage/' . $output->bukti_file) }}" target="_blank" class="text-emerald-700 font-bold hover:underline">
                        <i class="fa-solid fa-file-pdf mr-1"></i> Unduh Berkas Bukti Luaran
                    </a>
                    @endif
                </div>

                <!-- Form Update Bukti Luaran (Ketua) -->
                @if(Auth::id() === $proposal->user_id)
                <div class="pt-2 border-t border-slate-200" x-data="{ openEdit: false }">
                    <button type="button" @click="openEdit = !openEdit" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Perbarui Bukti / Tautan Luaran
                    </button>

                    <div x-show="openEdit" x-cloak class="mt-3 p-3 bg-white rounded-lg border border-slate-200">
                        <form method="POST" action="{{ route('final-reports.outputs.update', $output) }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Status Capaian Saat Ini</label>
                                <select name="status_capaian" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                                    <option value="draft" {{ $output->status_capaian === 'draft' ? 'selected' : '' }}>Draf</option>
                                    <option value="submitted" {{ $output->status_capaian === 'submitted' ? 'selected' : '' }}>Diserahkan (Submitted)</option>
                                    <option value="accepted" {{ $output->status_capaian === 'accepted' ? 'selected' : '' }}>Diterima (Accepted / Letter of Acceptance)</option>
                                    <option value="published" {{ $output->status_capaian === 'published' ? 'selected' : '' }}>Terbit / Publikasi (Published)</option>
                                    <option value="granted" {{ $output->status_capaian === 'granted' ? 'selected' : '' }}>Granted / Sertifikat HKI Terbit</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Tautan URL Publikasi / Bukti Online</label>
                                <input type="url" name="tautan_luaran" value="{{ $output->tautan_luaran }}" placeholder="https://..." class="w-full px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Unggah Berkas Bukti (PDF / Sertifikat)</label>
                                <input type="file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png,.zip" class="w-full text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                            </div>
                            <button type="submit" class="px-3.5 py-1.5 bg-blue-600 text-white font-bold rounded-lg shadow-sm">
                                Simpan Bukti Luaran
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Dokumen Laporan Akhir & Validasi -->
    @if($proposal->finalReports->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">Laporan Akhir & Validasi Kelayakan</h3>

        @foreach($proposal->finalReports as $final)
        <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 pb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-black text-slate-900">Total Realisasi Dana: Rp {{ number_format($final->total_realisasi_dana, 0, ',', '.') }}</span>
                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] 
                        @if($final->status_validasi === 'valid') bg-emerald-100 text-emerald-800 
                        @elseif($final->status_validasi === 'revision_needed') bg-rose-100 text-rose-800 
                        @else bg-amber-100 text-amber-800 @endif">
                        {{ $final->status_validasi_label }}
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ asset('storage/' . $final->file_laporan_akhir) }}" target="_blank" class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-blue-600 font-bold hover:bg-slate-100">
                        <i class="fa-solid fa-file-pdf text-rose-600 mr-1"></i> Dokumen Laporan Akhir
                    </a>
                    @if($final->status_validasi === 'valid')
                    <a href="{{ route('final-reports.print.ba', [$proposal, $final]) }}" target="_blank" class="px-3 py-1 bg-slate-800 text-white rounded-lg font-bold hover:bg-slate-900">
                        <i class="fa-solid fa-print mr-1"></i> Berita Acara Validasi (Lampiran 8)
                    </a>
                    @endif
                </div>
            </div>

            <!-- Breakdown 5 Komponen Realisasi Biaya -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-[10px]">
                <div class="p-2 bg-white rounded-lg border border-slate-200">
                    <span class="text-slate-400 block">Honorarium:</span>
                    <strong class="text-slate-800">Rp {{ number_format($final->realisasi_honorarium, 0, ',', '.') }}</strong>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200">
                    <span class="text-slate-400 block">Bahan & Alat:</span>
                    <strong class="text-slate-800">Rp {{ number_format($final->realisasi_bahan_peralatan, 0, ',', '.') }}</strong>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200">
                    <span class="text-slate-400 block">Perjalanan:</span>
                    <strong class="text-slate-800">Rp {{ number_format($final->realisasi_perjalanan, 0, ',', '.') }}</strong>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200">
                    <span class="text-slate-400 block">Publikasi/HKI:</span>
                    <strong class="text-slate-800">Rp {{ number_format($final->realisasi_publikasi, 0, ',', '.') }}</strong>
                </div>
                <div class="p-2 bg-white rounded-lg border border-slate-200">
                    <span class="text-slate-400 block">Lain-lain:</span>
                    <strong class="text-slate-800">Rp {{ number_format($final->realisasi_lain_lain, 0, ',', '.') }}</strong>
                </div>
            </div>

            @if($final->catatan_validasi)
            <div class="p-3 bg-white rounded-lg border border-slate-200">
                <span class="font-bold text-slate-800 block mb-1">Catatan Validasi LPPM / Reviewer:</span>
                <p class="text-slate-700 leading-relaxed">{{ $final->catatan_validasi }}</p>
                <span class="text-[10px] text-slate-400 block mt-1">Divalidasi oleh: {{ $final->validator->name ?? 'Tim LPPM' }}</span>
            </div>
            @endif

            <!-- Form Validasi Luaran (Reviewer / LPPM) -->
            @if((Auth::user()->isReviewer() || Auth::user()->isAdminLppm()) && $final->status_validasi !== 'valid')
            <div class="mt-4 pt-3 border-t border-slate-200">
                <h4 class="font-bold text-slate-800 mb-2">Form Validasi Luaran (Tahap 11 SOP)</h4>
                <form method="POST" action="{{ route('final-reports.validate', $final) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Keputusan Validasi Luaran</label>
                        <select name="status_validasi" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg font-bold">
                            <option value="valid">LUARAN SAH & VALID (Rekomendasikan Pelunasan Termin 2)</option>
                            <option value="revision_needed">PERLU PERBAIKAN BUKTI LUARAN (Tunda Pelunasan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catatan Pemeriksaan Berita Acara</label>
                        <textarea name="catatan_validasi" rows="3" required placeholder="Ulasan kesesuaian luaran dengan janji proposal..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg"></textarea>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm">
                        Simpan Keputusan Validasi
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Form Upload Laporan Akhir (Dosen) -->
    @if(Auth::id() === $proposal->user_id)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <h3 class="font-extrabold text-slate-900 text-base mb-1">Unggah Laporan Akhir Kegiatan (Lampiran 7)</h3>
        <p class="text-xs text-slate-500 mb-4">Pengusul menyerahkan laporan akhir hasil kegiatan dan rincian penggunaan anggaran 100%.</p>

        <form method="POST" action="{{ route('final-reports.store', $proposal) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-slate-700 mb-1">Ringkasan Hasil Kegiatan</label>
                <textarea name="ringkasan_hasil" rows="3" required placeholder="Uraikan intisari hasil kegiatan, temuan kebaruan, atau dampak pengabdian..."
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl leading-relaxed">{{ old('ringkasan_hasil') }}</textarea>
            </div>

            <!-- Realisasi 5 Komponen Biaya -->
            <div>
                <span class="font-bold text-slate-800 uppercase tracking-wider block mb-2">Realisasi Penggunaan Dana 100% (Rp)</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-slate-500 mb-1">Honorarium Tim (Rp)</label>
                        <input type="number" name="realisasi_honorarium" value="{{ old('realisasi_honorarium', 6000000) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Bahan & Peralatan (Rp)</label>
                        <input type="number" name="realisasi_bahan_peralatan" value="{{ old('realisasi_bahan_peralatan', 8000000) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Perjalanan / Lapangan (Rp)</label>
                        <input type="number" name="realisasi_perjalanan" value="{{ old('realisasi_perjalanan', 4500000) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Publikasi / Luaran (Rp)</label>
                        <input type="number" name="realisasi_publikasi" value="{{ old('realisasi_publikasi', 3500000) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Lain-lain / ATK (Rp)</label>
                        <input type="number" name="realisasi_lain_lain" value="{{ old('realisasi_lain_lain', 2000000) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Kendala, Solusi, dan Rekomendasi Keberlanjutan</label>
                <textarea name="kendala_rekomendasi" rows="2" placeholder="Catatan kendala dan prospek riset/pengabdian masa depan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl">{{ old('kendala_rekomendasi') }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Berkas Dokumen Laporan Akhir (PDF Lengkap, maks 20MB)</label>
                <input type="file" name="file_laporan_akhir" accept=".pdf" required class="block w-full text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:bg-blue-600 file:text-white">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md">
                    Kirim Laporan Akhir & Luaran &rarr;
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
