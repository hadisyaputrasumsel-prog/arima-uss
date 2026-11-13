@extends('layouts.app')

@section('title', $proposal->judul)

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'identitas' }">
    
    <!-- Top Action & Info Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs font-extrabold uppercase px-2.5 py-0.5 rounded {{ $proposal->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $proposal->kategori }}
                    </span>
                    <span class="text-xs px-3 py-0.5 rounded-full font-bold border {{ $proposal->status_badge_class }}">
                        <i class="fa-solid fa-circle-dot mr-1 text-[9px]"></i> {{ $proposal->status_label }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">&bull; Skema: {{ $proposal->scheme->nama_skema }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">{{ $proposal->judul }}</h1>
                <p class="text-xs text-slate-500 mt-2">
                    Ketua Pengusul: <strong class="text-slate-800">{{ $proposal->user->name }}</strong> ({{ $proposal->user->nidn ?? $proposal->user->nip ?? '-' }}) &bull;
                    Unit: {{ $proposal->user->program_studi ?? $proposal->user->fakultas }} &bull;
                    Total RAB: <strong class="text-blue-700">Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}</strong>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('proposals.print.formulir', $proposal) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Formulir (Lampiran 1)</span>
                </a>

                @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
                <form method="POST" action="{{ route('proposals.submit', $proposal) }}" onsubmit="return confirm('Apakah Anda yakin seluruh berkas, anggota tim, dan RAB telah lengkap dan sesuai ketentuan?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 flex items-center space-x-1.5">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Ajukan ke LPPM</span>
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- 13 Stages Visual Progress Tracker -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alur Tahapan Hibah Internal (Adaptasi BIMA):</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2 text-center text-[10px]">
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['draft']) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                    1. Draf
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['submitted', 'admin_review', 'admin_revision']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['draft']) ? 'bg-slate-100 text-slate-400' : 'bg-emerald-100 text-emerald-800') }}">
                    2. Verifikasi Adm
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['substantive_review']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['draft', 'submitted', 'admin_review', 'admin_revision']) ? 'bg-slate-100 text-slate-400' : 'bg-emerald-100 text-emerald-800') }}">
                    3. Review Substansi
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['approved']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['rejected']) ? 'bg-rose-100 text-rose-800' : (in_array($proposal->status, ['contract', 'in_progress', 'monev', 'final_report', 'completed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400')) }}">
                    4. Penetapan Pemenang
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['contract']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['in_progress', 'monev', 'final_report', 'completed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400') }}">
                    5. Kontrak & Termin I (70%)
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['in_progress', 'monev']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['final_report', 'completed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400') }}">
                    6. Pelaksanaan & Monev
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['final_report']) ? 'bg-blue-600 text-white' : (in_array($proposal->status, ['completed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-400') }}">
                    7. Laporan Akhir & Luaran
                </div>
                <div class="p-2 rounded-lg font-bold {{ in_array($proposal->status, ['completed']) ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                    8. Pelunasan & Selesai
                </div>
            </div>
        </div>
    </div>

    <!-- ADMIN LPPM ACTION PANEL (If user is LPPM) -->
    @if(Auth::user()->isAdminLppm())
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl border border-amber-200 p-6 shadow-sm">
        <h3 class="font-extrabold text-amber-900 text-sm flex items-center mb-4">
            <i class="fa-solid fa-shield-halved text-amber-600 mr-2"></i>
            Panel Kendali Admin LPPM (Verifikasi & Tata Kelola Usulan)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Verifikasi Administrasi -->
            <div class="bg-white p-4 rounded-xl border border-amber-200 text-xs">
                <h4 class="font-bold text-slate-800 mb-2">1. Verifikasi Administrasi (Tahap 3)</h4>
                @if(in_array($proposal->status, ['submitted', 'admin_review', 'admin_revision']))
                <form method="POST" action="{{ route('lppm.verify.administrative', $proposal) }}" class="space-y-3">
                    @csrf
                    <div>
                        <select name="action" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-semibold">
                            <option value="pass">Lolos Verifikasi Administrasi</option>
                            <option value="revision">Kembalikan untuk Revisi</option>
                            <option value="reject">Tolak Usulan Administrasi</option>
                        </select>
                    </div>
                    <div>
                        <textarea name="catatan" rows="2" placeholder="Catatan checklist kelengkapan..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg shadow-sm">
                        Simpan Verifikasi
                    </button>
                </form>
                @else
                <p class="text-slate-500">Status saat ini: <strong class="text-slate-800">{{ $proposal->status_label }}</strong></p>
                @endif
            </div>

            <!-- 2. Plotting Reviewer Internal (Cross-Faculty Rule) -->
            <div class="bg-white p-4 rounded-xl border border-amber-200 text-xs">
                <h4 class="font-bold text-slate-800 mb-2">2. Plotting Reviewer Internal (Tahap 4)</h4>
                <p class="text-[11px] text-slate-500 mb-2">Minimal 2 reviewer lintas fakultas/prodi (blind review).</p>
                <form method="POST" action="{{ route('lppm.reviewers.assign', $proposal) }}" class="space-y-3">
                    @csrf
                    <div>
                        <select name="reviewer_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs">
                            <option value="">-- Pilih Dosen Reviewer --</option>
                            @foreach($allDosen as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->fakultas ?? 'USS' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-sm">
                        Tugaskan Reviewer
                    </button>
                </form>
                <div class="mt-2 text-[11px] text-slate-500">
                    Reviewer ditugaskan: <strong>{{ $proposal->reviewerAssignments->count() }} orang</strong>
                </div>
            </div>

            <!-- 3. Kontrak & SPK (Tahap 6) -->
            <div class="bg-white p-4 rounded-xl border border-amber-200 text-xs">
                <h4 class="font-bold text-slate-800 mb-2">3. Kontrak & Surat Penugasan (Tahap 6)</h4>
                @if($proposal->contract)
                    <p class="text-slate-600 mb-1">No SPK: <strong>{{ $proposal->contract->nomor_kontrak }}</strong></p>
                    <p class="text-slate-600 mb-2">Status: <strong>{{ strtoupper($proposal->contract->status_ttd) }}</strong></p>
                    @if($proposal->contract->status_ttd !== 'active')
                    <form method="POST" action="{{ route('lppm.contracts.sign', $proposal->contract) }}">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-sm">
                            Sahkan SPK & Tagih Termin I
                        </button>
                    </form>
                    @else
                    <span class="inline-block px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 font-bold">SPK Telah Aktif & Disahkan</span>
                    @endif
                @else
                    <p class="text-slate-400">Kontrak dibuat setelah penetapan pemenang disahkan.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Tab Navigation -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-2 sm:space-x-4 overflow-x-auto text-xs font-bold">
            <button @click="activeTab = 'identitas'" :class="activeTab === 'identitas' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-info-circle mr-1.5"></i> Identitas Usulan
            </button>
            <button @click="activeTab = 'anggota'" :class="activeTab === 'anggota' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-users mr-1.5"></i> Tim Pengusul ({{ $proposal->members->count() }})
            </button>
            <button @click="activeTab = 'rab'" :class="activeTab === 'rab' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-calculator mr-1.5"></i> Rencana Anggaran (RAB)
            </button>
            <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-file-pdf mr-1.5"></i> Dokumen Substansi & Mitra
            </button>
            <button @click="activeTab = 'luaran'" :class="activeTab === 'luaran' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-bullseye mr-1.5"></i> Target Luaran ({{ $proposal->outputs->count() }})
            </button>
            <button @click="activeTab = 'review'" :class="activeTab === 'review' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-star-half-stroke mr-1.5"></i> Penilaian Reviewer ({{ $proposal->reviews->count() }})
            </button>
            <button @click="activeTab = 'keuangan'" :class="activeTab === 'keuangan' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                class="py-3 px-3 border-b-2 whitespace-nowrap transition">
                <i class="fa-solid fa-coins mr-1.5"></i> Pencairan Dana Termin
            </button>
        </nav>
    </div>

    <!-- TAB 1: IDENTITAS USULAN -->
    <div x-show="activeTab === 'identitas'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-2">Informasi Rinci Usulan</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Skema & Kategori:</span>
                    <strong class="text-slate-800 text-sm">{{ $proposal->scheme->nama_skema }} ({{ ucfirst($proposal->kategori) }})</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Bidang Fokus / Rumpun:</span>
                    <strong class="text-slate-800 text-sm">{{ $proposal->bidang_fokus }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Tingkat Kesiapan Teknologi (TKT):</span>
                    <strong class="text-slate-800 text-sm">{{ $proposal->tkt ? 'TKT ' . $proposal->tkt : 'N/A (Pengabdian)' }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Lama Kegiatan:</span>
                    <strong class="text-slate-800 text-sm">{{ $proposal->lama_kegiatan }} Bulan</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Lokasi Pelaksanaan:</span>
                    <strong class="text-slate-800 text-sm">{{ $proposal->lokasi_kegiatan }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <span class="text-slate-400 block text-[11px]">Total Anggaran Diusulkan:</span>
                    <strong class="text-blue-700 text-sm">Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-800 text-xs mb-1.5 uppercase tracking-wider text-slate-400">Ringkasan Usulan</h4>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $proposal->ringkasan }}
                </div>
            </div>

            @if($proposal->catatan_administrasi)
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs">
                <span class="font-bold text-amber-800 block mb-1">Catatan Verifikasi Administrasi LPPM:</span>
                <p class="text-amber-900">{{ $proposal->catatan_administrasi }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 2: ANGGOTA TIM PENGUSUL (SKPL-F-10 & F-11) -->
    <div x-show="activeTab === 'anggota'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Susunan Anggota Tim Peneliti / Pengabdi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Sesuai SOP, seluruh anggota tim wajib mengonfirmasi persetujuannya sebelum proposal dapat diajukan.</p>
                </div>
            </div>

            <!-- Members Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                            <th class="py-2.5 px-3">Nama Lengkap & NIDN</th>
                            <th class="py-2.5 px-3">Peran dalam Tim</th>
                            <th class="py-2.5 px-3">Program Studi / Fakultas</th>
                            <th class="py-2.5 px-3">Tugas dalam Tim</th>
                            <th class="py-2.5 px-3">Status Konfirmasi</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($proposal->members as $member)
                        <tr>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800">{{ $member->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-400">NIDN: {{ $member->nidn_nip ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-700">{{ $member->peran_tim }}</td>
                            <td class="py-3 px-3 text-slate-600">{{ $member->program_studi }} / {{ $member->fakultas }}</td>
                            <td class="py-3 px-3 max-w-xs text-slate-600">{{ $member->tugas_tim }}</td>
                            <td class="py-3 px-3">
                                @if($member->status_konfirmasi === 'approved')
                                    <span class="px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[10px]">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($member->status_konfirmasi === 'rejected')
                                    <span class="px-2 py-0.5 rounded-full font-bold bg-rose-100 text-rose-800 text-[10px]">
                                        <i class="fa-solid fa-xmark mr-1"></i> Menolak
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 text-[10px]">
                                        <i class="fa-solid fa-clock mr-1"></i> Menunggu Konfirmasi
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right">
                                @if(Auth::id() === $proposal->user_id && $member->peran_tim !== 'Ketua Pengusul' && in_array($proposal->status, ['draft', 'admin_revision']))
                                <form method="POST" action="{{ route('proposals.members.remove', [$proposal, $member]) }}" onsubmit="return confirm('Hapus anggota tim ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 p-1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Form Add Member (Ketua Only, during draft) -->
            @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
            <div class="mt-6 pt-5 border-t border-slate-100">
                <h4 class="font-bold text-xs uppercase text-slate-700 tracking-wider mb-3">Undang Anggota Tim Baru</h4>
                <form method="POST" action="{{ route('proposals.members.add', $proposal) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    @csrf
                    <div class="sm:col-span-4">
                        <label class="block text-slate-500 mb-1">Pilih Dosen USS (Kolega)</label>
                        <select name="user_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                            <option value="">-- Pilih Dosen Anggota --</option>
                            @foreach($allDosen as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->program_studi ?? $d->fakultas }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-slate-500 mb-1">Peran dalam Tim</label>
                        <select name="peran_tim" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                            <option value="Anggota Dosen 1">Anggota Dosen 1</option>
                            <option value="Anggota Dosen 2">Anggota Dosen 2</option>
                            <option value="Anggota Mahasiswa">Anggota Mahasiswa</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-slate-500 mb-1">Uraian Tugas Utama</label>
                        <input type="text" name="tugas_tim" required placeholder="Uraian tugas anggota tim..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm">
                            + Tambah
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 3: RENCANA ANGGARAN BIAYA (RAB) & VALIDASI BATAS PERSENTASE -->
    <div x-show="activeTab === 'rab'" class="space-y-6">
        <!-- Visual Percentage Meters according to Bab 7 SOP -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Pemeriksaan Batas Komponen Biaya (Bab 7 SOP)</h3>
            <p class="text-xs text-slate-500 mb-4">Total anggaran setiap komponen tidak boleh melampaui plafon persentase maksimal terhadap total usulan.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                @php
                    $cats = [
                        'honorarium' => ['label' => 'Honorarium Tim', 'max' => 30],
                        'bahan_peralatan' => ['label' => 'Bahan Habis Pakai', 'max' => 40],
                        'perjalanan' => ['label' => 'Perjalanan Lapang', 'max' => 20],
                        'publikasi_luaran' => ['label' => 'Publikasi / HKI', 'max' => 15],
                        'lain_lain' => ['label' => 'Lain-lain / ATK', 'max' => 10],
                    ];
                @endphp
                @foreach($cats as $ck => $cv)
                    @php
                        $cData = $rabAnalysis['categories'][$ck] ?? ['nominal' => 0, 'persentase' => 0, 'is_valid' => true];
                        $isExceeded = !$cData['is_valid'];
                    @endphp
                    <div class="p-3.5 rounded-xl border {{ $isExceeded ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50' }} text-xs">
                        <div class="flex items-center justify-between font-bold text-slate-700 mb-1">
                            <span>{{ $cv['label'] }}</span>
                            <span class="{{ $isExceeded ? 'text-rose-600' : 'text-slate-400' }}">Maks {{ $cv['max'] }}%</span>
                        </div>
                        <div class="text-base font-black {{ $isExceeded ? 'text-rose-700' : 'text-slate-900' }}">
                            {{ $cData['persentase'] }}%
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="h-full {{ $isExceeded ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ min($cData['persentase'], 100) }}%"></div>
                        </div>
                        <div class="mt-2 text-[11px] text-slate-500">
                            Rp {{ number_format($cData['nominal'], 0, ',', '.') }}
                        </div>
                        @if($isExceeded)
                            <div class="mt-1 text-[10px] font-bold text-rose-600 flex items-center">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Melampaui Batas!
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if(!$rabAnalysis['is_all_valid'])
            <div class="mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Terdapat komponen anggaran yang melebihi batas persentase maksimal. Usulan belum dapat dikirimkan ke LPPM.</span>
            </div>
            @endif
        </div>

        <!-- Rincian Item RAB -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Rincian Anggaran Biaya (Formulir RAB Lampiran 3)</h3>
                    <p class="text-xs text-slate-500">Total Keseluruhan Dana: <strong class="text-blue-700 text-sm">Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}</strong></p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                            <th class="py-2.5 px-3">Komponen Biaya</th>
                            <th class="py-2.5 px-3">Uraian / Item Biaya</th>
                            <th class="py-2.5 px-3 text-center">Volume</th>
                            <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                            <th class="py-2.5 px-3 text-right">Total Biaya</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($proposal->rabs as $rab)
                        <tr>
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded font-bold text-[10px] bg-slate-100 text-slate-700">
                                    {{ $rab->kategori_label }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800">{{ $rab->item_biaya }}</div>
                                <div class="text-[11px] text-slate-400">{{ $rab->keterangan }}</div>
                            </td>
                            <td class="py-3 px-3 text-center whitespace-nowrap">{{ $rab->volume }} {{ $rab->satuan }}</td>
                            <td class="py-3 px-3 text-right whitespace-nowrap text-slate-600">Rp {{ number_format($rab->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right whitespace-nowrap font-bold text-slate-900">Rp {{ number_format($rab->total_biaya, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right">
                                @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
                                <form method="POST" action="{{ route('proposals.rabs.remove', [$proposal, $rab]) }}" onsubmit="return confirm('Hapus item RAB ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 p-1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada rincian anggaran yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Form Add Item RAB (Ketua only) -->
            @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
            <div class="mt-6 pt-5 border-t border-slate-100">
                <h4 class="font-bold text-xs uppercase text-slate-700 tracking-wider mb-3">Tambah Item Anggaran Baru</h4>
                <form method="POST" action="{{ route('proposals.rabs.add', $proposal) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    @csrf
                    <div class="sm:col-span-3">
                        <label class="block text-slate-500 mb-1">Kelompok Biaya</label>
                        <select name="kategori_biaya" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                            <option value="honorarium">1. Honorarium Tim (maks 30%)</option>
                            <option value="bahan_peralatan">2. Bahan Habis Pakai (maks 40%)</option>
                            <option value="perjalanan">3. Perjalanan Lapangan (maks 20%)</option>
                            <option value="publikasi_luaran">4. Publikasi/Luaran (maks 15%)</option>
                            <option value="lain_lain">5. Lain-lain/ATK (maks 10%)</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-slate-500 mb-1">Item / Uraian</label>
                        <input type="text" name="item_biaya" required placeholder="Uraian belanja..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="block text-slate-500 mb-1">Vol</label>
                        <input type="number" name="volume" step="0.1" value="1" min="0.1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="block text-slate-500 mb-1">Satuan</label>
                        <input type="text" name="satuan" placeholder="Bln/Pkt" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-slate-500 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" name="harga_satuan" step="1000" min="1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm">
                            + Tambah RAB
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 4: DOKUMEN SUBSTANSI & MITRA -->
    <div x-show="activeTab === 'dokumen'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Dokumen Pendukung Proposal</h3>
            <p class="text-xs text-slate-500 mb-4">Unggah dokumen format PDF sesuai dengan template resmi LPPM USS (Lampiran 2 & 4).</p>

            <!-- Download Official Template Banner -->
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-file-word text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Template Dokumen Resmi LPPM USS (.docx)</h4>
                        <p class="text-[11px] text-slate-600 mt-0.5">Format baku penyusunan Dokumen Substansi Usulan (Lampiran 2), Surat Kesediaan Mitra (Lampiran 4), serta rincian panduan SOP.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="{{ asset('templates/Lampiran_Formulir_Template_Hibah_Internal.docx') }}" download class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh Template (.docx)</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Substansi Usulan (Wajib) -->
                <div class="p-5 rounded-xl border border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-slate-800 text-sm">Dokumen Substansi Proposal (PDF)</span>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">Wajib</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Mengikuti sistematika: Sampul, Pengesahan, Ringkasan, Pendahuluan, Tinjauan Pustaka, Metode, Jadwal, Daftar Pustaka.</p>
                    
                    @php
                        $substansiDoc = $proposal->documents->where('jenis_dokumen', 'substansi_proposal')->first();
                    @endphp

                    @if($substansiDoc)
                    <div class="p-3 bg-white rounded-lg border border-slate-200 flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2 text-xs truncate">
                            <i class="fa-solid fa-file-pdf text-rose-600 text-lg"></i>
                            <span class="font-semibold text-slate-700 truncate">{{ $substansiDoc->file_name }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $substansiDoc->file_path) }}" target="_blank" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-md font-bold text-xs hover:bg-blue-100">
                            Unduh
                        </a>
                    </div>
                    @endif

                    @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
                    <form method="POST" action="{{ route('proposals.documents.upload', $proposal) }}" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <input type="hidden" name="jenis_dokumen" value="substansi_proposal">
                        <input type="file" name="dokumen_file" accept=".pdf" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        <button type="submit" class="w-full py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-lg shadow-sm">
                            Unggah Dokumen Substansi
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Surat Kesediaan Mitra (Khusus Pengabdian) -->
                <div class="p-5 rounded-xl border border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-slate-800 text-sm">Surat Kesediaan Mitra (Bermaterai)</span>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $proposal->kategori === 'pengabdian' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $proposal->kategori === 'pengabdian' ? 'Wajib Pengabdian' : 'Opsional' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Lampiran 4: Surat pernyataan mitra bersedia berpartisipasi dan memfasilitasi program pengabdian.</p>

                    @php
                        $mitraDoc = $proposal->documents->where('jenis_dokumen', 'surat_mitra')->first();
                    @endphp

                    @if($mitraDoc)
                    <div class="p-3 bg-white rounded-lg border border-slate-200 flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2 text-xs truncate">
                            <i class="fa-solid fa-file-pdf text-rose-600 text-lg"></i>
                            <span class="font-semibold text-slate-700 truncate">{{ $mitraDoc->file_name }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $mitraDoc->file_path) }}" target="_blank" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-md font-bold text-xs hover:bg-blue-100">
                            Unduh
                        </a>
                    </div>
                    @endif

                    @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
                    <form method="POST" action="{{ route('proposals.documents.upload', $proposal) }}" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <input type="hidden" name="jenis_dokumen" value="surat_mitra">
                        <input type="file" name="dokumen_file" accept=".pdf" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        <button type="submit" class="w-full py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-lg shadow-sm">
                            Unggah Surat Mitra
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 5: TARGET LUARAN WAJIB -->
    <div x-show="activeTab === 'luaran'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Target Luaran Kegiatan yang Dijanjikan</h3>
            <p class="text-xs text-slate-500 mb-4">Luaran ini wajib dipenuhi dan akan divalidasi oleh Tim Reviewer/LPPM sebelum pencairan tahap akhir (pelunasan 30%).</p>

            <div class="space-y-3">
                @forelse($proposal->outputs as $out)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-extrabold text-slate-900 text-sm block">{{ $out->jenis_luaran }}</span>
                        <p class="text-slate-600 mt-1">{{ $out->target_uraian }}</p>
                        @if($out->tautan_luaran)
                        <a href="{{ $out->tautan_luaran }}" target="_blank" class="text-blue-600 font-semibold hover:underline mt-1 inline-block">
                            <i class="fa-solid fa-link text-[10px] mr-1"></i> Tautan Bukti: {{ $out->tautan_luaran }}
                        </a>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $out->is_verified ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $out->is_verified ? 'Valid & Diverifikasi' : $out->status_capaian_label }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada target luaran yang dicantumkan.</p>
                @endforelse
            </div>

            @if(Auth::id() === $proposal->user_id && in_array($proposal->status, ['draft', 'admin_revision']))
            <div class="mt-6 pt-5 border-t border-slate-100">
                <h4 class="font-bold text-xs uppercase text-slate-700 tracking-wider mb-3">Tambah Target Luaran Wajib</h4>
                <form method="POST" action="{{ route('proposals.outputs.add', $proposal) }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    @csrf
                    <div class="sm:col-span-4">
                        <label class="block text-slate-500 mb-1">Jenis Luaran</label>
                        <select name="jenis_luaran" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                            <option value="Artikel di Jurnal Nasional Terakreditasi (SINTA)">Artikel di Jurnal Nasional Terakreditasi (SINTA)</option>
                            <option value="Sertifikat Hak Cipta / HKI">Sertifikat Hak Cipta / HKI</option>
                            <option value="Modul / Panduan Praktis Pengabdian">Modul / Panduan Praktis Pengabdian</option>
                            <option value="Buku Ajar / Monograf Ber-ISBN">Buku Ajar / Monograf Ber-ISBN</option>
                            <option value="Prototipe Produk / Hasil Uji Coba">Prototipe Produk / Hasil Uji Coba</option>
                        </select>
                    </div>
                    <div class="sm:col-span-6">
                        <label class="block text-slate-500 mb-1">Rencana Target Spesifik</label>
                        <input type="text" name="target_uraian" required placeholder="Contoh: Submit ke Jurnal Rekayasa SINTA 2 dengan status Under Review" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    </div>
                    <div class="sm:col-span-2 flex items-end">
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm">
                            + Tambah
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- TAB 6: PENILAIAN REVIEWER (BLIND REVIEW) -->
    <div x-show="activeTab === 'review'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Hasil Evaluasi & Penilaian Reviewer</h3>
            <p class="text-xs text-slate-500 mb-4">Sesuai SOP Bab 8, proses penilaian dilakukan secara <em>Blind Review</em> (identitas reviewer dirahasiakan dari pengusul).</p>

            @if($proposal->reviews->isEmpty())
                <div class="py-8 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-hourglass-half text-2xl mb-2 text-slate-300"></i>
                    <p>Usulan ini sedang dalam proses review substansi oleh Reviewer Internal.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($proposal->reviews as $idx => $rev)
                    <div class="p-5 rounded-xl border border-slate-200 bg-slate-50 text-xs space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <span class="font-bold text-slate-800 text-sm">
                                Reviewer #{{ $idx + 1 }} 
                                @if(Auth::user()->isAdminLppm())
                                    <span class="text-slate-500 font-normal">({{ $rev->reviewer->name }})</span>
                                @endif
                            </span>
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-0.5 rounded-full font-bold bg-blue-100 text-blue-800">
                                    Skor Akhir: {{ $rev->nilai_akhir }} / 100
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full font-bold 
                                    @if($rev->rekomendasi === 'didanai') bg-emerald-100 text-emerald-800 
                                    @elseif($rev->rekomendasi === 'didanai_revisi') bg-amber-100 text-amber-800 
                                    @else bg-rose-100 text-rose-800 @endif">
                                    {{ $rev->rekomendasi_label }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-[11px]">
                            <div class="p-2 bg-white rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[10px]">Metodologi (30%)</span>
                                <strong class="text-slate-800 text-sm">{{ $rev->skor_metodologi }} / 5</strong>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[10px]">Kelayakan RAB (20%)</span>
                                <strong class="text-slate-800 text-sm">{{ $rev->skor_rab }} / 5</strong>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[10px]">Potensi Luaran (30%)</span>
                                <strong class="text-slate-800 text-sm">{{ $rev->skor_luaran }} / 5</strong>
                            </div>
                            <div class="p-2 bg-white rounded-lg border border-slate-200">
                                <span class="text-slate-400 block text-[10px]">Rekam Jejak (20%)</span>
                                <strong class="text-slate-800 text-sm">{{ $rev->skor_rekam_jejak }} / 5</strong>
                            </div>
                        </div>

                        <div>
                            <span class="font-bold text-slate-700 block mb-1">Catatan Evaluasi Reviewer:</span>
                            <div class="p-3 bg-white rounded-lg border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-line">
                                {{ $rev->catatan_umum }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- TAB 7: KONTRAK & PENCAIRAN TERMIN DANA -->
    <div x-show="activeTab === 'keuangan'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-extrabold text-slate-900 text-base mb-1">Kontrak SPK & Pencairan Bertahap</h3>
            <p class="text-xs text-slate-500 mb-4">Sesuai SOP Bab 6, dana dicairkan dalam 2 termin: Tahap I (70%) di awal dan Tahap II (30%) setelah luaran divalidasi.</p>

            @if($proposal->contract)
            <div class="mb-5 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="text-slate-400 block">Nomor Kontrak / SPK:</span>
                        <strong class="text-sm font-bold text-slate-900">{{ $proposal->contract->nomor_kontrak }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Nilai Kontrak Disetujui:</span>
                        <strong class="text-sm font-bold text-emerald-700">Rp {{ number_format($proposal->contract->nilai_kontrak, 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Status Pengesahan:</span>
                        <span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[10px] uppercase">
                            {{ $proposal->contract->status_ttd }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <div class="space-y-3">
                @forelse($proposal->disbursements as $disb)
                <div class="p-4 rounded-xl border border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-black text-slate-800 text-sm">{{ $disb->termin_label }}</span>
                            @if($disb->status === 'paid')
                                <span class="px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-[10px]">
                                    <i class="fa-solid fa-check mr-1"></i> Telah Ditransfer
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 text-[10px]">
                                    <i class="fa-solid fa-clock mr-1"></i> Menunggu Proses Keuangan
                                </span>
                            @endif
                        </div>
                        <p class="text-slate-500 mt-1">{{ $disb->catatan }}</p>
                        @if($disb->tanggal_cair)
                        <p class="text-slate-400 text-[11px] mt-0.5">Tgl Cair: {{ $disb->tanggal_cair->format('d M Y') }} &bull; No Ref: {{ $disb->nomor_transaksi }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-base font-black text-slate-900">Rp {{ number_format($disb->nominal, 0, ',', '.') }}</div>
                        <span class="text-slate-400 text-[11px]">{{ $disb->persentase }}% dari total dana</span>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-6 text-center">Data pencairan dana disiapkan setelah proposal ditetapkan lolos didanai.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
