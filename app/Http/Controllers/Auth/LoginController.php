<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'not_regex:/[\r\n]/'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah. Silakan coba lagi.']);
        }

        $request->session()->regenerate();

        return redirect($this->intendedTarget(Auth::user()));
    }

    /**
     * URL tujuan setelah login. `url.intended` dari session hanya dipakai bila
     * path lokal (diawali '/'); nilai absolut dari luar situs diabaikan agar
     * session yang di-poison tidak mengarahkan ke situs lain (open redirect).
     */
    protected function intendedTarget($user): string
    {
        $intended = (string) session()->get('url.intended', '');

        if ($intended !== '' && str_starts_with($intended, '/')) {
            return $intended;
        }

        return $this->homeFor($user);
    }

    protected function homeFor($user): string
    {
        return $user->isStaff() ? route('guru.dashboard') : route('dashboard');
    }
}