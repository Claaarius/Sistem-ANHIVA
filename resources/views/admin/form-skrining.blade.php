@extends('layouts.admin')
@section('title', ($pertanyaan ? 'Edit' : 'Tambah') . ' Pertanyaan - Admin ANHIVA')
@section('page-title', ($pertanyaan ? 'Edit' : 'Tambah') . ' Pertanyaan Skrining')

@section('content')
<div class="card">
    <div class="card-body" style="padding:var(--space-2xl);">
        <form method="POST" action="{{ $pertanyaan ? route('admin.skrining.update', $pertanyaan->id_pertanyaan) : route('admin.skrining.store') }}" id="pertanyaanForm">
            @csrf
            @if($pertanyaan) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Isi Pertanyaan *</label>
                <textarea name="isi_pertanyaan" class="form-control" rows="3" required>{{ old('isi_pertanyaan', $pertanyaan->isi_pertanyaan ?? '') }}</textarea>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $pertanyaan->kategori ?? '') }}" list="kategoriList" required>
                    <datalist id="kategoriList">
                        @foreach($kategoriList as $k)
                        <option value="{{ $k }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan *</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $pertanyaan->urutan ?? 1) }}" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilihan Jawaban *</label>
                <p class="form-hint">Tambahkan minimal 2 pilihan jawaban. Setiap jawaban memiliki bobot nilai.</p>
                <div id="pilihanContainer">
                    @if($pertanyaan && $pertanyaan->pilihan_jawaban)
                        @foreach($pertanyaan->pilihan_jawaban as $i => $p)
                        <div class="d-flex gap-md mb-sm align-center pilihan-row">
                            <input type="text" name="pilihan[{{ $i }}][teks]" class="form-control" placeholder="Teks jawaban" value="{{ $p['teks'] }}" required style="flex:3;">
                            <input type="number" name="pilihan[{{ $i }}][bobot]" class="form-control" placeholder="Bobot" value="{{ $p['bobot'] }}" required min="0" style="flex:1;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                        @endforeach
                    @else
                        <div class="d-flex gap-md mb-sm align-center pilihan-row">
                            <input type="text" name="pilihan[0][teks]" class="form-control" placeholder="Teks jawaban" required style="flex:3;">
                            <input type="number" name="pilihan[0][bobot]" class="form-control" placeholder="Bobot" required min="0" style="flex:1;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="d-flex gap-md mb-sm align-center pilihan-row">
                            <input type="text" name="pilihan[1][teks]" class="form-control" placeholder="Teks jawaban" required style="flex:3;">
                            <input type="number" name="pilihan[1][bobot]" class="form-control" placeholder="Bobot" required min="0" style="flex:1;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-sm btn-outline mt-sm" onclick="addPilihan()">
                    <i class="fas fa-plus"></i> Tambah Pilihan
                </button>
            </div>

            <div class="d-flex gap-md">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ $pertanyaan ? 'Perbarui' : 'Simpan' }}</button>
                <a href="{{ route('admin.skrining.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pilihanIndex = {{ $pertanyaan && $pertanyaan->pilihan_jawaban ? count($pertanyaan->pilihan_jawaban) : 2 }};

function addPilihan() {
    const container = document.getElementById('pilihanContainer');
    const html = `
        <div class="d-flex gap-md mb-sm align-center pilihan-row">
            <input type="text" name="pilihan[${pilihanIndex}][teks]" class="form-control" placeholder="Teks jawaban" required style="flex:3;">
            <input type="number" name="pilihan[${pilihanIndex}][bobot]" class="form-control" placeholder="Bobot" required min="0" style="flex:1;">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    pilihanIndex++;
}
</script>
@endpush
