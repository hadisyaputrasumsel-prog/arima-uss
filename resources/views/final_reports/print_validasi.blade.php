<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Validasi Luaran - {{ $proposal->judul }}</title>
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
        <span class="text-xs text-slate-500">Pratinjau Cetak: Berita Acara Validasi Luaran (Lampiran 8 SOP)</span>
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
            <h3 class="text-base font-bold uppercase tracking-wider underline">BERITA ACARA VALIDASI LUARAN (LAPORAN AKHIR)</h3>
            <p class="text-xs uppercase font-sans mt-0.5">Lampiran 8 &bull; SOP Hibah Penelitian dan Pengabdian Internal USS</p>
        </div>

        <div class="font-sans text-xs space-y-4 mb-6 leading-relaxed">
            <p>
                Pada hari ini, tanggal {{ $report->validated_at ? $report->validated_at->format('d F Y') : date('d F Y') }}, telah dilaksanakan pemeriksaan dan validasi pemenuhan target luaran wajib terhadap usulan penelitian / pengabdian kepada masyarakat:
            </p>

            <table class="w-full text-xs border-collapse">
                <tr class="border-b border-slate-200">
                    <td class="py-1.5 font-bold w-1/3">Judul Kegiatan</td>
                    <td class="py-1.5">: {{ $proposal->judul }}</td>
                </tr>
                <tr class="border-b border-slate-200">
                    <td class="py-1.5 font-bold">Skema Hibah</td>
                    <td class="py-1.5">: {{ $proposal->scheme->nama_skema }} (Tahun {{ $proposal->scheme->tahun_anggaran }})</td>
                </tr>
                <tr class="border-b border-slate-200">
                    <td class="py-1.5 font-bold">Ketua Pelaksana</td>
                    <td class="py-1.5">: {{ $proposal->user->name }} (NIDN: {{ $proposal->user->nidn ?? $proposal->user->nip ?? '-' }})</td>
                </tr>
                <tr class="border-b border-slate-200">
                    <td class="py-1.5 font-bold">Total Nilai Kontrak</td>
                    <td class="py-1.5">: Rp {{ number_format($proposal->contract->nilai_kontrak ?? $proposal->total_dana_usulan, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b border-slate-200">
                    <td class="py-1.5 font-bold">Total Realisasi Anggaran</td>
                    <td class="py-1.5">: Rp {{ number_format($report->total_realisasi_dana, 0, ',', '.') }} (100%)</td>
                </tr>
            </table>

            <!-- Luaran yang Divalidasi -->
            <div class="pt-2">
                <span class="font-bold block mb-1">Hasil Pemeriksaan Luaran Wajib yang Dijanjikan:</span>
                <table class="w-full border border-slate-300 border-collapse text-xs">
                    <thead class="bg-slate-50 font-bold">
                        <tr>
                            <th class="border border-slate-300 p-2 text-center w-10">No</th>
                            <th class="border border-slate-300 p-2">Jenis Luaran</th>
                            <th class="border border-slate-300 p-2">Target Janji Usulan</th>
                            <th class="border border-slate-300 p-2 text-center">Status Pemenuhan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposal->outputs as $idx => $out)
                        <tr>
                            <td class="border border-slate-300 p-2 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 p-2 font-semibold">{{ $out->jenis_luaran }}</td>
                            <td class="border border-slate-300 p-2">{{ $out->target_uraian }}</td>
                            <td class="border border-slate-300 p-2 text-center font-bold text-emerald-700">
                                {{ $out->is_verified ? 'SAH & VALID' : strtoupper($out->status_capaian) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                <span class="font-bold block mb-1">Catatan Evaluasi Tim Reviewer / LPPM:</span>
                <div class="p-3 bg-slate-50 border border-slate-300 rounded leading-relaxed">
                    {{ $report->catatan_validasi }}
                </div>
            </div>

            <div class="pt-2">
                <span class="font-bold block mb-1">Kesimpulan & Rekomendasi:</span>
                <div class="p-3 bg-slate-50 border border-slate-300 rounded font-bold uppercase text-slate-800">
                    [ X ] {{ $report->status_validasi === 'valid' ? 'Seluruh luaran wajib dinyatakan Sah & Valid. Rekomendasi pencairan dana tahap II (pelunasan 30%) dapat diproses.' : 'Luaran belum memenuhi ketentuan wajib. Perlu perbaikan sebelum pencairan pelunasan.' }}
                </div>
            </div>
        </div>

        <p class="font-sans text-xs mb-8">Demikian Berita Acara Validasi Luaran ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagai dasar pencairan dana tahap akhir (pelunasan).</p>

        <!-- Tanda Tangan 3 Pihak -->
        <div class="grid grid-cols-3 gap-4 text-center font-sans text-xs">
            <div>
                <p>Validator / Reviewer,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $report->validator->name ?? 'Tim Reviewer LPPM' }}</p>
                <p>NIDN. {{ $report->validator->nidn ?? '……………………………' }}</p>
            </div>
            <div>
                <p>Ketua Pelaksana,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $proposal->user->name }}</p>
                <p>NIDN. {{ $proposal->user->nidn ?? $proposal->user->nip ?? '……………………………' }}</p>
            </div>
            <div>
                <p>Mengetahui,</p>
                <p>Kepala LPPM USS</p>
                <div class="h-20"></div>
                <p class="font-bold underline">( ………………………………………… )</p>
                <p>NIDN/NIP. ……………………………</p>
            </div>
        </div>
    </div>
</body>
</html>
