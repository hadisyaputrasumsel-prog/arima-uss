<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdminTi() && !Auth::user()->isAdminLppm()) {
                abort(403, 'Akses khusus Admin TI / LPPM.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin_ti,admin_lppm,dosen,reviewer,keuangan,pimpinan',
            'nidn' => 'nullable|string|max:50|unique:users,nidn',
            'nip' => 'nullable|string|max:50',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'program_studi' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        ActivityLog::log('Pembuatan Akun Pengguna', 'Menambahkan pengguna baru: ' . $user->name . ' (' . $user->role_label . ')');

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin_ti,admin_lppm,dosen,reviewer,keuangan,pimpinan',
            'nidn' => 'nullable|string|max:50|unique:users,nidn,' . $user->id,
            'nip' => 'nullable|string|max:50',
            'jabatan_fungsional' => 'nullable|string|max:100',
            'fakultas' => 'nullable|string|max:100',
            'program_studi' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::log('Pembaruan Akun Pengguna', 'Memperbarui data pengguna: ' . $user->name);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        ActivityLog::log('Penghapusan Akun Pengguna', 'Menghapus akun: ' . $nama);

        return back()->with('success', 'Pengguna ' . $nama . ' berhasil dihapus.');
    }
}
