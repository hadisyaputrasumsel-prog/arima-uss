@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-6 sm:p-8 text-white shadow-xl">
        <div class="relative z-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-semibold mb-3 border border-white/15">
                <i class="fa-solid fa-graduation-cap text-amber-400"></i>
                <span>Sistem Pengelolaan Hibah Penelitian & Pengabdian Internal USS</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-blue-200 text-sm mt-1 max-w-2xl">
                Anda masuk sebagai <strong class="text-white">{{ $user->role_label }}</strong>. 
                Sistem ARIMA memfasilitasi 13 siklus tahapan tata kelola hibah internal mengadaptasi alur mekanisme nasional BIMA/DRTPM.
            </p>
        </div>
        <!-- Decorative SVG background pattern -->
        <div class="absolute right-0 top-0 bottom-0 opacity-10 pointer-events-none flex items-center pr-10">
            <i class="fa-solid fa-atom text-[180px]"></i>
        </div>
    </div>

    <!-- ROLE SPECIFIC METRICS -->

    @if($user->isAdminLppm() || $user->isAdminTi())
    <!-- LPPM Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Usulan</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalProposals }}</h3>
                <p class="text-[11px] text-blue-600 font-medium">Seluruh skema</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Perlu Verifikasi</p>
                <h3 class="text-2xl font-black text-amber-700">{{ $submittedProposals }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Verifikasi administrasi</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Dalam Review</p>
                <h3 class="text-2xl font-black text-purple-700">{{ $reviewProposals }}</h3>
                <p class="text-[11px] text-purple-600 font-medium">Reviewer internal</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Lolos Didanai</p>
                <h3 class="text-2xl font-black text-emerald-700">{{ $approvedProposals }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium">Rp {{ number_format($totalFundingApproved, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Verifications & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base flex items-center">
                    <i class="fa-solid fa-list-check text-blue-600 mr-2"></i>
                    Usulan Menunggu Verifikasi Administrasi (Tahap 3)
                </h3>
                <a href="{{ route('proposals.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            @if($pendingVerifications->isEmpty())
                <div class="py-8 text-center text-slate-400 text-sm">
                    <i class="fa-solid fa-check-double text-3xl mb-2 text-slate-300"></i>
                    <p>Semua usulan telah selesai diverifikasi!</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($pendingVerifications as $p)
                    <div class="py-3.5 flex items-center justify-between">
                        <div class="pr-4">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $p->kategori }}</span>
                            <a href="{{ route('proposals.show', $p) }}" class="block font-bold text-sm text-slate-900 hover:text-blue-600 mt-1">
                                {{ $p->judul }}
                            </a>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Ketua: <strong class="text-slate-700">{{ $p->user->name }}</strong> &bull; Total RAB: Rp {{ number_format($p->total_dana_usulan, 0, ',', '.') }}
                            </p>
                        </div>
                        <a href="{{ route('proposals.show', $p) }}" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 whitespace-nowrap shadow-sm">
                            Periksa &rarr;
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center">
                <i class="fa-solid fa-bolt text-amber-500 mr-2"></i>
                Aksi Cepat LPPM
            </h3>
            <div class="space-y-2.5">
                <a href="{{ route('schemes.create') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition text-xs font-bold text-slate-700 hover:text-blue-700">
                    <i class="fa-solid fa-plus-circle text-blue-600 text-base mr-3"></i>
                    <span>Buka Skema Hibah Baru</span>
                </a>
                <a href="{{ route('lppm.ranking') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition text-xs font-bold text-slate-700 hover:text-blue-700">
                    <i class="fa-solid fa-trophy text-amber-500 text-base mr-3"></i>
                    <span>Rapat Penetapan Pemenang (SK)</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center p-3 rounded-lg bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition text-xs font-bold text-slate-700 hover:text-blue-700">
                    <i class="fa-solid fa-file-excel text-emerald-600 text-base mr-3"></i>
                    <span>Ekspor Rekap Data untuk BKD/Akreditasi</span>
                </a>
            </div>
        </div>
    </div>

    @elseif($user->isDosen())
    <!-- DOSEN METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-signature"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Usulan Saya</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalMyProposals }}</h3>
                <p class="text-[11px] text-blue-600 font-medium">Ketua Pelaksana</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Kegiatan Aktif</p>
                <h3 class="text-2xl font-black text-emerald-700">{{ $activeMyProposals }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium">Sedang Berjalan</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Undangan Tim</p>
                <h3 class="text-2xl font-black text-amber-700">{{ $memberInvitations->where('status_konfirmasi', 'pending')->count() }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Perlu konfirmasi</p>
            </div>
        </div>
    </div>

    <!-- Undangan Keikutsertaan Tim Pengusul (SKPL-F-10) -->
    @if($memberInvitations->where('status_konfirmasi', 'pending')->isNotEmpty())
    <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 shadow-sm">
        <h3 class="font-bold text-amber-900 text-sm flex items-center mb-3">
            <i class="fa-solid fa-bell text-amber-600 mr-2"></i>
            Permintaan Konfirmasi Keikutsertaan Tim (Wajib Dikonfirmasi)
        </h3>
        <p class="text-xs text-amber-700 mb-4">
            Sesuai SKPL ARIMA, ketua pengusul tidak dapat mengirimkan proposal sebelum seluruh anggota tim menyetujui keikutsertaannya.
        </p>
        <div class="space-y-3">
            @foreach($memberInvitations->where('status_konfirmasi', 'pending') as $inv)
            <div class="bg-white p-4 rounded-xl border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $inv->proposal->kategori }}</span>
                    <h4 class="font-bold text-sm text-slate-800 mt-1">{{ $inv->proposal->judul }}</h4>
                    <p class="text-xs text-slate-500">Ketua Pengusul: <strong class="text-slate-700">{{ $inv->proposal->user->name }}</strong> &bull; Peran: {{ $inv->peran_tim }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <form method="POST" action="{{ route('proposals.members.confirm', $inv) }}">
                        @csrf
                        <input type="hidden" name="action" value="approved">
                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-check mr-1"></i> Setuju
                        </button>
                    </form>
                    <form method="POST" action="{{ route('proposals.members.confirm', $inv) }}">
                        @csrf
                        <input type="hidden" name="action" value="rejected">
                        <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                            <i class="fa-solid fa-xmark mr-1"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Daftar Usulan Saya -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center">
                <i class="fa-solid fa-file-lines text-blue-600 mr-2"></i>
                Daftar Usulan Penelitian & Pengabdian Saya
            </h3>
            <a href="{{ route('proposals.create') }}" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm flex items-center space-x-1">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Buat Usulan Baru</span>
            </a>
        </div>

        @if($myProposals->isEmpty())
            <div class="py-10 text-center text-slate-400">
                <i class="fa-solid fa-folder-open text-4xl mb-2 text-slate-300"></i>
                <p class="text-sm">Anda belum memiliki usulan proposal.</p>
                <a href="{{ route('proposals.create') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm">
                    Mulai Usulkan Proposal Sekarang
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($myProposals as $p)
                <div class="py-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $p->kategori }}</span>
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-bold border {{ $p->status_badge_class }}">
                                {{ $p->status_label }}
                            </span>
                        </div>
                        <a href="{{ route('proposals.show', $p) }}" class="block font-bold text-slate-900 hover:text-blue-600 text-sm mt-1.5">
                            {{ $p->judul }}
                        </a>
                        <p class="text-xs text-slate-500 mt-1">
                            Skema: {{ $p->scheme->nama_skema }} &bull; Total RAB: <strong>Rp {{ number_format($p->total_dana_usulan, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('proposals.show', $p) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    @elseif($user->isReviewer())
    <!-- REVIEWER METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Menunggu Penilaian</p>
                <h3 class="text-2xl font-black text-amber-700">{{ $pendingReviewsCount }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Tugas review aktif</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Review Selesai</p>
                <h3 class="text-2xl font-black text-emerald-700">{{ $completedReviewsCount }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium">Rubrik tersimpan</p>
            </div>
        </div>
    </div>

    <!-- Review Tasks List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center">
                <i class="fa-solid fa-clipboard-check text-blue-600 mr-2"></i>
                Daftar Usulan yang Ditugaskan untuk Direview (Blind Review)
            </h3>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($assignedReviews as $asg)
            <div class="py-4 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">{{ $asg->proposal->kategori }}</span>
                        @if($asg->status === 'completed')
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800">Selesai Direview (Skor: {{ $asg->review->nilai_akhir ?? '-' }})</span>
                        @else
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800">Menunggu Penilaian</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-sm text-slate-900 mt-1.5">{{ $asg->proposal->judul }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Skema: {{ $asg->proposal->scheme->nama_skema }} &bull; Bidang Fokus: {{ $asg->proposal->bidang_fokus }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('reviews.show', $asg->proposal) }}" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm whitespace-nowrap">
                        {{ $asg->status === 'completed' ? 'Lihat / Edit Rubrik' : 'Isi Rubrik Penilaian' }} &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-slate-400 text-sm">
                Tidak ada tugas review proposal yang ditugaskan saat ini.
            </div>
            @endforelse
        </div>
    </div>

    @elseif($user->isKeuangan())
    <!-- KEUANGAN METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Menunggu Pencairan</p>
                <h3 class="text-2xl font-black text-amber-700">{{ $pendingDisbursements->count() }}</h3>
                <p class="text-[11px] text-amber-600 font-medium">Rp {{ number_format($totalPendingAmount, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Dana Dicairkan</p>
                <h3 class="text-2xl font-black text-emerald-700">Rp {{ number_format($totalPaidAmount / 1000000, 1) }} Jt</h3>
                <p class="text-[11px] text-emerald-600 font-medium">{{ $paidDisbursements->count() }} transaksi</p>
            </div>
        </div>
    </div>

    <!-- Quick Action to Finance -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center">
                <i class="fa-solid fa-money-bill-transfer text-emerald-600 mr-2"></i>
                Tagihan Pencairan Dana Termin 1 & 2 (Tahap 7 & 12 SOP)
            </h3>
            <a href="{{ route('finance.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola Pencairan &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($pendingDisbursements as $disb)
            <div class="py-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800">{{ $disb->termin_label }}</span>
                    <h4 class="font-bold text-sm text-slate-900 mt-1">{{ $disb->proposal->judul }}</h4>
                    <p class="text-xs text-slate-500">
                        Penerima: <strong>{{ $disb->proposal->user->name }}</strong> &bull; Bank: {{ $disb->proposal->user->bank_name ?? 'Bank Sumsel Babel' }} ({{ $disb->proposal->user->bank_account_number ?? '-' }})
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-base font-extrabold text-slate-900">Rp {{ number_format($disb->nominal, 0, ',', '.') }}</div>
                    <a href="{{ route('finance.index') }}" class="mt-1 inline-block px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                        Proses Transfer &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-slate-400 text-sm">
                Tidak ada tagihan pencairan yang tertunda.
            </div>
            @endforelse
        </div>
    </div>

    @elseif($user->isPimpinan())
    <!-- PIMPINAN METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Pengusul</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalProposals }} Usulan</h3>
                <p class="text-[11px] text-blue-600 font-medium">Tahun Anggaran 2026</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-trophy"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Ditetapkan Didanai</p>
                <h3 class="text-2xl font-black text-emerald-700">{{ $approvedCount }} Judul</h3>
                <p class="text-[11px] text-emerald-600 font-medium">SK Rektor USS</p>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase">Realisasi Dana</p>
                <h3 class="text-2xl font-black text-purple-700">Rp {{ number_format($totalDisbursed / 1000000, 1) }} Jt</h3>
                <p class="text-[11px] text-purple-600 font-medium">Pencairan terserap</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Skema Hibah Aktif (Semua Role) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center">
                <i class="fa-solid fa-bullhorn text-blue-600 mr-2"></i>
                Skema Hibah Internal Universitas Sumatera Selatan Tahun 2026
            </h3>
            <a href="{{ route('schemes.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Skema &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($schemes as $s)
            <div class="p-5 rounded-xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition bg-gradient-to-br from-white to-slate-50 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded {{ $s->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $s->kategori }}
                        </span>
                        <span class="text-xs text-slate-500 font-medium">TA {{ $s->tahun_anggaran }}</span>
                    </div>
                    <h4 class="font-extrabold text-slate-900 text-base">{{ $s->nama_skema }}</h4>
                    <p class="text-xs text-slate-600 mt-2 line-clamp-2">{{ $s->deskripsi }}</p>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500">Maks. Usulan / Judul:</span>
                        <span class="font-black text-slate-800">Rp {{ number_format($s->maks_dana_usulan, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 flex items-center justify-between">
                    <span class="text-[11px] text-slate-500">
                        <i class="fa-regular fa-calendar mr-1"></i> Batas: {{ $s->tgl_akhir_pengusulan->format('d M Y') }}
                    </span>
                    <a href="{{ route('proposals.create') }}" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm">
                        Usulkan &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Audit Trail Logs (SKPL-NF-02) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-bold text-slate-900 text-base mb-4 flex items-center">
            <i class="fa-solid fa-clock-rotate-left text-slate-600 mr-2"></i>
            Log Aktivitas Sistem (Audit Trail Terkini)
        </h3>
        <div class="space-y-3">
            @foreach($recentLogs as $log)
            <div class="flex items-start space-x-3 text-xs py-1.5 border-b border-slate-50 last:border-0">
                <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></span>
                <div class="flex-1">
                    <span class="font-bold text-slate-800">{{ $log->user->name ?? 'Sistem' }}</span>
                    <span class="text-slate-400">&bull; {{ $log->aksi }}</span>
                    <p class="text-slate-600 mt-0.5">{{ $log->keterangan }}</p>
                </div>
                <span class="text-slate-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
