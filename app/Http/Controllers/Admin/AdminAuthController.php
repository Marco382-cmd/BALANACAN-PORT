<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    // Hardcoded admin credentials — replace with DB-based auth if needed
    private const ADMIN_EMAIL    = 'admin@balanacanport.gov.ph';
    private const ADMIN_PASSWORD = 'admin1234';

    public function showLogin(): View|RedirectResponse
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (
            $request->email    === self::ADMIN_EMAIL &&
            $request->password === self::ADMIN_PASSWORD
        ) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_email', $request->email);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid credentials. Please try again.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['admin_logged_in', 'admin_email']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out.');
    }
}