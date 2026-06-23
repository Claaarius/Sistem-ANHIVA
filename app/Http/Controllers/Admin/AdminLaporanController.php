<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skrining;
use App\Models\Konseling;
use App\Models\Pengguna;
use App\Models\Rujukan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminLaporanController extends Controller
{
    public function index()
    {
        $totalPengguna = Pengguna::count();
        $totalSkrining = Skrining::count();
        $totalKonseling = Konseling::count();
        $totalKomentar = \App\Models\Komentar::count();
        $totalEdukasi = \App\Models\MateriEdukasi::count();
        $totalGallery = \App\Models\Gallery::count();

        // Data terbaru
        $latestSkrining = Skrining::with('pengguna')->latest('tanggal_skrining')->limit(5)->get();
        $latestKonseling = Konseling::with('pengguna')->latest('tanggal_pengajuan')->limit(5)->get();
        $latestKomentar = \App\Models\Komentar::with('pengguna')->latest('tanggal_komentar')->limit(5)->get();

        return view('admin.laporan', compact(
            'totalPengguna',
            'totalSkrining',
            'totalKonseling',
            'totalKomentar',
            'totalEdukasi',
            'totalGallery',
            'latestSkrining',
            'latestKonseling',
            'latestKomentar'
        ));
    }

    private function getData(string $jenis, int $bulan, int $tahun): array
    {
        $start = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $end   = Carbon::create($tahun, $bulan, 1)->endOfMonth();
        $label = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');

        // Generate 6 bulan terakhir untuk chart tren
        $trenLabels = [];
        $trenData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::create($tahun, $bulan, 1)->subMonths($i);
            $trenLabels[] = $m->translatedFormat('M Y');
            if ($jenis === 'skrining') {
                $trenData[] = Skrining::whereYear('tanggal_skrining', $m->year)
                    ->whereMonth('tanggal_skrining', $m->month)->count();
            } elseif ($jenis === 'konseling') {
                $trenData[] = Konseling::whereYear('tanggal_pengajuan', $m->year)
                    ->whereMonth('tanggal_pengajuan', $m->month)->count();
            } elseif ($jenis === 'pengguna') {
                $trenData[] = Pengguna::whereYear('tanggal_daftar', $m->year)
                    ->whereMonth('tanggal_daftar', $m->month)->count();
            }
        }

        if ($jenis === 'skrining') {
            $total     = Skrining::whereBetween('tanggal_skrining', [$start, $end])->count();
            $rendah    = Skrining::whereBetween('tanggal_skrining', [$start, $end])->where('tingkat_risiko', 'Rendah')->count();
            $sedang    = Skrining::whereBetween('tanggal_skrining', [$start, $end])->where('tingkat_risiko', 'Sedang')->count();
            $tinggi    = Skrining::whereBetween('tanggal_skrining', [$start, $end])->where('tingkat_risiko', 'Tinggi')->count();
            $anonim    = Skrining::whereBetween('tanggal_skrining', [$start, $end])->whereNull('id_pengguna')->count();
            $terdaftar = $total - $anonim;
            $rataRata  = Skrining::whereBetween('tanggal_skrining', [$start, $end])->avg('skor_total');
            $dominan   = $rendah >= $sedang && $rendah >= $tinggi ? 'Rendah' : ($sedang >= $tinggi ? 'Sedang' : 'Tinggi');
            $detail    = Skrining::whereBetween('tanggal_skrining', [$start, $end])
                ->orderBy('tanggal_skrining', 'desc')->get();

            return compact('total', 'rendah', 'sedang', 'tinggi', 'anonim', 'terdaftar',
                'rataRata', 'dominan', 'detail', 'trenLabels', 'trenData', 'label', 'jenis', 'bulan', 'tahun');
        }

        if ($jenis === 'konseling') {
            $total     = Konseling::whereBetween('tanggal_pengajuan', [$start, $end])->count();
            $selesai   = Konseling::whereBetween('tanggal_pengajuan', [$start, $end])->where('status', 'Selesai')->count();
            $online    = Konseling::whereBetween('tanggal_pengajuan', [$start, $end])->where('jenis', 'Online')->count();
            $tatapMuka = Konseling::whereBetween('tanggal_pengajuan', [$start, $end])->where('jenis', 'Tatap Muka')->count();
            $rujukan   = Rujukan::whereHas('konseling', fn($q) => $q->whereBetween('tanggal_pengajuan', [$start, $end]))->count();
            $detail    = Konseling::whereBetween('tanggal_pengajuan', [$start, $end])
                ->with('rujukan')->orderBy('tanggal_pengajuan', 'desc')->get();

            return compact('total', 'selesai', 'online', 'tatapMuka', 'rujukan',
                'detail', 'trenLabels', 'trenData', 'label', 'jenis', 'bulan', 'tahun');
        }

        if ($jenis === 'pengguna') {
            $total  = Pengguna::count();
            $baru   = Pengguna::whereBetween('tanggal_daftar', [$start, $end])->count();
            $detail = Pengguna::whereBetween('tanggal_daftar', [$start, $end])
                ->orderBy('tanggal_daftar', 'desc')->get();

            return compact('total', 'baru', 'detail', 'trenLabels', 'trenData', 'label', 'jenis', 'bulan', 'tahun');
        }

        return [];
    }

    public function preview(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:skrining,konseling,pengguna',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
        ]);

        $data = $this->getData($request->jenis, (int) $request->bulan, (int) $request->tahun);
        return view('admin.laporan', array_merge($data, ['showPreview' => true]));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:skrining,konseling,pengguna',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
        ]);

        $data = $this->getData($request->jenis, (int) $request->bulan, (int) $request->tahun);
        $data['generatedAt'] = now()->translatedFormat('d F Y, H:i');

        $pdf = Pdf::loadView('admin.laporan-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'Laporan_' . ucfirst($data['jenis']) . '_' . $data['label'] . '.pdf';
        return $pdf->download($filename);
    }
}