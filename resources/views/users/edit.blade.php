@extends('layouts.app')

@section('title', 'Sunting Pengguna - ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('users.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sunting Pengguna</h1>
            <p class="text-xs text-slate-500">Perbarui data profil dan hak akses pengguna.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('users.update', $user) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4 text-xs">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Email Institusi</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kata Sandi Baru (Kosongkan bila tidak diubah)</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Peran Akses (Role)</label>
                <select name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white font-bold">
                    <option value="dosen" {{ $user->role === 'dosen' ? 'selected' : '' }}>Dosen / Pengusul</option>
                    <option value="reviewer" {{ $user->role === 'reviewer' ? 'selected' : '' }}>Reviewer Internal</option>
                    <option value="admin_lppm" {{ $user->role === 'admin_lppm' ? 'selected' : '' }}>Admin LPPM</option>
                    <option value="keuangan" {{ $user->role === 'keuangan' ? 'selected' : '' }}>Bagian Keuangan</option>
                    <option value="pimpinan" {{ $user->role === 'pimpinan' ? 'selected' : '' }}>Pimpinan (WR / Dekan)</option>
                    <option value="admin_ti" {{ $user->role === 'admin_ti' ? 'selected' : '' }}>Administrator TI</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Jabatan Fungsional</label>
                <select name="jabatan_fungsional" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    <option value="Tenaga Pengajar" {{ $user->jabatan_fungsional === 'Tenaga Pengajar' ? 'selected' : '' }}>Tenaga Pengajar</option>
                    <option value="Asisten Ahli" {{ $user->jabatan_fungsional === 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                    <option value="Lektor" {{ $user->jabatan_fungsional === 'Lektor' ? 'selected' : '' }}>Lektor</option>
                    <option value="Lektor Kepala" {{ $user->jabatan_fungsional === 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                    <option value="Guru Besar" {{ $user->jabatan_fungsional === 'Guru Besar' ? 'selected' : '' }}>Guru Besar</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">NIDN</label>
                <input type="text" name="nidn" value="{{ old('nidn', $user->nidn) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">NIP</label>
                <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Fakultas</label>
                <input type="text" name="fakultas" value="{{ old('fakultas', $user->fakultas) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
            <div>
                <label class="block font-bold text-slate-700 mb-1">Program Studi</label>
                <input type="text" name="program_studi" value="{{ old('program_studi', $user->program_studi) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
            </div>
        </div>

        <div>
            <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-md">
                Perbarui Pengguna
            </button>
        </div>
    </form>
</div>
@endsection
