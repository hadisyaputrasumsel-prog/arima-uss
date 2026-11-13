<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rubrik Penilaian Reviewer - {{ $proposal->judul }}</title>
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
    <div class="max-w-4xl mx-auto mb-4 no-print flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-sm font-sans">
        <span class="text-xs text-slate-500">Pratinjau Cetak: Rubrik Penilaian Reviewer (Lampiran 5 SOP)</span>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm">
            Cetak Dokumen
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-md border border-slate-200">
        <!-- Header Kop -->
        <div class="flex items-center gap-5 pb-4 border-b-2 border-slate-900 mb-6 font-sans">
            <img src="{{ asset('images/logo-mark.png') }}" alt="Logo USS" class="h-16 w-16 object-contain shrink-0">
            <div class="flex-1 text-center">
                <h2 class="text-xl font-bold tracking-tight uppercase text-slate-900">UNIVERSITAS SUMATERA SELATAN</h2>
                <h3 class="text-sm font-bold uppercase text-slate-700">LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</h3>
                <p class="text-xs text-slate-500 mt-1">Jl. Kolonel H. Barlian No. 12, Palembang, Sumatera Selatan &bull; Surel: lppm@uss.ac.id</p>
            </div>
        </div>

        <div class="text-center mb-6">
            <h3 class="text-base font-bold uppercase tracking-wider underline">RUBRIK PENILAIAN REVIEWER (LAMPIRAN 5)</h3>
            <p class="text-xs uppercase font-sans mt-0.5">SOP Hibah Penelitian dan Pengabdian Internal USS</p>
        </div>

        <table class="w-full text-xs mb-6 font-sans border-collapse">
            <tr class="border-b border-slate-200">
                <td class="py-1.5 font-bold w-1/3">Judul Usulan</td>
                <td class="py-1.5">: {{ $proposal->judul }}</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-1.5 font-bold">Skema Hibah</td>
                <td class="py-1.5">: {{ $proposal->scheme->nama_skema }} (Tahun {{ $proposal->scheme->tahun_anggaran }})</td>
            </tr>
            <tr class="border-b border-slate-200">
                <td class="py-1.5 font-bold">Kode Reviewer</td>
                <td class="py-1.5">: REV-USS-{{ sprintf('%03d', $review->reviewer_id) }} (Kerahasiaan Terjaga)</td>
            </tr>
        </table>

        <!-- Table Rubrik -->
        <table class="w-full text-xs mb-6 font-sans border border-slate-400 border-collapse">
            <thead class="bg-slate-100 font-bold text-center">
                <tr>
                    <th class="border border-slate-400 p-2 w-10">No</th>
                    <th class="border border-slate-400 p-2 text-left">Kriteria Penilaian</th>
                    <th class="border border-slate-400 p-2 w-20">Bobot</th>
                    <th class="border border-slate-400 p-2 w-24">Skor (1 - 5)</th>
                    <th class="border border-slate-400 p-2 w-28">Nilai Terbobot</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-slate-400 p-2 text-center">1</td>
                    <td class="border border-slate-400 p-2">
                        <strong>Kualitas Metodologi</strong>
                        <p class="text-[11px] text-slate-600">Ketepatan pendekatan & kelayakan pelaksanaan</p>
                    </td>
                    <td class="border border-slate-400 p-2 text-center">30%</td>
                    <td class="border border-slate-400 p-2 text-center font-bold">{{ $review->skor_metodologi }}</td>
                    <td class="border border-slate-400 p-2 text-center">{{ number_format(0.30 * $review->skor_metodologi * 20, 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-400 p-2 text-center">2</td>
                    <td class="border border-slate-400 p-2">
                        <strong>Kelayakan Anggaran / RAB</strong>
                        <p class="text-[11px] text-slate-600">Kewajaran biaya & kepatuhan plafon persentase</p>
                    </td>
                    <td class="border border-slate-400 p-2 text-center">20%</td>
                    <td class="border border-slate-400 p-2 text-center font-bold">{{ $review->skor_rab }}</td>
                    <td class="border border-slate-400 p-2 text-center">{{ number_format(0.20 * $review->skor_rab * 20, 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-400 p-2 text-center">3</td>
                    <td class="border border-slate-400 p-2">
                        <strong>Potensi Ketercapaian Luaran</strong>
                        <p class="text-[11px] text-slate-600">Kejelasan target jurnal SINTA, HKI, modul pengabdian</p>
                    </td>
                    <td class="border border-slate-400 p-2 text-center">30%</td>
                    <td class="border border-slate-400 p-2 text-center font-bold">{{ $review->skor_luaran }}</td>
                    <td class="border border-slate-400 p-2 text-center">{{ number_format(0.30 * $review->skor_luaran * 20, 2) }}</td>
                </tr>
                <tr>
                    <td class="border border-slate-400 p-2 text-center">4</td>
                    <td class="border border-slate-400 p-2">
                        <strong>Rekam Jejak Tim Pengusul</strong>
                        <p class="text-[11px] text-slate-600">Relevansi pengalaman & publikasi terdahulu</p>
                    </td>
                    <td class="border border-slate-400 p-2 text-center">20%</td>
                    <td class="border border-slate-400 p-2 text-center font-bold">{{ $review->skor_rekam_jejak }}</td>
                    <td class="border border-slate-400 p-2 text-center">{{ number_format(0.20 * $review->skor_rekam_jejak * 20, 2) }}</td>
                </tr>
                <tr class="bg-slate-50 font-bold">
                    <td colspan="4" class="border border-slate-400 p-2 text-right">TOTAL NILAI AKHIR (Skala 100):</td>
                    <td class="border border-slate-400 p-2 text-center text-sm font-black">{{ $review->nilai_akhir }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mb-6 font-sans text-xs">
            <span class="font-bold block mb-1">Rekomendasi Hasil Penilaian:</span>
            <div class="p-3 bg-slate-50 rounded border border-slate-300 font-bold uppercase text-slate-800">
                [ X ] {{ $review->rekomendasi_label }}
            </div>
        </div>

        <div class="mb-8 font-sans text-xs">
            <span class="font-bold block mb-1">Catatan Evaluasi untuk Pengusul (Blind Review):</span>
            <div class="p-3 bg-slate-50 rounded border border-slate-300 whitespace-pre-line text-slate-700 leading-relaxed">
                {{ $review->catatan_umum }}
            </div>
        </div>

        <div class="pt-6 font-sans text-xs text-right">
            <p>Palembang, {{ $review->created_at->format('d F Y') }}</p>
            <p class="font-semibold">Reviewer Internal,</p>
            <div class="h-20"></div>
            <p class="font-bold underline">( Kode: REV-USS-{{ sprintf('%03d', $review->reviewer_id) }} )</p>
            <p class="text-[10px] text-slate-400 italic">Identitas reviewer dijaga kerahasiaannya oleh LPPM USS</p>
        </div>
    </div>
</body>
</html>
