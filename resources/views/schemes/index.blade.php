@extends('layouts.app')

@section('title', 'Daftar Skema Hibah Internal')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Skema Hibah Penelitian & Pengabdian</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar skema pendanaan internal Universitas Sumatera Selatan (Tahap 1 SOP).</p>
        </div>
        @if(Auth::user()->isAdminLppm())
        <a href="{{ route('schemes.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center space-x-2 w-fit">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Skema Baru</span>
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($schemes as $scheme)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-extrabold uppercase px-2.5 py-1 rounded-full {{ $scheme->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $scheme->kategori }}
                    </span>
                    <span class="text-xs font-bold text-slate-400">Tahun {{ $scheme->tahun_anggaran }}</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 leading-snug">{{ $scheme->nama_skema }}</h3>
                <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">{{ $scheme->deskripsi }}</p>

                <div class="mt-5 grid grid-cols-2 gap-3 py-3 px-4 bg-slate-50 rounded-xl text-xs border border-slate-100">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Pagu Total Skema:</span>
                        <strong class="text-slate-800 text-sm">Rp {{ number_format($scheme->pagu_anggaran, 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Maks. Usulan / Judul:</span>
                        <strong class="text-blue-700 text-sm">Rp {{ number_format($scheme->maks_dana_usulan, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="mt-4 space-y-1.5 text-xs text-slate-500">
                    <div class="flex items-center justify-between">
                        <span><i class="fa-regular fa-calendar-check text-slate-400 mr-1.5"></i> Periode Pengusulan:</span>
                        <strong class="text-slate-700">{{ $scheme->tgl_mulai_pengusulan->format('d M') }} s.d. {{ $scheme->tgl_akhir_pengusulan->format('d M Y') }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span><i class="fa-solid fa-file-invoice text-slate-400 mr-1.5"></i> Total Usulan Masuk:</span>
                        <strong class="text-slate-700">{{ $scheme->proposals_count }} Usulan</strong>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('schemes.show', $scheme) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                    Lihat Panduan & Detail &rarr;
                </a>
                @if(Auth::user()->isDosen())
                <a href="{{ route('proposals.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm">
                    Buat Usulan
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
