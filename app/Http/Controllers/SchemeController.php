<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = Scheme::withCount('proposals')->orderBy('is_active', 'desc')->latest()->get();
        return view('schemes.index', compact('schemes'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('schemes.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'tahun_anggaran' => 'required|string|size:4',
            'kategori' => 'required|in:penelitian,pengabdian',
            'pagu_anggaran' => 'required|numeric|min:0',
            'maks_dana_usulan' => 'required|numeric|min:0',
            'tgl_mulai_pengusulan' => 'required|date',
            'tgl_akhir_pengusulan' => 'required|date|after_or_equal:tgl_mulai_pengusulan',
            'tgl_mulai_review' => 'nullable|date',
            'tgl_akhir_review' => 'nullable|date',
            'tgl_pengumuman' => 'nullable|date',
            'tgl_monev' => 'nullable|date',
            'tgl_laporan_akhir' => 'nullable|date',
            'panduan_file' => 'nullable|file|mimes:pdf|max:10240',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('panduan_file')) {
            $path = $request->file('panduan_file')->store('guidelines', 'public');
            $validated['panduan_file'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $scheme = Scheme::create($validated);

        ActivityLog::log('Publikasi Skema Hibah', 'Admin LPPM menambahkan skema baru: ' . $scheme->nama_skema);

        return redirect()->route('schemes.index')->with('success', 'Skema hibah berhasil ditambahkan dan disosialisasikan.');
    }

    public function show(Scheme $scheme)
    {
        $scheme->load(['proposals' => function ($q) {
            $q->with('user')->latest();
        }]);
        return view('schemes.show', compact('scheme'));
    }

    public function edit(Scheme $scheme)
    {
        $this->authorizeAdmin();
        return view('schemes.edit', compact('scheme'));
    }

    public function update(Request $request, Scheme $scheme)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'tahun_anggaran' => 'required|string|size:4',
            'kategori' => 'required|in:penelitian,pengabdian',
            'pagu_anggaran' => 'required|numeric|min:0',
            'maks_dana_usulan' => 'required|numeric|min:0',
            'tgl_mulai_pengusulan' => 'required|date',
            'tgl_akhir_pengusulan' => 'required|date',
            'tgl_mulai_review' => 'nullable|date',
            'tgl_akhir_review' => 'nullable|date',
            'tgl_pengumuman' => 'nullable|date',
            'tgl_monev' => 'nullable|date',
            'tgl_laporan_akhir' => 'nullable|date',
            'panduan_file' => 'nullable|file|mimes:pdf|max:10240',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('panduan_file')) {
            if ($scheme->panduan_file) {
                Storage::disk('public')->delete($scheme->panduan_file);
            }
            $validated['panduan_file'] = $request->file('panduan_file')->store('guidelines', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $scheme->update($validated);

        ActivityLog::log('Perubahan Skema Hibah', 'Admin memperbarui skema: ' . $scheme->nama_skema);

        return redirect()->route('schemes.show', $scheme)->with('success', 'Skema hibah berhasil diperbarui.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->isAdminLppm()) {
            abort(403, 'Akses terbatas untuk Admin LPPM.');
        }
    }
}
