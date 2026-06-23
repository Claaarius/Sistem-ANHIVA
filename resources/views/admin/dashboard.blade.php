@extends('layouts.admin')
@section('title', 'Dashboard Admin - ANHIVA')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Overview -->
<div class="grid grid-4 mb-xl">
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-number">{{ $stats['total_skrining'] }}</div>
        <div class="stat-label">Total Skrining</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-users"></i></div>
        <div class="stat-number">{{ $stats['total_pengguna'] }}</div>
        <div class="stat-label">Pengguna Terdaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="fas fa-comments"></i></div>
        <div class="stat-number">{{ $stats['total_konseling_pending'] }}</div>
        <div class="stat-label">Konseling Menunggu</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="fas fa-message"></i></div>
        <div class="stat-number">{{ $stats['total_komentar_pending'] }}</div>
        <div class="stat-label">Komentar Menunggu</div>
    </div>
</div>

<!-- Risk Distribution -->
<div class="card mb-xl">
    <div class="card-header"><h4 style="margin-bottom:0;">Distribusi Tingkat Risiko Skrining</h4></div>
    <div class="card-body">
        <div class="grid grid-3">
            <div style="text-align:center; padding:var(--space-lg);">
                <div style="font-size:2rem; font-weight:800; color:var(--risk-low-text);">{{ $stats['risiko_rendah'] }}</div>
                <span class="badge badge-risiko-rendah">Rendah</span>
            </div>
            <div style="text-align:center; padding:var(--space-lg);">
                <div style="font-size:2rem; font-weight:800; color:var(--risk-med-text);">{{ $stats['risiko_sedang'] }}</div>
                <span class="badge badge-risiko-sedang">Sedang</span>
            </div>
            <div style="text-align:center; padding:var(--space-lg);">
                <div style="font-size:2rem; font-weight:800; color:var(--risk-high-text);">{{ $stats['risiko_tinggi'] }}</div>
                <span class="badge badge-risiko-tinggi">Tinggi</span>
            </div>
        </div>
    </div>
</div>

<!-- Hero Section Management -->
<div class="card mb-xl">
    <div class="card-header d-flex justify-between align-center">
        <h4 style="margin-bottom:0;"><i class="fas fa-star" style="color:var(--amber-400);"></i> Kelola Hero Section</h4>
        <a href="{{ route('admin.edukasi.index') }}" class="btn btn-sm btn-outline"><i class="fas fa-book-open"></i> Kelola Edukasi Terbaru</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.dashboard.hero') }}">
            @csrf
            @method('PUT')
            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ $hero->judul ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Teks Tombol</label>
                    <input type="text" name="tombol_teks" class="form-control" value="{{ $hero->tombol_teks ?? '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konten / Tagline</label>
                <textarea name="konten" class="form-control" rows="3" required>{{ $hero->konten ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Link Tombol</label>
                <input type="text" name="tombol_link" class="form-control" value="{{ $hero->tombol_link ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Hero Section</button>
        </form>
    </div>
</div>

<!-- HIV Facts Management -->
<div class="card">
    <div class="card-header d-flex justify-between align-center">
        <h4 style="margin-bottom:0;"><i class="fas fa-chart-bar" style="color:var(--amber-400);"></i> Fakta & Statistik HIV</h4>
        <button class="btn btn-sm btn-primary" onclick="document.getElementById('addFaktaModal').classList.add('active')">
            <i class="fas fa-plus"></i> Tambah Fakta
        </button>
    </div>
    <div class="card-body">
        @error('fakta')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        @foreach($faktaHiv as $f)
        <div class="fact-card mb-md">
            <div class="fact-item d-flex gap-md flex-wrap" style="align-items:flex-start;">
                <form id="updateFakta-{{ $f->id_konten }}" method="POST" action="{{ route('admin.dashboard.fakta.update', $f->id_konten) }}" class="fact-edit-form" style="flex:1 1 560px; min-width:280px;">
                    @csrf
                    @method('PUT')
                    <div>
                        <input type="text" name="judul" class="form-control mb-sm" value="{{ $f->judul }}" required style="font-weight:700;">
                        <textarea name="konten" class="form-control mb-sm" rows="2" required>{{ $f->konten }}</textarea>
                        <input type="text" name="sumber" class="form-control" value="{{ $f->sumber }}" placeholder="Sumber data (opsional)">
                    </div>
                </form>
                <div class="fact-controls d-flex flex-column justify-between" style="min-width:180px;">
                    <div class="fact-actions d-flex gap-sm justify-end flex-wrap" style="margin-top:var(--space-sm); width:100%;">
                        <button type="submit" form="updateFakta-{{ $f->id_konten }}" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <form method="POST" action="{{ route('admin.dashboard.fakta.destroy', $f->id_konten) }}" class="fact-delete-form" onsubmit="return confirm('Yakin hapus fakta ini?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Fakta Modal -->
<div class="modal-overlay" id="addFaktaModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Fakta HIV</h3>
            <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('active')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.dashboard.fakta.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Judul Card *</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Isi Fakta atau Statistik *</label>
                    <textarea name="konten" class="form-control" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Sumber Data</label>
                    <input type="text" name="sumber" class="form-control" placeholder="Opsional">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
