<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\ProposalMember;
use App\Models\ProposalRab;
use App\Models\ProposalDocument;
use App\Models\Scheme;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Proposal::with(['scheme', 'user'])->latest();

        if ($user->isDosen()) {
            // Dosen can see proposals where they are Ketua or Anggota
            $myProposalIds = Proposal::where('user_id', $user->id)->pluck('id');
            $memberProposalIds = ProposalMember::where('user_id', $user->id)->pluck('proposal_id');
            $allIds = $myProposalIds->merge($memberProposalIds)->unique();
            $query->whereIn('id', $allIds);
        }

        $proposals = $query->paginate(12);
        return view('proposals.index', compact('proposals'));
    }

    public function create()
    {
        $user = Auth::user();
        $schemes = Scheme::where('is_active', true)->get();
        return view('proposals.create', compact('schemes', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'scheme_id' => 'required|exists:schemes,id',
            'judul' => 'required|string|max:500',
            'bidang_fokus' => 'required|string|max:255',
            'tkt' => 'nullable|integer|min:1|max:9',
            'lama_kegiatan' => 'required|integer|min:1|max:12',
            'lokasi_kegiatan' => 'required|string|max:255',
            'ringkasan' => 'required|string|max:2000',
        ]);

        $scheme = Scheme::findOrFail($validated['scheme_id']);
        $validated['user_id'] = $user->id;
        $validated['kategori'] = $scheme->kategori;
        $validated['status'] = 'draft';
        $validated['total_dana_usulan'] = 0;

        $proposal = Proposal::create($validated);

        // Auto-add Ketua as primary member (approved)
        ProposalMember::create([
            'proposal_id' => $proposal->id,
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'nidn_nip' => $user->nidn ?? $user->nip,
            'program_studi' => $user->program_studi,
            'fakultas' => $user->fakultas,
            'peran_tim' => 'Ketua Pengusul',
            'tugas_tim' => 'Penanggung jawab utama penelitian dan pengusulan',
            'status_konfirmasi' => 'approved',
            'tgl_konfirmasi' => now(),
        ]);

        ActivityLog::log('Penyusunan Draf Usulan', 'Membuat draf usulan: ' . $proposal->judul, $proposal->id);

        return redirect()->route('proposals.show', $proposal)->with('success', 'Draf usulan berhasil dibuat. Silakan lengkapi anggota tim, RAB, dan dokumen substansi.');
    }

    public function show(Proposal $proposal)
    {
        $proposal->load([
            'scheme',
            'user',
            'members.user',
            'rabs',
            'documents',
            'reviewerAssignments.reviewer',
            'reviews',
            'contract',
            'disbursements',
            'progressReports.reviewer',
            'finalReports.validator',
            'outputs',
            'activityLogs.user'
        ]);

        $allDosen = User::where('role', 'dosen')->where('id', '!=', Auth::id())->orderBy('name')->get();
        $rabAnalysis = $proposal->rab_analysis;

        return view('proposals.show', compact('proposal', 'allDosen', 'rabAnalysis'));
    }

    public function edit(Proposal $proposal)
    {
        $this->authorizeOwnerOrAdmin($proposal);
        if (!in_array($proposal->status, ['draft', 'admin_revision'])) {
            return back()->with('error', 'Usulan tidak dapat disunting pada status saat ini.');
        }

        $schemes = Scheme::where('is_active', true)->get();
        return view('proposals.edit', compact('proposal', 'schemes'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $this->authorizeOwnerOrAdmin($proposal);

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'bidang_fokus' => 'required|string|max:255',
            'tkt' => 'nullable|integer|min:1|max:9',
            'lama_kegiatan' => 'required|integer|min:1|max:12',
            'lokasi_kegiatan' => 'required|string|max:255',
            'ringkasan' => 'required|string|max:2000',
        ]);

        $proposal->update($validated);

        ActivityLog::log('Pembaruan Draf Usulan', 'Memperbarui data identitas usulan', $proposal->id);

        return redirect()->route('proposals.show', $proposal)->with('success', 'Data usulan berhasil diperbarui.');
    }

    // Add Member to Proposal
    public function addMember(Request $request, Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_lengkap' => 'required_without:user_id|string|max:255',
            'nidn_nip' => 'nullable|string|max:50',
            'program_studi' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'peran_tim' => 'required|string|max:50',
            'tugas_tim' => 'required|string|max:500',
        ]);

        if (!empty($validated['user_id'])) {
            $user = User::find($validated['user_id']);
            $validated['nama_lengkap'] = $user->name;
            $validated['nidn_nip'] = $user->nidn ?? $user->nip;
            $validated['program_studi'] = $user->program_studi;
            $validated['fakultas'] = $user->fakultas;
            $validated['status_konfirmasi'] = 'pending';
        } else {
            $validated['status_konfirmasi'] = 'approved'; // non-system member e.g. mahasiswa
        }

        $validated['proposal_id'] = $proposal->id;
        ProposalMember::create($validated);

        ActivityLog::log('Penambahan Anggota Tim', 'Menambahkan ' . $validated['nama_lengkap'] . ' sebagai ' . $validated['peran_tim'], $proposal->id);

        return back()->with('success', 'Anggota tim berhasil ditambahkan dan notifikasi konfirmasi dikirim.');
    }

    public function removeMember(Proposal $proposal, ProposalMember $member)
    {
        $this->authorizeOwner($proposal);
        if ($member->peran_tim === 'Ketua Pengusul') {
            return back()->with('error', 'Ketua Pengusul tidak dapat dihapus.');
        }

        $nama = $member->nama_lengkap;
        $member->delete();

        ActivityLog::log('Penghapusan Anggota Tim', 'Menghapus anggota: ' . $nama, $proposal->id);

        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }

    // Confirm or reject membership invitation
    public function confirmMembership(Request $request, ProposalMember $member)
    {
        if ($member->user_id !== Auth::id()) {
            abort(403);
        }

        $action = $request->input('action'); // 'approved' or 'rejected'
        if (!in_array($action, ['approved', 'rejected'])) {
            return back()->with('error', 'Aksi tidak valid.');
        }

        $member->update([
            'status_konfirmasi' => $action,
            'tgl_konfirmasi' => now(),
        ]);

        $statusText = $action === 'approved' ? 'menyetujui' : 'menolak';
        ActivityLog::log('Konfirmasi Keikutsertaan Tim', Auth::user()->name . ' ' . $statusText . ' keikutsertaan dalam usulan', $member->proposal_id);

        return back()->with('success', 'Status keikutsertaan Anda berhasil diperbarui menjadi: ' . strtoupper($action));
    }

    // Add RAB item
    public function addRab(Request $request, Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $validated = $request->validate([
            'kategori_biaya' => 'required|in:honorarium,bahan_peralatan,perjalanan,publikasi_luaran,lain_lain',
            'item_biaya' => 'required|string|max:255',
            'volume' => 'required|numeric|min:0.01',
            'satuan' => 'required|string|max:50',
            'harga_satuan' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $totalBiaya = round($validated['volume'] * $validated['harga_satuan'], 2);
        $validated['total_biaya'] = $totalBiaya;
        $validated['proposal_id'] = $proposal->id;

        ProposalRab::create($validated);

        // Recalculate total dana usulan
        $newTotal = $proposal->rabs()->sum('total_biaya');
        $proposal->update(['total_dana_usulan' => $newTotal]);

        ActivityLog::log('Penambahan Komponen RAB', 'Menambahkan item RAB: ' . $validated['item_biaya'], $proposal->id);

        return back()->with('success', 'Item anggaran RAB berhasil ditambahkan.');
    }

    public function removeRab(Proposal $proposal, ProposalRab $rab)
    {
        $this->authorizeOwner($proposal);

        $rab->delete();
        $newTotal = $proposal->rabs()->sum('total_biaya');
        $proposal->update(['total_dana_usulan' => $newTotal]);

        ActivityLog::log('Penghapusan Komponen RAB', 'Menghapus item anggaran RAB', $proposal->id);

        return back()->with('success', 'Item RAB berhasil dihapus.');
    }

    // Upload Document (Substansi, Surat Mitra)
    public function uploadDocument(Request $request, Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $request->validate([
            'jenis_dokumen' => 'required|in:substansi_proposal,surat_mitra,dokumen_pendukung_lain',
            'dokumen_file' => 'required|file|mimes:pdf|max:10240', // max 10MB
        ]);

        $file = $request->file('dokumen_file');
        $originalName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $path = $file->store('proposals/' . $proposal->id, 'public');

        // Delete previous document of same type if exists
        $prev = ProposalDocument::where('proposal_id', $proposal->id)
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->first();

        if ($prev) {
            Storage::disk('public')->delete($prev->file_path);
            $prev->delete();
        }

        ProposalDocument::create([
            'proposal_id' => $proposal->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $fileSize,
        ]);

        ActivityLog::log('Unggah Dokumen Usulan', 'Mengunggah berkas: ' . $originalName, $proposal->id);

        return back()->with('success', 'Dokumen usulan berhasil diunggah.');
    }

    // Add Output target
    public function addOutput(Request $request, Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $validated = $request->validate([
            'jenis_luaran' => 'required|string|max:255',
            'target_uraian' => 'required|string|max:500',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $validated['proposal_id'] = $proposal->id;
        $validated['status_capaian'] = 'draft';

        \App\Models\Output::create($validated);

        return back()->with('success', 'Target luaran wajib berhasil ditambahkan.');
    }

    // Final Submit Proposal (Tahap 2 SOP & SKPL-F-11)
    public function submit(Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        // 1. Check all members confirmed
        if (!$proposal->areAllMembersConfirmed()) {
            return back()->with('error', 'Gagal mengajukan usulan! Masih terdapat anggota tim yang belum mengonfirmasi persetujuan.');
        }

        // 2. Check RAB breakdown percentages compliance
        $rabAnalysis = $proposal->rab_analysis;
        if (!$rabAnalysis['is_all_valid']) {
            return back()->with('error', 'Gagal mengajukan usulan! Terdapat komponen RAB yang melebihi batas persentase maksimal yang diatur pada Bab 7 SOP.');
        }

        if ($proposal->total_dana_usulan <= 0) {
            return back()->with('error', 'Gagal mengajukan usulan! Rincian RAB belum diisi.');
        }

        // 3. Check document substansi uploaded
        $hasSubstansi = $proposal->documents()->where('jenis_dokumen', 'substansi_proposal')->exists();
        if (!$hasSubstansi) {
            return back()->with('error', 'Gagal mengajukan usulan! Berkas PDF Substansi Usulan belum diunggah.');
        }

        // 4. If pengabdian, check surat mitra
        if ($proposal->kategori === 'pengabdian') {
            $hasMitra = $proposal->documents()->where('jenis_dokumen', 'surat_mitra')->exists();
            if (!$hasMitra) {
                return back()->with('error', 'Gagal mengajukan usulan! Skema pengabdian wajib menyertakan Surat Kesediaan Mitra (Lampiran 4).');
            }
        }

        $proposal->update(['status' => 'submitted']);

        ActivityLog::log('Pengajuan Usulan Lengkap', 'Pengusul mengajukan usulan secara resmi untuk verifikasi administrasi LPPM', $proposal->id);

        return back()->with('success', 'Proposal berhasil diajukan ke LPPM! Usulan Anda saat ini sedang menunggu verifikasi administrasi.');
    }

    // Print Lembar Pengesahan / Formulir Identitas (Lampiran 1)
    public function printFormulir(Proposal $proposal)
    {
        $proposal->load(['scheme', 'user', 'members', 'rabs', 'outputs']);
        return view('proposals.print_formulir', compact('proposal'));
    }

    private function authorizeOwner(Proposal $proposal)
    {
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Akses terbatas untuk Ketua Pengusul.');
        }
    }

    private function authorizeOwnerOrAdmin(Proposal $proposal)
    {
        if ($proposal->user_id !== Auth::id() && !Auth::user()->isAdminLppm()) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }
    }
}
