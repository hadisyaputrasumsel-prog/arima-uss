@extends('layouts.app')

@section('title', 'Daftar Usulan Hibah Internal')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Usulan Penelitian & Pengabdian</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pengelolaan usulan proposal hibah internal Universitas Sumatera Selatan.</p>
        </div>
        @if(Auth::user()->isDosen())
        <a href="{{ route('proposals.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center space-x-2 w-fit">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Usulan Baru</span>
        </a>
        @endif
    </div>

    <!-- Filter Bar / Stats -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3">Judul Usulan</th>
                        <th class="py-3 px-3">Skema & Kategori</th>
                        <th class="py-3 px-3">Ketua & Anggota</th>
                        <th class="py-3 px-3">Dana Usulan</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposals as $prop)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-3 max-w-sm">
                            <a href="{{ route('proposals.show', $prop) }}" class="font-bold text-slate-900 hover:text-blue-600 block line-clamp-2">
                                {{ $prop->judul }}
                            </a>
                            <span class="text-[11px] text-slate-400 mt-0.5 block">Fokus: {{ $prop->bidang_fokus }}</span>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $prop->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $prop->kategori }}
                            </span>
                            <div class="text-[11px] text-slate-500 mt-1">{{ Str::limit($prop->scheme->nama_skema, 28) }}</div>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $prop->user->name }}</div>
                            <div class="text-[11px] text-slate-400">{{ $prop->user->program_studi ?? $prop->user->fakultas }}</div>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <div class="font-extrabold text-slate-900">Rp {{ number_format($prop->total_dana_usulan, 0, ',', '.') }}</div>
                            @if($prop->dana_disetujui)
                            <div class="text-[10px] text-emerald-600 font-semibold">Disetujui: Rp {{ number_format($prop->dana_disetujui, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $prop->status_badge_class }}">
                                {{ $prop->status_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                            <a href="{{ route('proposals.show', $prop) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold transition">
                                Buka Usulan &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p>Belum ada usulan proposal yang tersimpan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $proposals->links() }}
        </div>
    </div>
</div>
@endsection
