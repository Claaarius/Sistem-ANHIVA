<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use App\Models\Komentar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function index()
    {
        $pengguna = Auth::guard('web')->user();
        $riwayatKomentar = Komentar::where('id_pengguna', $pengguna->id_pengguna)
                            ->orderBy('tanggal_komentar', 'desc')
                            ->get();
        return view('akun.index', compact('pengguna', 'riwayatKomentar'));
    }

    public function updateProfil(Request $request)
    {
        $pengguna = Auth::guard('web')->user();
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('pengguna', 'username')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'email'    => ['required', 'email', Rule::unique('pengguna', 'email')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data = [
            'username' => $request->username,
            'email'    => $request->email,
        ];

        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($pengguna->foto_profil && Storage::disk('public')->exists($pengguna->foto_profil)) {
                Storage::disk('public')->delete($pengguna->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $pengguna->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|max:20|confirmed',
        ]);

        $pengguna = Auth::guard('web')->user();

        if (!Hash::check($request->current_password, $pengguna->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $pengguna->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diubah.');
    }

    public function destroy(Request $request)
    {
        $pengguna = Pengguna::find(Auth::guard('web')->id());

        Auth::logout();

        $pengguna->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard')->with('success', 'Akun Anda telah berhasil dihapus.');
    }

    public function hapusKomentar($id)
    {
        $pengguna = Auth::guard('web')->user();
        $komentar = Komentar::where('id_komentar', $id)
            ->where('id_pengguna', $pengguna->id_pengguna)
            ->firstOrFail();

        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
