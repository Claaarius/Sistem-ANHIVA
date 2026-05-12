<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            // Simpan juga di session manual untuk CheckAdmin & CheckSuperAdmin
            $admin = Auth::guard('admin')->user();
            session([
                'admin' => [
                    'id' => $admin->id_admin,
                    'nama' => $admin->nama_admin,
                    'email' => $admin->email,
                    'role' => $admin->role,
                ]
            ]);

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil sebagai Admin!');
        }

        return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->forget('admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
