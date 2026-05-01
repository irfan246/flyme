<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
            ->route($request->user()->dashboardRoute())
            ->with('success', 'Selamat datang kembali, '.$request->user()->name.'.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $customerRole = Role::where('name', 'customer')->firstOrFail();

        $user = User::create([
            ...$request->validated(),
            'role_id' => $customerRole->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('customer.dashboard')
            ->with('success', 'Registrasi berhasil. Akun customer Anda sudah aktif.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda sudah logout.');
    }
}
