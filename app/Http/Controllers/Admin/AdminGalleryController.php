<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class AdminGalleryController extends Controller
{
    private function getCloudinary()
    {
        return new Cloudinary(
            Configuration::instance([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true]
            ])
        );
    }

    public function index()
    {
        $gallery = Gallery::orderBy('tanggal_upload', 'desc')->paginate(12);
        return view('admin.gallery', compact('gallery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'required|string',
        ]);

        $result = $this->getCloudinary()->uploadApi()->upload(
            $request->file('foto')->getRealPath(),
            ['folder' => 'anhiva/gallery']
        );

        Gallery::create([
            'id_admin'      => session('admin')['id'] ?? null,
            'foto'          => $result['secure_url'],
            'keterangan'    => $request->keterangan,
            'tanggal_upload' => now(),
        ]);

        return back()->with('success', 'Foto berhasil ditambahkan ke gallery.');
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $request->validate([
            'keterangan' => 'required|string',
        ]);

        $data = ['keterangan' => $request->keterangan];

        if ($request->hasFile('foto')) {
            $request->validate(['foto' => 'image|mimes:jpeg,png,jpg,webp|max:5120']);
            $result = $this->getCloudinary()->uploadApi()->upload(
                $request->file('foto')->getRealPath(),
                ['folder' => 'anhiva/gallery']
            );
            $data['foto'] = $result['secure_url'];
        }

        $gallery->update($data);
        return back()->with('success', 'Foto gallery berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return back()->with('success', 'Foto gallery berhasil dihapus.');
    }
}