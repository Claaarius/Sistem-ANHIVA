<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('role')->orderBy('nama_admin')->get();
        return view('admin.manajemen', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|string|min:8|max:20|confirmed',
            'role' => 'required|in:Super Admin,Admin',
        ]);

        Admin::create([
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id_admin === (session('admin')['id'] ?? null)) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $admin->delete();
        return back()->with('success', 'Admin berhasil dihapus.');
    }

    public function showChangePassword()
    {
        return view('admin.password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|max:20|confirmed',
        ]);

        $adminId = session('admin')['id'] ?? null;
        $admin = Admin::findOrFail($adminId);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }

        $admin->update(['password' => $request->password]);
        return back()->with('success', 'Password berhasil diubah.');
    }

    public function updateProfil(Request $request)
    {
        $adminId = session('admin')['id'] ?? null;
        $admin = Admin::findOrFail($adminId);
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $admin->id_admin . ',id_admin',
        ]);

        $admin->update([
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
