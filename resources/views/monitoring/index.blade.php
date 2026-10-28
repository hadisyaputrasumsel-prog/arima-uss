@extends('layouts.app')

@section('title', 'Monitoring & Evaluasi (Monev) Kemajuan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Monitoring & Evaluasi (Monev) Kemajuan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Tahap 8 & 9 SOP & Lampiran 6: Pemantauan capaian kegiatan, serapan dana tahap I, dan penerbitan Berita Acara Monev.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="divide-y divide-slate-100">
            @forelse($proposals as $prop)
            <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $prop->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $prop->kategori }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $prop->status_badge_class }}">
                            {{ $prop->status_label }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm mt-1.5">{{ $prop->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Ketua: <strong class="text-slate-700">{{ $prop->user->name }}</strong> &bull; Total Dana: Rp {{ number_format($prop->total_dana_usulan, 0, ',', '.') }}
                    </p>

                    @if($prop->latestProgressReport)
                    <div class="mt-2 flex items-center space-x-3 text-xs">
                        <span class="font-bold text-blue-700">Capaian: {{ $prop->latestProgressReport->persentase_capaian }}%</span>
                        <span class="text-slate-400">&bull;</span>
                        <span class="text-slate-600">Serapan Dana I: Rp {{ number_format($prop->latestProgressReport->dana_terpakai, 0, ',', '.') }}</span>
                        <span class="text-slate-400">&bull;</span>
                        <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $prop->latestProgressReport->status_monev === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ strtoupper($prop->latestProgressReport->status_monev) }}
                        </span>
                    </div>
                    @else
                    <div class="mt-2 text-xs text-amber-600 font-semibold flex items-center">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Belum ada laporan kemajuan yang diunggah.
                    </div>
                    @endif
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('monitoring.show', $prop) }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm whitespace-nowrap">
                        Kelola Monev &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400">
                <i class="fa-solid fa-chart-line text-4xl mb-2 text-slate-300"></i>
                <p class="text-sm">Belum ada kegiatan penelitian/pengabdian pada tahap pelaksanaan dan monev.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $proposals->links() }}
        </div>
    </div>
</div>
@endsection
