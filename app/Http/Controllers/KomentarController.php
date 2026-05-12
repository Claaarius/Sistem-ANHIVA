<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komentar;
use Illuminate\Support\Str;

class KomentarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'isi_komentar' => 'required|string|max:10000',
        ]);

        $kodeUnik = session('kode_unik');
        if (!$kodeUnik) {
        $kodeUnik = 'ANH-' . strtoupper(Str::random(10));
        session(['kode_unik' => $kodeUnik]);
}

        Komentar::create([
            'id_pengguna' => auth()->check() ? auth()->user()->id_pengguna : null,
            'kode_unik' => $kodeUnik,
            'isi_komentar' => $request->isi_komentar,
            'status' => 'Menunggu',
            'tanggal_komentar' => now(),
        ]);
        session()->forget('komentar_notif_shown');

        return back()->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function update(Request $request, $id)
    {
        $komentar = Komentar::findOrFail($id);

        // Check ownership
        if (!auth()->check()) {
            return back()->with('error', 'Hanya pengguna terdaftar yang dapat mengedit komentar.');
        }

        if ($komentar->id_pengguna !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit komentar ini.');
        }

        if ($komentar->jumlah_edit >= 3) {
            return back()->with('error', 'Anda sudah mencapai batas maksimal edit (3 kali).');
        }

        $request->validate([
            'isi_komentar' => 'required|string|max:10000',
        ]);

        $komentar->update([
            'isi_komentar' => $request->isi_komentar,
            'jumlah_edit' => $komentar->jumlah_edit + 1,
            'tanggal_edit_terakhir' => now(),
            'is_edited' => true,
            'sudah_dilihat_admin' => false,
        ]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }
}
