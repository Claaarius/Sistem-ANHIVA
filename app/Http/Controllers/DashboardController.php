<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DashboardKonten;
use App\Models\Skrining;
use App\Models\Pengguna;
use App\Models\MateriEdukasi;
use App\Models\Gallery;
use App\Models\Komentar;

class DashboardController extends Controller
{
    public function index()
    {
        // Hero section
        $hero = DashboardKonten::hero()->first();

        // System stats
        $totalSkrining = Skrining::count();
        $totalPengguna = Pengguna::count();
        $totalMateri = MateriEdukasi::count();
        $distribusiRisiko = [
            'rendah' => Skrining::where('tingkat_risiko', 'Rendah')->count(),
            'sedang' => Skrining::where('tingkat_risiko', 'Sedang')->count(),
            'tinggi' => Skrining::where('tingkat_risiko', 'Tinggi')->count(),
        ];

        // HIV Facts
        $faktaHiv = DashboardKonten::faktaHiv()->get();

        // Latest education materials
        $materiTerbaru = MateriEdukasi::where('tampilkan_di_dashboard', true)
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();
            
        if ($materiTerbaru->isEmpty()) {
            $materiTerbaru = MateriEdukasi::orderBy('tanggal_publish', 'desc')->take(3)->get();
        }

        // Gallery
        $gallery = Gallery::orderBy('tanggal_upload', 'desc')->take(6)->get();

        // Approved comments
        $komentar = Komentar::where('status', 'Disetujui')
            ->orderBy('tanggal_komentar', 'desc')
            ->get();

        return view('dashboard', compact(
            'hero', 'totalSkrining', 'totalPengguna', 'totalMateri',
            'distribusiRisiko', 'faktaHiv', 'materiTerbaru', 'gallery', 'komentar'
        ));
    }
}
