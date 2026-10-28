@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('users.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-xs text-slate-500">Pendaftaran akun sivitas akademika & pemangku kepentingan.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('users.store') }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4 text-xs">
        @csrf

        <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Dr. Ir. Budi Santoso, M.T." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Institusi</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@uss.ac.id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kata Sandi Awal</label>
                <input type="password" name="password" required value="password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran Akses (Role)</label>
                <select name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white font-bold">
                    <option value="dosen">Dosen / Pengusul</option>
                    <option value="reviewer">Reviewer Internal</option>
                    <option value="admin_lppm">Admin LPPM</option>
                    <option value="keuangan">Bagian Keuangan</option>
                    <option value="pimpinan">Pimpinan (WR / Dekan)</option>
                    <option value="admin_ti">Administrator TI</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jabatan Fungsional</label>
                <select name="jabatan_fungsional" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    <option value="Tenaga Pengajar">Tenaga Pengajar</option>
                    <option value="Asisten Ahli">Asisten Ahli</option>
                    <option value="Lektor">Lektor</option>
                    <option value="Lektor Kepala">Lektor Kepala</option>
                    <option value="Guru Besar">Guru Besar</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">NIDN</label>
                <input type="text" name="nidn" value="{{ old('nidn') }}" placeholder="10 digit NIDN" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">NIP</label>
                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="18 digit NIP" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Fakultas</label>
                <input type="text" name="fakultas" value="{{ old('fakultas') }}" placeholder="Contoh: Ilmu Komputer" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Program Studi</label>
                <input type="text" name="program_studi" value="{{ old('program_studi') }}" placeholder="Contoh: Teknik Informatika" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08123456789" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md">
                Simpan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
