@extends('layouts.admin')
@section('title', ($materi ? 'Edit' : 'Tambah') . ' Materi - Admin ANHIVA')
@section('page-title', ($materi ? 'Edit' : 'Tambah') . ' Materi Edukasi')

@section('content')
<div class="card">
    <div class="card-body" style="padding:var(--space-2xl);">
        <form method="POST" action="{{ $materi ? route('admin.edukasi.update', $materi->id_materi) : route('admin.edukasi.store') }}" enctype="multipart/form-data">
            @csrf
            @if($materi) @method('PUT') @endif

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Judul *</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $materi->judul ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $materi->kategori ?? '') }}" list="kategoriList" required placeholder="Pilih atau ketik kategori baru">
                    <datalist id="kategoriList">
                        @foreach($kategoriList as $k)
                        <option value="{{ $k }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Tanggal Publish *</label>
                    <input type="date" name="tanggal_publish" class="form-control" value="{{ old('tanggal_publish', $materi ? $materi->tanggal_publish->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Thumbnail (opsional)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    @if($materi && $materi->thumbnail)
                        <div style="margin-top:8px;">
                            <img src="{{ asset('storage/' . $materi->thumbnail) }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="Thumbnail" style="height:60px; border-radius:var(--radius-sm);">
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Materi *</label>
                <div id="quillEditor" style="min-height:300px;">{!! old('isi_materi', $materi->isi_materi ?? '') !!}</div>
                <input type="hidden" name="isi_materi" id="isiMateriInput">
            </div>

            <div class="d-flex gap-md">
                <button type="submit" class="btn btn-primary" onclick="document.getElementById('isiMateriInput').value = document.querySelector('#quillEditor .ql-editor').innerHTML;">
                    <i class="fas fa-save"></i> {{ $materi ? 'Perbarui' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.edukasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
var quill = new Quill('#quillEditor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [2, 3, 4, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['link'],
            ['clean']
        ]
    }
});

document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('isiMateriInput').value = quill.root.innerHTML;
});
</script>
@endpush
