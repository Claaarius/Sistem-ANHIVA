<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardKonten;
use App\Models\Skrining;
use App\Models\Pengguna;
use App\Models\MateriEdukasi;
use App\Models\Komentar;
use App\Models\Konseling;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_skrining' => Skrining::count(),
            'total_pengguna' => Pengguna::count(),
            'total_materi' => MateriEdukasi::count(),
            'total_komentar_pending' => Komentar::where('status', 'Menunggu')->count(),
            'total_konseling_pending' => Konseling::where('status', 'Menunggu')->count(),
            'risiko_rendah' => Skrining::where('tingkat_risiko', 'Rendah')->count(),
            'risiko_sedang' => Skrining::where('tingkat_risiko', 'Sedang')->count(),
            'risiko_tinggi' => Skrining::where('tingkat_risiko', 'Tinggi')->count(),
        ];

        $hero = DashboardKonten::hero()->first();
        $faktaHiv = DashboardKonten::faktaHiv()->get();

        return view('admin.dashboard', compact('stats', 'hero', 'faktaHiv'));
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'konten' => 'required|string',
        ]);

        $hero = DashboardKonten::hero()->first();
        if ($hero) {
            $hero->update($request->only('judul', 'konten', 'tombol_teks', 'tombol_link'));
        } else {
            DashboardKonten::create(array_merge(
                $request->only('judul', 'konten', 'tombol_teks', 'tombol_link'),
                ['tipe' => 'hero', 'aktif' => true]
            ));
        }

        return back()->with('success', 'Hero section berhasil diperbarui.');
    }

    public function storeFakta(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'konten' => 'required|string',
        ]);

        DashboardKonten::create([
            'tipe' => 'fakta_hiv',
            'judul' => $request->judul,
            'konten' => $request->konten,
            'sumber' => $request->sumber,
            'urutan' => DashboardKonten::where('tipe', 'fakta_hiv')->count() + 1,
            'aktif' => true,
        ]);

        return back()->with('success', 'Fakta HIV berhasil ditambahkan.');
    }

    public function updateFakta(Request $request, $id)
    {
        $fakta = DashboardKonten::findOrFail($id);
        $request->validate([
            'judul' => 'required|string',
            'konten' => 'required|string',
        ]);

        $fakta->update($request->only('judul', 'konten', 'sumber'));
        return back()->with('success', 'Fakta HIV berhasil diperbarui.');
    }

    public function destroyFakta($id)
    {
        DashboardKonten::findOrFail($id)->delete();
        return back()->with('success', 'Fakta HIV berhasil dihapus.');
    }
}
