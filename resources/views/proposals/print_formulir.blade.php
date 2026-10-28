<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Identitas Usulan - {{ $proposal->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt !important; line-height: 1.4 !important; }
            @page { margin: 20mm; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 font-serif text-slate-900">
    <!-- Print toolbar -->
    <div class="max-w-4xl mx-auto mb-4 no-print flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-sm font-sans">
        <span class="text-xs text-slate-500">Pratinjau Cetak: Formulir Identitas & Lembar Pengesahan (Lampiran 1 & 2 SOP)</span>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm">
            Cetak Dokumen
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-md border border-slate-200">
        <!-- Header Kop Kampus -->
        <div class="text-center pb-4 border-b-2 border-slate-900 mb-6 font-sans">
            <h2 class="text-xl font-bold tracking-tight">UNIVERSITAS SUMATERA SELATAN</h2>
            <h3 class="text-sm font-bold uppercase text-slate-700">LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</h3>
            <p class="text-xs text-slate-500">Jl. Kolonel H. Barlian No. 12, Palembang, Sumatera Selatan &bull; Surel: lppm@uss.ac.id</p>
        </div>

        <div class="text-center mb-6">
            <h3 class="text-base font-bold uppercase tracking-wider underline">FORMULIR IDENTITAS USULAN</h3>
            <p class="text-xs uppercase font-sans mt-0.5">SOP Hibah Penelitian dan Pengabdian Internal (No: SOP/LPPM-USS/HI-PPM)</p>
        </div>

        <!-- Bagian A: Data Umum Usulan -->
        <h4 class="font-bold text-sm uppercase font-sans mb-2 border-b border-slate-300 pb-1">A. Data Umum Usulan</h4>
        <table class="w-full text-xs mb-6 font-sans border-collapse">
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold w-1/3">1. Judul Penelitian / Pengabdian</td>
                <td class="py-2">: {{ $proposal->judul }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">2. Skema Hibah</td>
                <td class="py-2">: {{ $proposal->scheme->nama_skema }} (Tahun Anggaran {{ $proposal->scheme->tahun_anggaran }})</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">3. Kategori Kegiatan</td>
                <td class="py-2">: [ {{ $proposal->kategori === 'penelitian' ? 'X' : ' ' }} ] Penelitian &nbsp;&nbsp;&nbsp; [ {{ $proposal->kategori === 'pengabdian' ? 'X' : ' ' }} ] Pengabdian kepada Masyarakat</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">4. Bidang Fokus / Rumpun Ilmu</td>
                <td class="py-2">: {{ $proposal->bidang_fokus }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">5. Tingkat Kesiapan Teknologi (TKT)</td>
                <td class="py-2">: {{ $proposal->tkt ? 'TKT ' . $proposal->tkt : 'N/A' }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">6. Lama Kegiatan & Lokasi</td>
                <td class="py-2">: {{ $proposal->lama_kegiatan }} Bulan &bull; Lokasi: {{ $proposal->lokasi_kegiatan }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">7. Total Usulan Dana</td>
                <td class="py-2">: <strong>Rp {{ number_format($proposal->total_dana_usulan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        <!-- Bagian B: Data Ketua Pengusul -->
        <h4 class="font-bold text-sm uppercase font-sans mb-2 border-b border-slate-300 pb-1">B. Data Ketua Pengusul</h4>
        <table class="w-full text-xs mb-6 font-sans border-collapse">
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold w-1/3">1. Nama Lengkap & Gelar</td>
                <td class="py-2">: {{ $proposal->user->name }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">2. NIDN / NIP</td>
                <td class="py-2">: {{ $proposal->user->nidn ?? $proposal->user->nip ?? '-' }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">3. Jabatan Fungsional</td>
                <td class="py-2">: {{ $proposal->user->jabatan_fungsional ?? 'Tenaga Pengajar' }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">4. Program Studi / Fakultas</td>
                <td class="py-2">: {{ $proposal->user->program_studi }} / {{ $proposal->user->fakultas }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-2 font-bold">5. Surel / Nomor Kontak HP</td>
                <td class="py-2">: {{ $proposal->user->email }} / {{ $proposal->user->no_hp ?? '-' }}</td>
            </tr>
        </table>

        <!-- Bagian C: Susunan Anggota Tim -->
        <h4 class="font-bold text-sm uppercase font-sans mb-2 border-b border-slate-300 pb-1">C. Susunan Anggota Tim</h4>
        <table class="w-full text-xs mb-6 font-sans border border-slate-300 border-collapse">
            <thead class="bg-slate-50 font-bold">
                <tr>
                    <th class="border border-slate-300 p-2 text-center w-10">No</th>
                    <th class="border border-slate-300 p-2">Nama Anggota</th>
                    <th class="border border-slate-300 p-2">NIDN / NIP</th>
                    <th class="border border-slate-300 p-2">Fakultas / Prodi</th>
                    <th class="border border-slate-300 p-2">Tugas dalam Tim</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->members as $idx => $m)
                <tr>
                    <td class="border border-slate-300 p-2 text-center">{{ $idx + 1 }}</td>
                    <td class="border border-slate-300 p-2 font-semibold">{{ $m->nama_lengkap }}</td>
                    <td class="border border-slate-300 p-2">{{ $m->nidn_nip ?? '-' }}</td>
                    <td class="border border-slate-300 p-2">{{ $m->program_studi }} / {{ $m->fakultas }}</td>
                    <td class="border border-slate-300 p-2">{{ $m->tugas_tim }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Lembar Pengesahan -->
        <div class="mt-8 pt-4 font-sans text-xs">
            <p class="text-justify mb-6 leading-relaxed">
                Dengan ini menyatakan bahwa data yang diisikan dalam usulan ini adalah benar dan proposal yang diajukan belum pernah didanai oleh sumber pendanaan internal maupun eksternal lain pada tahun anggaran yang sama.
            </p>

            <div class="grid grid-cols-2 gap-8 text-center pt-4">
                <div>
                    <p>Mengetahui,</p>
                    <p class="font-semibold">Dekan / Ketua Program Studi</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline">( ………………………………………………… )</p>
                    <p>NIDN/NIP. ……………………………………</p>
                </div>
                <div>
                    <p>Palembang, {{ date('d F Y') }}</p>
                    <p class="font-semibold">Ketua Pengusul,</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline">{{ $proposal->user->name }}</p>
                    <p>NIDN. {{ $proposal->user->nidn ?? $proposal->user->nip ?? '……………………………………' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
