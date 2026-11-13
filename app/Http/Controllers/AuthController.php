<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login_id.required' => 'Email atau NIDN wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginId = trim($credentials['login_id']);
        $throttleKey = Str::transliterate(Str::lower($loginId).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login_id' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        $email = null;

        // Check if loginId is an email
        if (filter_var($loginId, FILTER_VALIDATE_EMAIL)) {
            $email = $loginId;
        } else {
            // Find by NIDN or NIP in lecturers table
            $lecturer = Lecturer::where('nidn', $loginId)
                ->orWhere('nip', $loginId)
                ->first();

            if ($lecturer && $lecturer->user) {
                $email = $lecturer->user->email;
            }
        }

        if (!$email || !Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login_id' => 'Kombinasi Email/NIDN dan kata sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'login_id' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin LPPM.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}

