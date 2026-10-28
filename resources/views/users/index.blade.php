@extends('layouts.app')

@section('title', 'Manajemen Pengguna Sistem (Admin TI)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna & Peran (SKPL-F-02)</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pengaturan akun dosen, reviewer, admin LPPM, keuangan, dan pimpinan universitas.</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center space-x-2 w-fit">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3">Nama Pengguna</th>
                        <th class="py-3 px-3">Email & Kontak</th>
                        <th class="py-3 px-3">Peran (Role)</th>
                        <th class="py-3 px-3">Fakultas / Unit</th>
                        <th class="py-3 px-3">NIDN / NIP</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-3">
                            <strong class="text-slate-900 text-sm block">{{ $u->name }}</strong>
                            <span class="text-[11px] text-slate-400">{{ $u->jabatan_fungsional ?? 'Dosen' }}</span>
                        </td>
                        <td class="py-3.5 px-3">
                            <span class="text-slate-700">{{ $u->email }}</span>
                            <div class="text-[11px] text-slate-400">{{ $u->no_hp ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase
                                @if($u->role === 'dosen') bg-blue-100 text-blue-800
                                @elseif($u->role === 'reviewer') bg-purple-100 text-purple-800
                                @elseif($u->role === 'admin_lppm') bg-amber-100 text-amber-800
                                @elseif($u->role === 'keuangan') bg-emerald-100 text-emerald-800
                                @elseif($u->role === 'pimpinan') bg-rose-100 text-rose-800
                                @else bg-slate-100 text-slate-800 @endif">
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3">
                            <div class="text-slate-800 font-semibold">{{ $u->fakultas ?? '-' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $u->program_studi ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-3 font-mono text-slate-600">
                            {{ $u->nidn ?? $u->nip ?? '-' }}
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('users.edit', $u) }}" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus akun pengguna {{ $u->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
