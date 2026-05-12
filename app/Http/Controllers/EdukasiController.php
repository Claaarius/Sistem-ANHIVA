<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriEdukasi;

class EdukasiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = MateriEdukasi::select('kategori')->distinct()->pluck('kategori');
        $selectedKategori = $request->get('kategori');

        $query = MateriEdukasi::orderBy('tanggal_publish', 'desc');
        if ($selectedKategori) {
            $query->where('kategori', $selectedKategori);
        }
        $materi = $query->paginate(9);

        return view('edukasi.index', compact('materi', 'kategori', 'selectedKategori'));
    }

    public function show($id)
    {
        $materi = MateriEdukasi::findOrFail($id);
        $materiTerkait = MateriEdukasi::where('id_materi', '!=', $id)
            ->where('kategori', $materi->kategori)
            ->orderBy('tanggal_publish', 'desc')
            ->take(3)
            ->get();

        return view('edukasi.show', compact('materi', 'materiTerkait'));
    }
}
