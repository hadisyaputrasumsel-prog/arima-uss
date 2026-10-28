<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $demoUsers = User::orderBy('id')->get();
        return view('auth.login', compact('demoUsers'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            ActivityLog::log('Login ke Sistem', 'Pengguna berhasil login ke ARIMA');
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    // Quick login for development / demonstration
    public function quickLogin($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        request()->session()->regenerate();
        ActivityLog::log('Login Cepat (Demo)', 'Login menggunakan akun demo: ' . $user->name);
        return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai ' . $user->name . ' (' . $user->role_label . ')');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log('Logout dari Sistem', 'Pengguna keluar dari aplikasi');
            Auth::logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar.');
    }
}
