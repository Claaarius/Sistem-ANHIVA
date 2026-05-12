<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konseling;
use App\Models\CatatanKonseling;
use Illuminate\Support\Str;

class KonselingController extends Controller
{
    public function index()
{
    return view('konseling.index');
}

    public function pengajuan()
    {
        return view('konseling.pengajuan');
    }

    public function store(Request $request)
{
    $request->validate([
        'jenis' => 'required|in:Online,Tatap Muka',
        'alasan' => 'required|string',
    ]);

    $kodeUnik = session('kode_unik');
    if (!$kodeUnik) {
        $kodeUnik = 'ANH-' . strtoupper(Str::random(10));
        session(['kode_unik' => $kodeUnik]);
    }

    Konseling::create([
        'id_pengguna' => auth()->check() ? auth()->user()->id_pengguna : null,
        'kode_unik' => $kodeUnik,
        'alasan' => $request->alasan,
        'jenis' => $request->jenis,
        'nomor_kontak' => $request->nomor_kontak,
        'status' => 'Menunggu',
        'tanggal_pengajuan' => now(),
        'sudah_dilihat_admin' => false,
    ]);

    return redirect()->route('konseling.pengajuan')
        ->with('pengajuan_sukses', 'Pengajuan konseling berhasil dikirim!');
    }

    public function catatan(Request $request)
    {
        if (auth()->check()) {
            $catatan = CatatanKonseling::where('kode_unik', auth()->user()->kode_unik)
                ->with('konseling')
                ->orderBy('tanggal_catatan', 'desc')
                ->get();
            return view('konseling.catatan', compact('catatan'));
        }

        if ($request->has('kode_unik')) {
            $catatan = CatatanKonseling::where('kode_unik', $request->kode_unik)
                ->with('konseling')
                ->orderBy('tanggal_catatan', 'desc')
                ->get();
            return view('konseling.catatan', compact('catatan'));
        }

        return view('konseling.catatan', ['catatan' => null]);
    }

    public function riwayatPengajuan()
    {
        if (auth()->check()) {
            $riwayat = Konseling::where('id_pengguna', auth()->id())->orderBy('tanggal_pengajuan', 'desc')->get();
            return view('konseling.riwayat-pengajuan', compact('riwayat'));
        }
        return view('konseling.riwayat-pengajuan', ['riwayat' => null]);
    }

    public function cariRiwayat(Request $request)
    {
        $request->validate(['kode_unik' => 'required|string']);
        $riwayat = Konseling::where('kode_unik', $request->kode_unik)->orderBy('tanggal_pengajuan', 'desc')->get();
        return view('konseling.riwayat-pengajuan', compact('riwayat'))->with('searched_kode', $request->kode_unik);
    }

    public function konfirmasiJadwal($id)
    {
        $konseling = Konseling::findOrFail($id);

        // Authorization check
        if (auth()->check() && $konseling->id_pengguna !== auth()->id()) {
            abort(403);
        }

        if ($konseling->status === 'Selesai') {
            return back()->with('error', 'Kehadiran sudah dikonfirmasi sebelumnya.');
        }

        $konseling->update([
            'status' => 'Selesai',
            'konfirmasi_pengguna' => 'Dikonfirmasi',
        ]);
        return back()->with('success', 'Kehadiran berhasil dikonfirmasi.');
    }

    public function mintaReschedule(Request $request, $id)
    {
        $request->validate([
            'tanggal_reschedule_diminta' => 'required|date',
            'catatan_reschedule' => 'nullable|string',
        ]);
        $konseling = Konseling::findOrFail($id);

        if (auth()->check() && $konseling->id_pengguna !== auth()->id()) {
            abort(403);
        }
        if (($konseling->jumlah_reschedule ?? 0) >= 2) {
            return back()->with('error', 'Batas permintaan reschedule telah tercapai (maks. 2x).');
        }

        $konseling->update([
            'status' => 'Menunggu Reschedule',
            'konfirmasi_pengguna' => 'Minta Reschedule',
            'tanggal_reschedule_diminta' => $request->tanggal_reschedule_diminta,
            'catatan_reschedule' => $request->catatan_reschedule,
            'jumlah_reschedule' => $konseling->jumlah_reschedule + 1,
        ]);

        session()->forget('reschedule_notif_shown');
        return back()->with('success', 'Permintaan reschedule dikirim.');
    }

    public function batalkan($id)
    {
        $konseling = Konseling::findOrFail($id);

        $isPemilikLogin = auth()->check() && $konseling->id_pengguna === auth()->id();
        $isPemilikKodeUnik = !auth()->check() && session('kode_unik') && $konseling->kode_unik === session('kode_unik');

        if (!$isPemilikLogin && !$isPemilikKodeUnik) {
            abort(403);
        }

        if ($konseling->status === 'Dijadwalkan' && ($konseling->jumlah_reschedule ?? 0) < 2) {
            return back()->with('error', 'Tidak dapat membatalkan konseling yang sedang dijadwalkan.');
        }

        if (!in_array($konseling->status, ['Menunggu', 'Dijadwalkan'])) {
            return back()->with('error', 'Pengajuan hanya dapat dibatalkan saat status masih Menunggu atau Dijadwalkan dengan batas reschedule tercapai.');
        }

        $konseling->delete();

        return redirect()->route('konseling.riwayat')->with('success', 'Pengajuan konseling berhasil dibatalkan.');
    }
}
