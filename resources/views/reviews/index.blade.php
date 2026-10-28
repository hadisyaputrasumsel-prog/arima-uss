@extends('layouts.app')

@section('title', 'Tugas Penilaian Reviewer')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Penilaian Substansi Proposal (Reviewer Internal)</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Tahap 4 SOP & Lampiran 5: Evaluasi kelayakan metodologi, kewajaran RAB, dan potensi ketercapaian luaran (Blind Review).</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="divide-y divide-slate-100">
            @forelse($assignments as $asg)
            <div class="py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $asg->proposal->kategori === 'penelitian' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $asg->proposal->kategori }}
                        </span>
                        @if($asg->status === 'completed')
                            <span class="px-2.5 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800 text-xs">
                                <i class="fa-solid fa-circle-check mr-1"></i> Penilaian Selesai (Nilai: {{ $asg->review->nilai_akhir ?? '-' }})
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800 text-xs">
                                <i class="fa-solid fa-clock mr-1"></i> Perlu Penilaian
                            </span>
                        @endif
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm mt-1.5">{{ $asg->proposal->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Skema: {{ $asg->proposal->scheme->nama_skema }} &bull; Bidang Fokus: {{ $asg->proposal->bidang_fokus }} &bull; Usulan RAB: <strong>Rp {{ number_format($asg->proposal->total_dana_usulan, 0, ',', '.') }}</strong>
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($asg->review)
                    <a href="{{ route('reviews.print', [$asg->proposal, $asg->review]) }}" target="_blank" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        <i class="fa-solid fa-print mr-1"></i> Cetak Rubrik
                    </a>
                    @endif
                    <a href="{{ route('reviews.show', $asg->proposal) }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        {{ $asg->status === 'completed' ? 'Ubah Penilaian' : 'Mulai Penilaian' }} &rarr;
                    </a>
                </div>
            </div>
            @empty
            <div class="py-12 text-center text-slate-400">
                <i class="fa-solid fa-clipboard-check text-4xl mb-2 text-slate-300"></i>
                <p class="text-sm">Saat ini belum ada tugas penilaian proposal yang ditugaskan kepada Anda.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection
