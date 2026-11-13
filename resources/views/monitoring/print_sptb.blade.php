<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPTB Kemajuan 70% - {{ $proposal->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt !important; line-height: 1.4 !important; }
            @page { margin: 20mm 20mm 20mm 20mm; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 font-serif text-slate-900">
    <!-- Print toolbar -->
    <div class="max-w-4xl mx-auto mb-4 no-print flex items-center justify-between bg-white p-4 rounded-xl border border-slate-200 shadow-sm font-sans">
        <div class="flex items-center space-x-2 text-xs text-slate-600">
            <i class="fa-solid fa-file-invoice-dollar text-blue-600 text-base"></i>
            <span>Pratinjau Cetak: <strong>Surat Pernyataan Tanggung Jawab Belanja (SPTB) Kemajuan 70%</strong></span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.history.back()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                Kembali
            </button>
            <button onclick="window.print()" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Dokumen (PDF)</span>
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-md border border-slate-200">
        <!-- Header Kop Kampus -->
        <div class="flex items-center gap-5 pb-4 border-b-2 border-slate-900 mb-6 font-sans">
            <img src="{{ asset('images/logo-mark.png') }}" alt="Logo USS" class="h-20 w-20 object-contain shrink-0">
            <div class="flex-1 text-center">
                <h2 class="text-xl font-bold tracking-tight uppercase text-slate-900">UNIVERSITAS SUMATERA SELATAN</h2>
                <h3 class="text-sm font-bold uppercase text-slate-700">LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT (LPPM)</h3>
                <p class="text-xs text-slate-500 mt-1">Jl. Kolonel H. Barlian No. 12, Palembang, Sumatera Selatan &bull; Surel: lppm@uss.ac.id</p>
            </div>
        </div>

        <div class="text-center mb-6">
            <h3 class="text-base font-bold uppercase tracking-wider underline">SURAT PERNYATAAN TANGGUNG JAWAB BELANJA (SPTB)</h3>
            <p class="text-xs font-bold uppercase font-sans mt-0.5 tracking-wide text-slate-700">PENCAIRAN TAHAP I (KEMAJUAN 70%)</p>
            <p class="text-[11px] font-sans text-slate-500 mt-0.5">SOP Hibah Penelitian dan Pengabdian Internal (No: SOP/LPPM-USS/HI-PPM)</p>
        </div>

        <div class="font-sans text-xs space-y-4 mb-6 leading-relaxed">
            <p>Yang bertanda tangan di bawah ini:</p>

            <table class="w-full text-xs border-collapse font-sans ml-4 mb-3">
                <tr>
                    <td class="py-1 font-semibold w-1/3">1. Nama Lengkap</td>
                    <td class="py-1">: <strong>{{ $proposal->user->name }}</strong></td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">2. NIDN / NIP</td>
                    <td class="py-1">: {{ $proposal->user->nidn ?? ($proposal->user->nip ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">3. Program Studi / Fakultas</td>
                    <td class="py-1">: {{ $proposal->user->program_studi ?? '-' }} / {{ $proposal->user->fakultas ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">4. Jabatan Fungsional</td>
                    <td class="py-1">: {{ $proposal->user->jabatan_fungsional ?? 'Dosen' }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">5. Judul Kegiatan</td>
                    <td class="py-1">: {{ $proposal->judul }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">6. Skema Hibah</td>
                    <td class="py-1">: {{ $proposal->scheme->nama_skema }} (Tahun Anggaran {{ $proposal->scheme->tahun_anggaran }})</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">7. Nomor SK / Kontrak Penugasan</td>
                    <td class="py-1">: {{ $proposal->sk_penetapan_no ?? ($proposal->contract->nomor_kontrak ?? 'SK/LPPM-USS/HIBAH/' . date('Y') . '/' . sprintf('%03d', $proposal->id)) }}</td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">8. Tanggal SK / Kontrak</td>
                    <td class="py-1">: {{ $proposal->sk_penetapan_tgl ? $proposal->sk_penetapan_tgl->format('d F Y') : ($proposal->contract && $proposal->contract->tgl_kontrak ? $proposal->contract->tgl_kontrak->format('d F Y') : date('d F Y')) }}</td>
                </tr>
            </table>

            <p class="mt-4">
                Dengan ini menyatakan dengan sebenar-benarnya dan penuh tanggung jawab bahwa:
            </p>

            <ol class="list-decimal pl-6 space-y-2 text-justify">
                <li>
                    Berdasarkan Kontrak Perjanjian Penugasan Pelaksanaan Hibah Penelitian/Pengabdian Internal, telah menerima pencairan <strong>Dana Tahap I sebesar 70%</strong> dengan rincian keuangan sebagai berikut:
                </li>
            </ol>

            <!-- Table Rincian Keuangan 70% -->
            <table class="w-full text-xs font-sans border border-slate-300 mt-2 mb-4">
                <thead class="bg-slate-100 font-bold border-b border-slate-300">
                    <tr>
                        <th class="p-2 text-left border-r border-slate-300">Uraian Komponen Pendanaan</th>
                        <th class="p-2 text-right w-44">Jumlah (Rupiah)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-2 border-r border-slate-300">Total Plafon Anggaran Disetujui (100%)</td>
                        <td class="p-2 text-right font-medium">Rp {{ number_format($proposal->dana_disetujui ?? $proposal->total_dana_usulan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-blue-50/50">
                        <td class="p-2 border-r border-slate-300 font-bold">Dana Tahap I Diterima (Termin 70%)</td>
                        <td class="p-2 text-right font-bold text-blue-900">Rp {{ number_format($danaTermin1, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 border-r border-slate-300">Dana Terpakai / Dibelanjakan s.d. Laporan Kemajuan Ini</td>
                        <td class="p-2 text-right font-semibold text-emerald-800">Rp {{ number_format($danaTerpakai, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-slate-50">
                        <td class="p-2 border-r border-slate-300">Sisa Saldo Dana Termin I</td>
                        <td class="p-2 text-right font-semibold text-slate-700">Rp {{ number_format($sisaDana, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <ol start="2" class="list-decimal pl-6 space-y-2 text-justify">
                <li>
                    Jumlah uang tersebut pada butir 1 telah benar-benar dikeluarkan dan dibelanjakan untuk keperluan pelaksanaan kegiatan penelitian/pengabdian sesuai dengan Rencana Anggaran Biaya (RAB) yang telah disetujui LPPM Universitas Sumatera Selatan.
                </li>
                <li>
                    Seluruh bukti-bukti pengeluaran yang sah (kuitansi, nota, faktur, bukti potong pajak, dan bukti transfer) disimpan secara lengkap oleh Ketua Pelaksana untuk keperluan pemeriksaan sewaktu-waktu oleh Tim Monitoring dan Evaluasi Internal, Satuan Pengawas Internal (SPI), atau Auditor yang ditugaskan oleh Universitas Sumatera Selatan.
                </li>
                <li>
                    Apabila di kemudian hari terdapat kelebihan pembayaran, ketidaksesuaian pos belanja terhadap ketentuan persentase maksimal RAB, atau kerugian yang timbul akibat kelalaian pelaksanaan kegiatan, saya bertanggung jawab sepenuhnya dan bersedia menyetorkan kembali kelebihan/kerugian tersebut ke kas Universitas Sumatera Selatan.
                </li>
            </ol>

            <p class="mt-4">
                Demikian Surat Pernyataan Tanggung Jawab Belanja (SPTB) Kemajuan ini dibuat dengan sebenar-benarnya tanpa paksaan dari pihak manapun untuk dipergunakan sebagai kelengkapan dokumen Monitoring & Evaluasi serta syarat administrasi pencairan dana tahap berikutnya.
            </p>
        </div>

        <!-- Tanda Tangan -->
        <div class="mt-8 pt-4 grid grid-cols-2 gap-8 font-sans text-xs">
            <div class="text-center">
                <p class="text-slate-500 mb-1">Mengetahui,</p>
                <p class="font-bold">Kepala LPPM</p>
                <p class="font-bold">Universitas Sumatera Selatan</p>
                <div class="h-20 flex items-center justify-center text-slate-400 italic text-[11px]">
                    (Tanda tangan & cap)
                </div>
                <p class="font-bold underline text-slate-900">Pengelola LPPM USS</p>
                <p class="text-slate-500 text-[11px]">NIDN. 0211047801</p>
            </div>

            <div class="text-center">
                <p class="text-slate-500 mb-1">Palembang, {{ $report && $report->created_at ? $report->created_at->format('d F Y') : date('d F Y') }}</p>
                <p class="font-bold">Ketua Pelaksana Kegiatan,</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="px-3 py-1 border border-dashed border-slate-400 text-slate-400 text-[10px] uppercase rounded">
                        Materai Rp 10.000
                    </span>
                </div>
                <p class="font-bold underline text-slate-900">{{ $proposal->user->name }}</p>
                <p class="text-slate-500 text-[11px]">NIDN/NIP. {{ $proposal->user->nidn ?? ($proposal->user->nip ?? '-') }}</p>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-200 text-[10px] text-slate-400 font-sans flex items-center justify-between no-print">
            <span>ARIMA USS &bull; Sistem Tata Kelola Hibah Internal</span>
            <span>Dicetak secara otomatis pada {{ date('d/m/Y H:i') }} WIB</span>
        </div>
    </div>
</body>
</html>
