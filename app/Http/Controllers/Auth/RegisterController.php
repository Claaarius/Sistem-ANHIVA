<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:pengguna,username',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:8|max:20|confirmed',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $input = $request->except(['password', 'password_confirmation']);
            if ($errors->has('email')) {
                unset($input['email']);
            }

            return back()->withErrors($errors)->withInput($input);
        }

        // Generate unique code format ANH-XXXXXXXXXX
        do {
            $kodeUnik = 'ANH-' . strtoupper(Str::random(10));
        } while (Pengguna::where('kode_unik', $kodeUnik)->exists());

        $pengguna = Pengguna::create([
            'kode_unik' => $kodeUnik,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'tanggal_daftar' => now(),
        ]);

        Auth::login($pengguna);
        session(['kode_unik' => $kodeUnik]);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Kode unik Anda: ' . $kodeUnik);
    }
}
