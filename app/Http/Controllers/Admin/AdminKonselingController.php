<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konseling;
use App\Models\CatatanKonseling;
use App\Models\Rujukan;
use Illuminate\Support\Facades\Auth;

class AdminKonselingController extends Controller
{
    public function index()
{
    Konseling::where('sudah_dilihat_admin', false)
        ->update(['sudah_dilihat_admin' => true]);

    $status = request('status');
    $query = Konseling::orderBy('tanggal_pengajuan', 'desc');
    if ($status) {
        $query->where('status', $status);
    }
    $konseling = $query->paginate(15)->withQueryString();

    $konselingCount = [
        'Menunggu' => Konseling::where('status', 'Menunggu')->count(),
        'Dijadwalkan' => Konseling::where('status', 'Dijadwalkan')->count(),
        'Menunggu Reschedule' => Konseling::where('status', 'Menunggu Reschedule')->count(),
        'Selesai' => Konseling::where('status', 'Selesai')->count(),
    ];

    $pendingReschedule = Konseling::where('konfirmasi_pengguna', 'Minta Reschedule')->count();
    $showRescheduleNotif = $pendingReschedule > 0 && !session('reschedule_notif_shown');
    if ($showRescheduleNotif) {
        session(['reschedule_notif_shown' => true]);
    }

    return view('admin.konseling', compact('konseling', 'konselingCount', 'showRescheduleNotif', 'pendingReschedule'));
}

    public function show($id)
    {
        $konseling = Konseling::with(['catatanKonseling', 'rujukan'])->findOrFail($id);
        return view('admin.konseling-show', compact('konseling'));
    }

    public function updateStatus(Request $request, $id)
    {
        $konseling = Konseling::findOrFail($id);
        $request->validate([
            'status' => 'required|in:Menunggu,Dijadwalkan,Selesai',
        ]);

        $data = ['status' => $request->status];
        if ($request->has('jadwal_konseling') && $request->jadwal_konseling) {
            $data['jadwal_konseling'] = $request->jadwal_konseling;
        }
        if ($request->has('lokasi_konseling')) {
            $data['lokasi_konseling'] = $request->lokasi_konseling;
        }

        $konseling->update($data);
        return back()->with('success', 'Status konseling berhasil diperbarui.');
    }

    public function setujuReschedule($id)
    {
        $konseling = Konseling::findOrFail($id);

        if (!$konseling->tanggal_reschedule_diminta) {
            return back()->with('error', 'Tanggal reschedule dari pengguna tidak tersedia.');
        }

        $konseling->update([
            'jadwal_konseling' => $konseling->tanggal_reschedule_diminta,
            'status' => 'Dijadwalkan',
            'konfirmasi_pengguna' => null,
        ]);

        return back()->with('success', 'Permintaan reschedule disetujui dan jadwal diperbarui.');
    }

    public function jadwalkanUlang(Request $request, $id)
    {
        $request->validate([
            'jadwal_konseling' => 'required|date',
        ]);

        $konseling = Konseling::findOrFail($id);
        $konseling->update([
            'jadwal_konseling' => $request->jadwal_konseling,
            'status' => 'Dijadwalkan',
            'konfirmasi_pengguna' => null,
        ]);

        return back()->with('success', 'Jadwal ulang berhasil disimpan.');
    }

    public function storeCatatan(Request $request, $id)
    {
        $konseling = Konseling::findOrFail($id);
        $request->validate([
            'deskripsi_hasil' => 'required|string',
        ]);

        CatatanKonseling::create([
            'id_konseling' => $konseling->id_konseling,
            'id_admin' => Auth::guard('admin')->user()->id_admin,
            'kode_unik' => $konseling->kode_unik,
            'tanggal_catatan' => now(),
            'deskripsi_hasil' => $request->deskripsi_hasil,
        ]);

        return back()->with('success', 'Catatan konseling berhasil ditambahkan.');
    }

    public function storeRujukan(Request $request, $id)
    {
        $konseling = Konseling::findOrFail($id);
        $request->validate([
            'lokasi_rujukan' => 'required|string',
            'tanggal_rujukan' => 'required|date',
            'status' => 'required|in:Aktif,Selesai,Dibatalkan',
        ]);

        Rujukan::create([
            'id_konseling' => $konseling->id_konseling,
            'lokasi_rujukan' => $request->lokasi_rujukan,
            'tanggal_rujukan' => $request->tanggal_rujukan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Rujukan berhasil ditambahkan.');
    }

    public function updateRujukan(Request $request, $id)
    {
        $rujukan = Rujukan::findOrFail($id);
        $request->validate([
            'lokasi_rujukan' => 'required|string',
            'tanggal_rujukan' => 'required|date',
            'status' => 'required|in:Aktif,Selesai,Dibatalkan',
        ]);

        $rujukan->update($request->only('lokasi_rujukan', 'tanggal_rujukan', 'status'));
        return back()->with('success', 'Rujukan berhasil diperbarui.');
    }

    public function destroyRujukan($id)
    {
        Rujukan::findOrFail($id)->delete();
        return back()->with('success', 'Rujukan berhasil dihapus.');
    }

    public function destroy($id)
    {
        $konseling = Konseling::with(['catatanKonseling', 'rujukan'])->findOrFail($id);

        if (in_array($konseling->status, ['Dijadwalkan', 'Menunggu Reschedule'])) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Konseling yang sedang aktif atau menunggu reschedule tidak dapat dihapus.',
                ], 422);
            }

            return back()->with('error', 'Konseling yang sedang aktif atau menunggu reschedule tidak dapat dihapus.');
        }

        $konseling->catatanKonseling()->delete();
        $konseling->rujukan()->delete();
        $konseling->delete();

        return redirect()->route('admin.konseling.index')->with('success', 'Data konseling berhasil dihapus.');
    }
}
