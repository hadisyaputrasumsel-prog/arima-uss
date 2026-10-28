@extends('layouts.app')

@section('title', $scheme->nama_skema)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('schemes.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $scheme->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $scheme->kategori }} &bull; TA {{ $scheme->tahun_anggaran }}
                </span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">{{ $scheme->nama_skema }}</h1>
            </div>
        </div>
        @if(Auth::user()->isDosen())
        <a href="{{ route('proposals.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md">
            Usulkan Proposal pada Skema Ini
        </a>
        @endif
    </div>

    <!-- Details Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
            <h3 class="font-bold text-slate-900 text-base">Deskripsi Skema</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $scheme->deskripsi ?? 'Tidak ada deskripsi rinci.' }}</p>

            <h3 class="font-bold text-slate-900 text-base pt-3 border-t border-slate-100">Daftar Usulan Terdaftar</h3>
            <div class="divide-y divide-slate-100">
                @forelse($scheme->proposals as $prop)
                <div class="py-3 flex items-center justify-between text-xs">
                    <div>
                        <a href="{{ route('proposals.show', $prop) }}" class="font-bold text-slate-800 hover:text-blue-600 block">{{ $prop->judul }}</a>
                        <span class="text-slate-500">Ketua: {{ $prop->user->name }} &bull; Usulan: Rp {{ number_format($prop->total_dana_usulan, 0, ',', '.') }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded font-bold border {{ $prop->status_badge_class }}">
                        {{ $prop->status_label }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 py-4">Belum ada usulan pada skema ini.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4 h-fit">
            <h3 class="font-bold text-slate-900 text-base">Informasi Anggaran</h3>
            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block">Pagu Anggaran Total:</span>
                    <strong class="text-base font-extrabold text-slate-900">Rp {{ number_format($scheme->pagu_anggaran, 0, ',', '.') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block">Plafon Maksimal per Usulan:</span>
                    <strong class="text-base font-extrabold text-blue-700">Rp {{ number_format($scheme->maks_dana_usulan, 0, ',', '.') }}</strong>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-slate-400 block">Periode Pengusulan:</span>
                    <span class="font-semibold text-slate-700">{{ $scheme->tgl_mulai_pengusulan->format('d M Y') }} s.d. {{ $scheme->tgl_akhir_pengusulan->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
