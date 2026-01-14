<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Try logging in with username
        if (Auth::guard('client')->attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('portal.dashboard', ['tenant_domain' => $request->getHost()]));
        }

        // Try logging in with phone
        if (Auth::guard('client')->attempt(['phone' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(route('portal.dashboard', ['tenant_domain' => $request->getHost()]));
        }

        return back()->withErrors([
            'username' => 'بيانات الدخول غير صحيحة.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login', ['tenant_domain' => $request->getHost()]);
    }
}
