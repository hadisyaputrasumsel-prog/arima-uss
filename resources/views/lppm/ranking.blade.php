@extends('layouts.app')

@section('title', 'Penetapan Pemenang Hibah Internal (Tahap 5 SOP)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rapat Penetapan Pemenang Hibah Internal</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Tahap 5 SOP & SKPL-F-18: Rekapitulasi skor reviewer, perangkingan kelayakan, dan penetapan SK Rektor.</p>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-lg">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div>
                <span class="text-blue-300 block">Total Usulan Siap Ditetapkan:</span>
                <strong class="text-2xl font-black">{{ $proposals->count() }} Judul</strong>
            </div>
            <div>
                <span class="text-blue-300 block">Usulan Lolos Didanai:</span>
                <strong class="text-2xl font-black text-emerald-400">{{ $proposals->where('status', 'approved')->count() }} Judul</strong>
            </div>
            <div>
                <span class="text-blue-300 block">Total Alokasi Disetujui:</span>
                <strong class="text-2xl font-black text-amber-300">Rp {{ number_format($proposals->where('status', 'approved')->sum('dana_disetujui'), 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <!-- Ranking Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-extrabold text-slate-900 text-base mb-4">Peringkat Usulan Berdasarkan Skor Reviewer</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3 text-center">Rank</th>
                        <th class="py-3 px-3">Judul Usulan & Ketua</th>
                        <th class="py-3 px-3">Skema</th>
                        <th class="py-3 px-3 text-center">Rata-rata Skor</th>
                        <th class="py-3 px-3">Usulan Dana</th>
                        <th class="py-3 px-3">Status Saat Ini</th>
                        <th class="py-3 px-3 text-right">Keputusan Penetapan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposals as $idx => $prop)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-4 px-3 text-center font-black text-sm text-slate-700">
                            #{{ $idx + 1 }}
                        </td>
                        <td class="py-4 px-3 max-w-sm">
                            <a href="{{ route('proposals.show', $prop) }}" class="font-bold text-slate-900 hover:text-blue-600 block line-clamp-2">
                                {{ $prop->judul }}
                            </a>
                            <span class="text-slate-500 mt-0.5 block">Ketua: <strong class="text-slate-700">{{ $prop->user->name }}</strong> ({{ $prop->user->program_studi }})</span>
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $prop->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ $prop->kategori }}
                            </span>
                        </td>
                        <td class="py-4 px-3 text-center whitespace-nowrap">
                            @if($prop->average_score)
                                <span class="px-2.5 py-1 rounded-full font-black text-sm bg-blue-100 text-blue-800">
                                    {{ $prop->average_score }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $prop->reviews->count() }} reviewer</div>
                            @else
                                <span class="text-slate-400">Belum ada skor</span>
                            @endif
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <div class="font-bold text-slate-800">Rp {{ number_format($prop->total_dana_usulan, 0, ',', '.') }}</div>
                            @if($prop->dana_disetujui)
                            <div class="text-[11px] text-emerald-600 font-semibold">Disetujui: Rp {{ number_format($prop->dana_disetujui, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $prop->status_badge_class }}">
                                {{ $prop->status_label }}
                            </span>
                        </td>
                        <td class="py-4 px-3 text-right whitespace-nowrap">
                            @if(in_array($prop->status, ['substantive_review', 'approved', 'rejected']))
                            <div x-data="{ openModal: false }">
                                <button @click="openModal = true" class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm">
                                    {{ $prop->status === 'approved' ? 'Perbarui Penetapan' : 'Tetapkan Pemenang' }}
                                </button>

                                <!-- Modal Penetapan Pemenang -->
                                <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                    <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                                        <h4 class="font-extrabold text-slate-900 text-base mb-1">Keputusan Penetapan Pemenang</h4>
                                        <p class="text-xs text-slate-500 mb-4 line-clamp-1">{{ $prop->judul }}</p>

                                        <form method="POST" action="{{ route('lppm.determine.winner', $prop) }}" class="space-y-4 text-xs">
                                            @csrf
                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Keputusan Rapat LPPM & Pimpinan</label>
                                                <select name="action" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-bold">
                                                    <option value="approve" {{ $prop->status === 'approved' ? 'selected' : '' }}>LOLOS DIDANAI (Pemenang)</option>
                                                    <option value="reject" {{ $prop->status === 'rejected' ? 'selected' : '' }}>TIDAK DIDANAI (Ditolak)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Nominal Dana Disetujui (Rp)</label>
                                                <input type="number" name="dana_disetujui" value="{{ $prop->dana_disetujui ?? $prop->total_dana_usulan }}" min="1" step="100000" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Nomor SK Penetapan Rektor</label>
                                                <input type="text" name="sk_penetapan_no" value="{{ $prop->sk_penetapan_no ?? 'SK/014/REK-USS/HIBAH/' . date('Y') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Tanggal SK Penetapan</label>
                                                <input type="date" name="sk_penetapan_tgl" value="{{ $prop->sk_penetapan_tgl ? $prop->sk_penetapan_tgl->format('Y-m-d') : date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>

                                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                                                <button type="button" @click="openModal = false" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Penetapan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="text-slate-400 text-xs">Penetapan Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-10 text-center text-slate-400">Belum ada usulan yang siap untuk rapat penetapan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
