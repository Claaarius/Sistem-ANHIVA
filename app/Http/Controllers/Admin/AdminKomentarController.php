<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komentar;

class AdminKomentarController extends Controller
{
    public function index()
    {
        $komentar = Komentar::orderBy('tanggal_komentar', 'desc')->paginate(15);
        return view('admin.komentar', compact('komentar'));
    }

    public function approve($id)
    {
        $komentar = Komentar::findOrFail($id);
        $komentar->update([
            'status' => 'Disetujui',
            'sudah_dilihat_admin' => true,
        ]);
        return back()->with('success', 'Komentar berhasil disetujui.');
    }

    public function reject($id)
    {
        $komentar = Komentar::findOrFail($id);
        $komentar->update([
            'status' => 'Ditolak',
            'sudah_dilihat_admin' => true,
        ]);
        return back()->with('success', 'Komentar berhasil ditolak.');
    }

    public function destroy($id)
    {
        Komentar::findOrFail($id)->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function markAsRead($id)
    {
        $komentar = Komentar::findOrFail($id);
        $komentar->update(['sudah_dilihat_admin' => true]);
        return back()->with('success', 'Komentar telah ditandai sebagai dibaca.');
    }
}
