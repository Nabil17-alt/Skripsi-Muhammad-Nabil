<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'captcha' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (strtoupper($value) !== session('captcha_code')) {
                        $fail('CAPTCHA yang dimasukkan tidak valid.');
                    }
                },
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'CAPTCHA wajib diisi.',
        ]);

        $user = User::where('email', $credentials['email'])->where('is_active', true)->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah, atau akun tidak aktif.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->role && in_array($user->role->name, ['admin', 'pimpinan'])) {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->intended(route('home'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tb_users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (strtoupper($value) !== session('captcha_code')) {
                        $fail('CAPTCHA yang dimasukkan tidak valid.');
                    }
                },
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'captcha.required' => 'CAPTCHA wajib diisi.',
        ]);

        $customerRole = Role::where('name', 'customer')->first();

        $user = User::create([
            'role_id' => $customerRole?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
