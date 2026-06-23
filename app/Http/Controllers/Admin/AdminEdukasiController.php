<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MateriEdukasi;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class AdminEdukasiController extends Controller
{
    private function getCloudinary()
    {
        return new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ])
        );
    }

    public function index()
    {
        $materi = MateriEdukasi::orderBy('tanggal_publish', 'desc')->paginate(10);
        return view('admin.edukasi', compact('materi'));
    }

    public function create()
    {
        $kategoriList = MateriEdukasi::select('kategori')->distinct()->pluck('kategori');
        return view('admin.form-edukasi', ['materi' => null, 'kategoriList' => $kategoriList]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'isi_materi' => 'required|string',
            'tanggal_publish' => 'required|date',
        ]);

        $data = [
            'id_admin' => session('admin')['id'] ?? null,
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi_materi' => $request->isi_materi,
            'tanggal_publish' => $request->tanggal_publish,
            'tampilkan_di_dashboard' => $request->has('tampilkan_di_dashboard'),
        ];

        if ($request->hasFile('thumbnail')) {
            $request->validate(['thumbnail' => 'image|mimes:jpeg,png,jpg,webp|max:5120']);
            $result = $this->getCloudinary()->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'anhiva/materi']
            );
            $data['thumbnail'] = $result['secure_url'];
        }

        MateriEdukasi::create($data);
        return redirect()->route('admin.edukasi.index')->with('success', 'Materi edukasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $materi = MateriEdukasi::findOrFail($id);
        $kategoriList = MateriEdukasi::select('kategori')->distinct()->pluck('kategori');
        return view('admin.form-edukasi', compact('materi', 'kategoriList'));
    }

    public function update(Request $request, $id)
    {
        $materi = MateriEdukasi::findOrFail($id);
        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'isi_materi' => 'required|string',
            'tanggal_publish' => 'required|date',
        ]);

        $data = $request->only('judul', 'kategori', 'isi_materi', 'tanggal_publish');
        $data['tampilkan_di_dashboard'] = $request->has('tampilkan_di_dashboard');

        if ($request->hasFile('thumbnail')) {
            $request->validate(['thumbnail' => 'image|mimes:jpeg,png,jpg,webp|max:5120']);
            $result = $this->getCloudinary()->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'anhiva/materi']
            );
            $data['thumbnail'] = $result['secure_url'];
        }

        $materi->update($data);
        return redirect()->route('admin.edukasi.index')->with('success', 'Materi edukasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = MateriEdukasi::findOrFail($id);
        $materi->delete();
        return back()->with('success', 'Materi edukasi berhasil dihapus.');
    }
}