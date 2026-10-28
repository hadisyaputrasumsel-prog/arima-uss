@extends('layouts.app')

@section('title', 'Sunting Skema - ' . $scheme->nama_skema)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('schemes.show', $scheme) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sunting Skema Hibah</h1>
            <p class="text-xs text-slate-500">Perbarui informasi dan pagu anggaran skema.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('schemes.update', $scheme) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Skema Hibah</label>
            <input type="text" name="nama_skema" value="{{ old('nama_skema', $scheme->nama_skema) }}" required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Kegiatan</label>
                <select name="kategori" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    <option value="penelitian" {{ $scheme->kategori === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                    <option value="pengabdian" {{ $scheme->kategori === 'pengabdian' ? 'selected' : '' }}>Pengabdian kepada Masyarakat</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tahun Anggaran</label>
                <input type="text" name="tahun_anggaran" value="{{ old('tahun_anggaran', $scheme->tahun_anggaran) }}" maxlength="4" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Total Pagu Skema (Rp)</label>
                <input type="number" name="pagu_anggaran" value="{{ old('pagu_anggaran', $scheme->pagu_anggaran) }}" required min="0" step="100000"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Maksimal Usulan per Judul (Rp)</label>
                <input type="number" name="maks_dana_usulan" value="{{ old('maks_dana_usulan', $scheme->maks_dana_usulan) }}" required min="0" step="100000"
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Mulai Pengusulan</label>
                <input type="date" name="tgl_mulai_pengusulan" value="{{ old('tgl_mulai_pengusulan', $scheme->tgl_mulai_pengusulan->format('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Batas Akhir Pengusulan</label>
                <input type="date" name="tgl_akhir_pengusulan" value="{{ old('tgl_akhir_pengusulan', $scheme->tgl_akhir_pengusulan->format('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi & Ruang Lingkup</label>
            <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">{{ old('deskripsi', $scheme->deskripsi) }}</textarea>
        </div>

        <div class="flex items-center space-x-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ $scheme->is_active ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="is_active" class="text-xs font-semibold text-slate-700">Skema aktif</label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('schemes.show', $scheme) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md">
                Perbarui Skema
            </button>
        </div>
    </form>
</div>
@endsection
