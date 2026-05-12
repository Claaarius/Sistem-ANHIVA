@extends('layouts.admin')
@section('title', 'Gallery - Admin ANHIVA')
@section('page-title', 'Manajemen Gallery')

@section('page-actions')
<button class="btn btn-sm btn-primary" onclick="document.getElementById('addGalleryModal').classList.add('active')">
    <i class="fas fa-plus"></i> Tambah Foto
</button>
@endsection

@section('content')
<div class="grid grid-3">
    @forelse($gallery as $g)
    <div class="card fade-in">
        <img src="{{ $g->foto }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="{{ $g->keterangan }}" class="card-img" style="object-fit:cover; height:200px;">
        <div class="card-body">
            <p class="card-text">{{ \Illuminate\Support\Str::limit($g->keterangan, 80) }}</p>
            <div style="font-size:0.75rem; color:var(--gray-400); margin-bottom:var(--space-sm);">{{ $g->tanggal_upload->format('d M Y') }}</div>
            <div class="d-flex gap-sm">
                <!-- Edit Button -->
                <button class="btn btn-sm btn-outline" onclick="openEditGalleryModal({{ $g->id_gallery }}, '{{ $g->foto }}', '{{ addslashes($g->keterangan) }}')">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form method="POST" action="{{ route('admin.gallery.destroy', $g->id_gallery) }}" onsubmit="return confirm('Yakin hapus foto ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state" style="grid-column:1/-1;">
        <i class="fas fa-images"></i>
        <h3>Belum ada foto di gallery</h3>
        <p>Tambahkan foto dokumentasi kegiatan.</p>
    </div>
    @endforelse
</div>
{{ $gallery->links('pagination.simple') }}

<!-- Add Gallery Modal -->
<div class="modal-overlay" id="addGalleryModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Foto Gallery</h3>
            <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('active')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Upload Foto *</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                    <div class="form-hint">Maks 5MB. Format: JPEG, PNG, WebP</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan Foto *</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi foto, contoh: Kegiatan penyuluhan HIV bersama mahasiswa..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Gallery Modal -->
<div class="modal-overlay" id="editGalleryModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Edit Foto Gallery</h3>
            <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('active')">&times;</button>
        </div>
        <form method="POST" action="" id="editGalleryForm" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group text-center">
                    <label class="form-label text-left">Preview Foto Saat Ini</label>
                    <img id="editPreviewImg" src="" alt="Preview" style="max-height:200px; border-radius:var(--radius-md); border:1px solid var(--gray-100);">
                </div>
                <div class="form-group">
                    <label class="form-label">Ganti Foto (opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewEditImage(this)">
                    <div class="form-hint">Biarkan kosong jika tidak ingin mengganti foto. Maks 5MB.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan Foto *</label>
                    <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditGalleryModal(id, imgSrc, keterangan) {
    document.getElementById('editGalleryForm').action = '/admin/gallery/' + id;
    document.getElementById('editPreviewImg').src = imgSrc;
    document.getElementById('editKeterangan').value = keterangan;
    document.getElementById('editGalleryModal').classList.add('active');
}

function previewEditImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editPreviewImg').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
