<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required|string|min:8|max:20',
    ]);

    if ($validator->fails()) {
        $errors = $validator->errors();
        $input = $request->only('email');
        if ($errors->has('email')) unset($input['email']);
        return back()->withErrors($errors)->withInput($input);
    }

    // Cek tabel pengguna dulu
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $request->session()->regenerate();
        session(['kode_unik' => Auth::user()->kode_unik]);
        return redirect()->route('dashboard')->with('success', 'Login berhasil!');
    }

    // Cek tabel admin
    $admin = Admin::where('email', $request->email)->first();
    if ($admin && Hash::check($request->password, $admin->password)) {
        // Login via Auth guard agar middleware auth:admin bisa mengenali
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        // Simpan juga di session manual untuk CheckAdmin & CheckSuperAdmin
        session([
            'admin' => [
                'id'    => $admin->id_admin,
                'nama'  => $admin->nama_admin,
                'email' => $admin->email,
                'role'  => $admin->role,
            ]
        ]);

        return redirect()->route('admin.dashboard');
    }

    return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
}

    public function logout(Request $request)
    {
        // Logout admin guard jika admin
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Logout user biasa
        Auth::logout();

        $request->session()->forget('admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dashboard');
    }
}
