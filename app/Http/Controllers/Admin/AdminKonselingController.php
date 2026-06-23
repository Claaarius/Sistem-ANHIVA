<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konseling;
use App\Models\CatatanKonseling;
use App\Models\Rujukan;
use App\Services\WhatsappService;

class AdminKonselingController extends Controller
{
    public function index()
    {
        Konseling::where('sudah_dilihat_admin', false)
            ->update(['sudah_dilihat_admin' => true]);

        $status = request('status');
        $query = Konseling::orderBy('tanggal_pengajuan', 'desc');
        if ($status) {
            if ($status === 'Rujukan') {
                $query->whereHas('rujukan');
            } else {
                $query->where('status', $status);
            }
        }
        $konseling = $query->paginate(15)->withQueryString();

        $konselingCount = [
            'Menunggu' => Konseling::where('status', 'Menunggu')->count(),
            'Dijadwalkan' => Konseling::where('status', 'Dijadwalkan')->count(),
            'Rujukan' => Konseling::has('rujukan')->count(),
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

        // Kirim notifikasi WhatsApp
        if ($konseling->nomor_kontak) {
            $jadwal = isset($data['jadwal_konseling'])
                ? \Carbon\Carbon::parse($data['jadwal_konseling'])->format('d M Y H:i')
                : '-';

            $lokasi = $konseling->lokasi_konseling ?? '-';

            $nama_pengguna = $konseling->pengguna->username ?? 'Pengguna';
            $pesan = match ($request->status) {
                'Dijadwalkan' => "Halo {$nama_pengguna}, jadwal konseling Anda telah dijadwalkan pada {$jadwal}.",
                'Selesai' => "Halo {$nama_pengguna}, konseling Anda telah selesai. Terima kasih.",
                'Menunggu' =>
                "Halo! 👋\n\nStatus konseling Anda di *ANHIVA* diperbarui menjadi *Menunggu*.\n\n" .
                "🔑 *Kode Unik:* {$konseling->kode_unik}\n\n" .
                "Kami akan segera memproses pengajuan Anda.\n" .
                "Terima kasih! 🙏",
                default => null,
            };

            if ($pesan) {
                WhatsappService::send($konseling->nomor_kontak, $pesan);
            }
        }

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

        // Kirim notifikasi WhatsApp
        if ($konseling->nomor_kontak) {
            $jadwal = \Carbon\Carbon::parse($konseling->tanggal_reschedule_diminta)->format('d M Y H:i');
            $pesan =
                "Halo! 👋\n\nPermintaan reschedule konseling Anda di *ANHIVA* telah *disetujui*.\n\n" .
                "📅 *Jadwal Baru:* {$jadwal}\n" .
                "🔑 *Kode Unik:* {$konseling->kode_unik}\n\n" .
                "Harap konfirmasi kehadiran Anda melalui aplikasi ANHIVA.\n" .
                "Terima kasih! 🙏";

            WhatsappService::send($konseling->nomor_kontak, $pesan);
        }

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

        // Kirim notifikasi WhatsApp
        if ($konseling->nomor_kontak) {
            $jadwal = \Carbon\Carbon::parse($request->jadwal_konseling)->format('d M Y H:i');
            $pesan =
                "Halo! 👋\n\nJadwal konseling Anda di *ANHIVA* telah dijadwalkan ulang.\n\n" .
                "📅 *Jadwal Baru:* {$jadwal}\n" .
                "🔑 *Kode Unik:* {$konseling->kode_unik}\n\n" .
                "Harap konfirmasi kehadiran Anda melalui aplikasi ANHIVA.\n" .
                "Terima kasih! 🙏";

            WhatsappService::send($konseling->nomor_kontak, $pesan);
        }

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
            'id_admin' => session('admin')['id'],
            'kode_unik' => $konseling->kode_unik,
            'tanggal_catatan' => now(),
            'deskripsi_hasil' => $request->deskripsi_hasil,
        ]);

        // Kirim notifikasi WhatsApp
        if ($konseling->nomor_kontak) {
            $pesan = "Halo! Admin ANHIVA telah menambahkan catatan hasil konseling untuk sesi Anda. Kode Unik: {$konseling->kode_unik}. Silakan login untuk melihat detailnya.";

            WhatsappService::send($konseling->nomor_kontak, $pesan);
        }

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
            return back()->with('error', 'Konseling yang sedang aktif atau menunggu reschedule tidak dapat dihapus.');
        }

        $konseling->catatanKonseling()->delete();
        $konseling->rujukan()->delete();
        $konseling->delete();

        return redirect()->route('admin.konseling.index')->with('success', 'Data konseling berhasil dihapus.');
    }

    public function updateCatatan(Request $request, $id, $catatanId)
    {
        $request->validate(['deskripsi_hasil' => 'required|string']);
        $catatan = CatatanKonseling::findOrFail($catatanId);
        $catatan->update(['deskripsi_hasil' => $request->deskripsi_hasil]);
        return back()->with('success', 'Catatan berhasil diperbarui.');
    }
}