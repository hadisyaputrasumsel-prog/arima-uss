@extends('layouts.app')

@section('title', 'Sunting Usulan - ' . $proposal->judul)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('proposals.show', $proposal) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sunting Draf Usulan</h1>
            <p class="text-xs text-slate-500">Perbarui identitas usulan, bidang fokus, atau ringkasan.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('proposals.update', $proposal) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Penelitian / Pengabdian</label>
            <textarea name="judul" rows="2" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">{{ old('judul', $proposal->judul) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bidang Fokus / Rumpun Ilmu</label>
                <input type="text" name="bidang_fokus" value="{{ old('bidang_fokus', $proposal->bidang_fokus) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">TKT (1 - 9, Khusus Penelitian)</label>
                <select name="tkt" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    <option value="">-- N/A (Pengabdian) --</option>
                    @for($i=1; $i<=9; $i++)
                    <option value="{{ $i }}" {{ $proposal->tkt == $i ? 'selected' : '' }}>TKT {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lama Kegiatan (Bulan)</label>
                <input type="number" name="lama_kegiatan" value="{{ old('lama_kegiatan', $proposal->lama_kegiatan) }}" min="1" max="12" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi Pelaksanaan Kegiatan</label>
                <input type="text" name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan', $proposal->lokasi_kegiatan) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ringkasan Usulan</label>
            <textarea name="ringkasan" rows="4" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">{{ old('ringkasan', $proposal->ringkasan) }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('proposals.show', $proposal) }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md">
                Perbarui Identitas Usulan &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
