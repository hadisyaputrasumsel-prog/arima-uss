@extends('layouts.app')

@section('title', 'Formulir Identitas Usulan (Lampiran 1)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center space-x-3">
        <a href="{{ route('proposals.index') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Formulir Identitas Usulan Baru</h1>
            <p class="text-xs text-slate-500">Lampiran 1 & Tahap 2 SOP: Pengusulan Proposal Penelitian / Pengabdian Internal USS.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('proposals.store') }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- Bagian A: Data Umum Usulan -->
        <div>
            <h3 class="text-sm font-black text-blue-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs mr-2">A</span>
                Data Umum Usulan
            </h3>

            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Skema Hibah Internal</label>
                    <select name="scheme_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Skema Hibah --</option>
                        @foreach($schemes as $s)
                        <option value="{{ $s->id }}" {{ old('scheme_id') == $s->id ? 'selected' : '' }}>
                            [{{ strtoupper($s->kategori) }}] {{ $s->nama_skema }} (Maks: Rp {{ number_format($s->maks_dana_usulan, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Penelitian / Pengabdian</label>
                    <textarea name="judul" rows="2" required placeholder="Tuliskan judul usulan secara lengkap, jelas, dan menggambarkan fokus kegiatan..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500">{{ old('judul') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bidang Fokus / Rumpun Ilmu</label>
                        <input type="text" name="bidang_fokus" value="{{ old('bidang_fokus') }}" required placeholder="Contoh: Ketahanan Pangan, TIK, Sosial Humaniora"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat Kesiapan Teknologi (TKT 1 - 9)</label>
                        <select name="tkt" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                            <option value="">-- Khusus Skema Penelitian --</option>
                            <option value="1">TKT 1 - Prinsip dasar teridentifikasi</option>
                            <option value="2">TKT 2 - Konsep dan aplikasi terformulasi</option>
                            <option value="3">TKT 3 - Pembuktian konsep secara analitis & eksperimental</option>
                            <option value="4">TKT 4 - Validasi komponen dalam lingkungan lab</option>
                            <option value="5">TKT 5 - Validasi komponen dalam lingkungan relevan</option>
                            <option value="6">TKT 6 - Demonstrasi model sistem dalam lingkungan relevan</option>
                            <option value="7">TKT 7 - Demonstrasi prototipe sistem di lingkungan operasional</option>
                            <option value="8">TKT 8 - Sistem teruji dan memenuhi kualifikasi</option>
                            <option value="9">TKT 9 - Sistem terbukti dalam lingkungan operasional aktual</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lama Kegiatan (Bulan)</label>
                        <input type="number" name="lama_kegiatan" value="{{ old('lama_kegiatan', 6) }}" min="1" max="12" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lokasi Pelaksanaan Kegiatan</label>
                        <input type="text" name="lokasi_kegiatan" value="{{ old('lokasi_kegiatan', 'Kota Palembang, Sumatera Selatan') }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ringkasan Usulan (Maks. 250 kata)</label>
                    <textarea name="ringkasan" rows="4" required placeholder="Uraikan permasalahan, tujuan, metode pelaksanaan, dan target luaran utama secara ringkas..."
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:bg-white">{{ old('ringkasan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bagian B: Data Ketua Pengusul (Otomatis dari Akun) -->
        <div class="pt-4 border-t border-slate-100">
            <h3 class="text-sm font-black text-blue-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs mr-2">B</span>
                Data Ketua Pengusul (Data Profil Dosen)
            </h3>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-400 block text-[11px]">Nama Lengkap & Gelar:</span>
                    <strong class="text-slate-900 text-sm">{{ $user->name }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-400 block text-[11px]">NIDN / NIP:</span>
                    <strong class="text-slate-900 text-sm">{{ $user->nidn ?? $user->nip ?? '-' }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-400 block text-[11px]">Fakultas / Program Studi:</span>
                    <strong class="text-slate-900 text-sm">{{ $user->fakultas }} / {{ $user->program_studi }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-slate-400 block text-[11px]">Surel / No. WhatsApp:</span>
                    <strong class="text-slate-900 text-sm">{{ $user->email }} / {{ $user->no_hp ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-[11px] text-slate-500 max-w-md">
                Setelah menyimpan draf identitas usulan, Anda dapat menambahkan anggota tim, rincian RAB dengan validasi batas %, dan mengunggah dokumen proposal (PDF).
            </p>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-600 hover:from-blue-800 hover:to-indigo-700 text-white font-bold text-xs shadow-md">
                Simpan & Lanjutkan ke Detail Usulan &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
