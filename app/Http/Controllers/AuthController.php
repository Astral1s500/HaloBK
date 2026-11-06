<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan form login terpusat.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // Jika sudah login di salah satu guard, redirect ke dashboard yang sesuai
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        }

        if (Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi multi-guard (Admin, Guru BK, Siswa).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'identifier.required' => 'Username / NIP / NISN wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');
        $identifier = trim($credentials['identifier']);
        $password = $credentials['password'];

        // 1. Cek Guard: Admin (berdasarkan username)
        if (Auth::guard('admin')->attempt(['username' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))
                ->with('login_success', true)
                ->with('success', 'Selamat datang kembali, Admin ' . Auth::guard('admin')->user()->nama_admin . '!');
        }

        // 2. Cek Guard: Guru BK (berdasarkan NIP)
        if (Auth::guard('guru')->attempt(['nip' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'))
                ->with('login_success', true)
                ->with('success', 'Selamat datang, Bapak/Ibu ' . Auth::guard('guru')->user()->nama_guru . '!');
        }

        // 3. Cek Guard: Siswa (berdasarkan NISN)
        if (Auth::guard('siswa')->attempt(['nisn' => $identifier, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard'))
                ->with('login_success', true)
                ->with('success', 'Halo, ' . Auth::guard('siswa')->user()->nama_siswa . '! Selamat belajar.');
        }

        // Kredensial tidak ditemukan di ketiga guard
        return back()->withErrors([
            'identifier' => 'Kredensial tidak cocok. Pastikan Username / NIP / NISN dan password Anda benar.',
        ])->onlyInput('identifier', 'remember');
    }

    /**
     * Logout dari guard yang sedang aktif dan bersihkan session.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        } elseif (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil logout.');
    }
}
