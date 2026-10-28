@extends('layouts.app')

@section('title', 'Rekapitulasi Data Hibah Internal (BKD & Akreditasi)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekapitulasi Data Hibah Internal (Arsip & BKD)</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Tahap 13 SOP & SKPL-F-30: Pengarsipan digital dan ekspor data terstruktur untuk Beban Kerja Dosen (BKD), SINTA, dan Akreditasi.</p>
        </div>
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center space-x-2 w-fit">
            <i class="fa-solid fa-file-excel text-base"></i>
            <span>Ekspor ke CSV / Excel</span>
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 text-xs">
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Skema Hibah</label>
                <select name="scheme_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    <option value="">Semua Skema</option>
                    @foreach($schemes as $s)
                    <option value="{{ $s->id }}" {{ request('scheme_id') == $s->id ? 'selected' : '' }}>{{ $s->nama_skema }} ({{ $s->tahun_anggaran }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Kategori</label>
                <select name="kategori" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    <option value="">Semua Kategori</option>
                    <option value="penelitian" {{ request('kategori') === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                    <option value="pengabdian" {{ request('kategori') === 'pengabdian' ? 'selected' : '' }}>Pengabdian</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Filter Status Usulan</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Diajukan</option>
                    <option value="substantive_review" {{ request('status') === 'substantive_review' ? 'selected' : '' }}>Review Substansi</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Lolos Didanai</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Pelaksanaan Berjalan</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai & Arsip</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('reports.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg font-bold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Rekapitulasi -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500">Menampilkan {{ $proposals->count() }} data usulan hibah</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3">No</th>
                        <th class="py-3 px-3">Judul Usulan</th>
                        <th class="py-3 px-3">Ketua & Unit</th>
                        <th class="py-3 px-3">Skema & TA</th>
                        <th class="py-3 px-3 text-right">Dana Disetujui</th>
                        <th class="py-3 px-3 text-center">Skor Review</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposals as $idx => $prop)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-3 text-slate-400 font-bold">{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-3 max-w-sm">
                            <a href="{{ route('proposals.show', $prop) }}" class="font-bold text-slate-900 hover:text-blue-600 block line-clamp-2">
                                {{ $prop->judul }}
                            </a>
                            <span class="text-[11px] text-slate-400">Luaran Wajib: {{ $prop->outputs->count() }} target</span>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <strong class="text-slate-800">{{ $prop->user->name }}</strong>
                            <div class="text-[11px] text-slate-500">{{ $prop->user->fakultas }} &bull; {{ $prop->user->program_studi }}</div>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded {{ $prop->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $prop->kategori }}
                            </span>
                            <div class="text-[11px] text-slate-500 mt-1">{{ Str::limit($prop->scheme->nama_skema, 20) }} ({{ $prop->scheme->tahun_anggaran }})</div>
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap font-bold text-slate-900">
                            Rp {{ number_format($prop->dana_disetujui ?? $prop->total_dana_usulan, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            @if($prop->average_score)
                                <span class="px-2 py-0.5 rounded-full font-bold bg-blue-100 text-blue-800 text-xs">
                                    {{ $prop->average_score }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $prop->status_badge_class }}">
                                {{ $prop->status_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                            <a href="{{ route('proposals.show', $prop) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold transition">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-10 text-center text-slate-400">Tidak ada data usulan yang cocok dengan kriteria filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
