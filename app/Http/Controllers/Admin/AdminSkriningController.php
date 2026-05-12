<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pertanyaan;
use App\Models\Skrining;

class AdminSkriningController extends Controller
{
    public function index()
    {
        $pertanyaan = Pertanyaan::orderBy('urutan')->get();
        $stats = [
            'total' => Skrining::count(),
            'rendah' => Skrining::where('tingkat_risiko', 'Rendah')->count(),
            'sedang' => Skrining::where('tingkat_risiko', 'Sedang')->count(),
            'tinggi' => Skrining::where('tingkat_risiko', 'Tinggi')->count(),
            'avg_skor' => round(Skrining::avg('skor_total') ?? 0, 1),
        ];

        return view('admin.skrining', compact('pertanyaan', 'stats'));
    }

    public function create()
    {
        $kategoriList = Pertanyaan::select('kategori')->distinct()->pluck('kategori');
        return view('admin.form-skrining', ['pertanyaan' => null, 'kategoriList' => $kategoriList]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_pertanyaan' => 'required|string',
            'kategori' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'pilihan' => 'required|array|min:2',
            'pilihan.*.teks' => 'required|string',
            'pilihan.*.bobot' => 'required|integer|min:0',
        ]);

        Pertanyaan::create([
            'isi_pertanyaan' => $request->isi_pertanyaan,
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
            'pilihan_jawaban' => $request->pilihan,
        ]);

        return redirect()->route('admin.skrining.index')->with('success', 'Pertanyaan skrining berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);
        $kategoriList = Pertanyaan::select('kategori')->distinct()->pluck('kategori');
        return view('admin.form-skrining', compact('pertanyaan', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);
        $request->validate([
            'isi_pertanyaan' => 'required|string',
            'kategori' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'pilihan' => 'required|array|min:2',
            'pilihan.*.teks' => 'required|string',
            'pilihan.*.bobot' => 'required|integer|min:0',
        ]);

        $pertanyaan->update([
            'isi_pertanyaan' => $request->isi_pertanyaan,
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
            'pilihan_jawaban' => $request->pilihan,
        ]);

        return redirect()->route('admin.skrining.index')->with('success', 'Pertanyaan skrining berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pertanyaan::findOrFail($id)->delete();
        return back()->with('success', 'Pertanyaan skrining berhasil dihapus.');
    }
}
