<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], ['username' => 'nama pengguna', 'password' => 'kata sandi']);

        $throttleKey = strtolower($request->input('username')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'username' => 'Terlalu banyak percobaan masuk. Coba lagi dalam '.RateLimiter::availableIn($throttleKey).' detik.',
            ]);
        }

        // Boleh masuk memakai username atau email
        $field = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $request->input('username'))->first();

        if ($user && ! $user->is_active) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'username' => 'Akun Anda dinonaktifkan. Hubungi admin prodi.',
            ]);
        }

        $credentials = [
            $field => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'username' => 'Nama pengguna atau kata sandi salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('showcase.index');
    }
}
