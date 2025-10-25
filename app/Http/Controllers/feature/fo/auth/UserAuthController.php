<?php

// app/Http/Controllers/User/Auth/UserAuthController.php
namespace App\Http\Controllers\feature\fo\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('feature.fo.auth.index');
    }



    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $request->session()->forget('url.intended');

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Setelah login, arahkan ke Count (sesuai requirement)
            return redirect()->route('fo.home.index');
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }


    public function showRegisterForm()
    {
        $payload = session('pre_eligibility_payload'); // optional
        return view('feature.fo.auth.register', compact('payload'));
    }

    // App\Http\Controllers\feature\fo\auth\UserAuthController.php



    public function register(Request $request)
    {
        $data = $request->validate([
            'nik' => ['required', 'string', 'max:32', 'unique:users,nik'],
            'name' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:1000'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:32', 'regex:/^[0-9+][0-9\s\-()]*$/'],
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->forget('url.intended');
        Auth::shouldUse('web');

        $user = \App\Models\User::create([
            'nik' => $data['nik'],
            'name' => $data['name'],
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'alamat' => $data['alamat'],
            'email' => $data['email'],
            'no_hp' => $data['no_hp'],
            'password' => $data['password'], // di-hash via casts
            'role' => 'user',
        ]);

        // bersihkan flag pra-kelayakan (biar tidak dipakai lagi)
        $request->session()->forget(['pre_eligible', 'pre_eligibility_payload']);

        // JANGAN auto-login, arahkan ke login
        return redirect()
            ->route('user.login')
            ->with('success', 'Registrasi berhasil. Silakan login untuk melanjutkan pengajuan.');
    }



    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('fo.home.index');
    }

}
