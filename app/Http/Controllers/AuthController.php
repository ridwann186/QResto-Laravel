<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Hanya staf yang boleh masuk ke dashboard
        if (!in_array($user->role, ['admin', 'cashier', 'kitchen'])) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun ini tidak memiliki akses staf.',
            ]);
        }

        return $this->redirectByRole($user->role);
    }

    // Mengarahkan pengguna sesuai role
    private function redirectByRole($role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.tables');
        }

        if ($role === 'cashier') {
            return redirect()->route('cashier.orders.index');
        }

        if ($role === 'kitchen') {
            return redirect()->route('kitchen.orders.index');
        }

        Auth::logout();

        return redirect()->route('login');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
