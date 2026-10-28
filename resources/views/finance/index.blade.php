@extends('layouts.app')

@section('title', 'Pencairan Dana Hibah Internal (Bagian Keuangan)')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengelolaan Pencairan Dana Bertahap</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Tahap 7 & 12 SOP: Pencairan Termin I (70% setelah kontrak) dan Termin II (30% setelah validasi luaran).</p>
    </div>

    <!-- Tagihan Menunggu Pencairan -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center">
            <i class="fa-solid fa-hourglass-start text-amber-500 mr-2"></i>
            Daftar Tagihan Menunggu Pencairan (Pending Transfer)
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3">Termin</th>
                        <th class="py-3 px-3">Judul Usulan & Penerima</th>
                        <th class="py-3 px-3">Rekening Bank</th>
                        <th class="py-3 px-3">Nominal Pencairan</th>
                        <th class="py-3 px-3">Catatan / Dasar</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendingDisbursements as $disb)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800">
                                {{ $disb->termin_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 max-w-xs">
                            <a href="{{ route('proposals.show', $disb->proposal) }}" class="font-bold text-slate-900 hover:text-blue-600 block line-clamp-2">
                                {{ $disb->proposal->judul }}
                            </a>
                            <span class="text-slate-500 block mt-0.5">Ketua: <strong>{{ $disb->proposal->user->name }}</strong></span>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $disb->proposal->user->bank_name ?? 'Bank Sumsel Babel' }}</div>
                            <div class="text-[11px] text-slate-500">No: {{ $disb->proposal->user->bank_account_number ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400">a.n {{ $disb->proposal->user->bank_account_holder ?? $disb->proposal->user->name }}</div>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <div class="text-sm font-black text-slate-900">Rp {{ number_format($disb->nominal, 0, ',', '.') }}</div>
                            <span class="text-[10px] text-slate-400">{{ $disb->persentase }}% dari total dana</span>
                        </td>
                        <td class="py-3.5 px-3 text-slate-500 max-w-xs">
                            {{ $disb->catatan }}
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                            <div x-data="{ openModal: false }">
                                <button @click="openModal = true" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm">
                                    <i class="fa-solid fa-money-bill-transfer mr-1"></i> Proses Transfer
                                </button>

                                <!-- Modal Transfer Form -->
                                <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                                    <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl border border-slate-200">
                                        <h4 class="font-extrabold text-slate-900 text-base mb-1">Konfirmasi Pembayaran Transfer</h4>
                                        <p class="text-xs text-slate-500 mb-4 line-clamp-1">{{ $disb->proposal->judul }}</p>

                                        <form method="POST" action="{{ route('finance.disbursements.process', $disb) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                                            @csrf
                                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                                                <span class="text-emerald-700 block font-semibold text-[11px]">Jumlah Ditransfer:</span>
                                                <strong class="text-lg font-black text-emerald-800">Rp {{ number_format($disb->nominal, 0, ',', '.') }}</strong>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Tanggal Transfer</label>
                                                <input type="date" name="tanggal_cair" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Nomor Referensi / Transaksi Bank</label>
                                                <input type="text" name="nomor_transaksi" placeholder="Contoh: TRF-202610-00123" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Unggah Bukti Transfer (PDF / Gambar)</label>
                                                <input type="file" name="bukti_transfer" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 mb-1">Catatan Tambahan</label>
                                                <textarea name="catatan" rows="2" placeholder="Catatan perbendaharaan..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg">{{ $disb->catatan }}</textarea>
                                            </div>

                                            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                                                <button type="button" @click="openModal = false" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-bold">Batal</button>
                                                <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-sm">Konfirmasi Telah Cair</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">Tidak ada tagihan pencairan yang pending saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Pencairan Telah Ditransfer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-extrabold text-slate-900 text-base mb-4 flex items-center">
            <i class="fa-solid fa-receipt text-emerald-600 mr-2"></i>
            Riwayat Pencairan Dana yang Telah Selesai
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 uppercase text-[10px] tracking-wider font-extrabold">
                        <th class="py-3 px-3">Tanggal & No Transaksi</th>
                        <th class="py-3 px-3">Judul Usulan & Penerima</th>
                        <th class="py-3 px-3">Termin</th>
                        <th class="py-3 px-3">Nominal Cair</th>
                        <th class="py-3 px-3">Petugas Keuangan</th>
                        <th class="py-3 px-3 text-right">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($paidDisbursements as $paid)
                    <tr>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $paid->tanggal_cair ? $paid->tanggal_cair->format('d M Y') : '-' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $paid->nomor_transaksi ?? '-' }}</div>
                        </td>
                        <td class="py-3.5 px-3 max-w-sm">
                            <span class="font-bold text-slate-900 block line-clamp-1">{{ $paid->proposal->judul }}</span>
                            <span class="text-slate-500 text-[11px]">Ketua: {{ $paid->proposal->user->name }}</span>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded font-bold text-[10px] bg-slate-100 text-slate-700">
                                {{ $paid->termin_label }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap font-black text-slate-900">
                            Rp {{ number_format($paid->nominal, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-3 whitespace-nowrap text-slate-600">
                            {{ $paid->processedBy->name ?? 'Bagian Keuangan' }}
                        </td>
                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                            @if($paid->bukti_transfer)
                            <a href="{{ asset('storage/' . $paid->bukti_transfer) }}" target="_blank" class="px-2.5 py-1 rounded bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">
                                Bukti &rarr;
                            </a>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">Belum ada riwayat transaksi pencairan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $paidDisbursements->links() }}
        </div>
    </div>
</div>
@endsection
