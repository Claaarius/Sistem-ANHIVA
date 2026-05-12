@extends('layouts.admin')
@section('title', 'Detail Konseling - Admin ANHIVA')
@section('page-title', 'Detail Konseling')

@section('content')
<a href="{{ route('admin.konseling.index') }}" class="btn btn-sm btn-secondary mb-lg"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="grid grid-2 mb-xl">
    <!-- Info Konseling -->
    <div class="card">
        <div class="card-header"><h4 style="margin-bottom:0;">Informasi Konseling</h4></div>
        <div class="card-body">
            <div class="result-detail"><span class="label">Kode Unik</span><span class="value" style="font-family:monospace;color:var(--teal-400);">{{ $konseling->kode_unik }}</span></div>
            <div class="result-detail"><span class="label">Jenis</span><span class="value">{{ $konseling->jenis }}</span></div>
            <div class="result-detail"><span class="label">Alasan</span><span class="value">{{ $konseling->alasan }}</span></div>
            <div class="result-detail"><span class="label">Nomor Kontak</span><span class="value">{{ $konseling->nomor_kontak ?? '-' }}</span></div>
            <div class="result-detail"><span class="label">Tanggal Pengajuan</span><span class="value">{{ $konseling->tanggal_pengajuan->format('d M Y') }}</span></div>
            <div class="result-detail"><span class="label">Status</span><span class="badge {{ $konseling->status_badge_class }}">{{ $konseling->status }}</span></div>
            @if($konseling->jadwal_konseling)
            <div class="result-detail"><span class="label">Jadwal</span><span class="value" style="color:var(--teal-400);font-weight:700;">{{ \Carbon\Carbon::parse($konseling->jadwal_konseling)->format('d M Y H:i') }}</span></div>
            @endif
            @if($konseling->konfirmasi_pengguna)
            <div class="result-detail"><span class="label">Status Konfirmasi</span><span class="badge {{ $konseling->konfirmasi_pengguna == 'Hadir' ? 'badge-risiko-rendah' : 'badge-risiko-tinggi' }}">{{ $konseling->konfirmasi_pengguna }}</span></div>
            @endif
            @if($konseling->catatan_reschedule)
            <div class="result-detail" style="border-top:1px solid var(--gray-100); padding-top:var(--space-md); margin-top:var(--space-md);">
                <span class="label" style="color:var(--amber-800);"><i class="fas fa-info-circle"></i> Permintaan Reschedule</span>
                <span class="value" style="display:block; margin-top:4px;">{{ $konseling->catatan_reschedule }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Update Status -->
    <div class="card">
        <div class="card-header"><h4 style="margin-bottom:0;">Update Status & Jadwal</h4></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.konseling.status', $konseling->id_konseling) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Menunggu" {{ $konseling->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Dijadwalkan" {{ $konseling->status == 'Dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                        <option value="Selesai" {{ $konseling->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jadwal Konseling</label>
                    <input type="datetime-local" name="jadwal_konseling" class="form-control" value="{{ $konseling->jadwal_konseling ? \Carbon\Carbon::parse($konseling->jadwal_konseling)->format('Y-m-d\TH:i') : '' }}">
                </div>
                @if($konseling->jenis == 'Tatap Muka')
                <div class="form-group">
                    <label class="form-label">Lokasi Konseling</label>
                    <input type="text" name="lokasi_konseling" class="form-control"
                           placeholder="Contoh: Puskesmas Sehat, Jl. Kesehatan No. 1"
                           value="{{ $konseling->lokasi_konseling }}">
                    <small style="color:var(--gray-400);">Isi lokasi tempat konseling tatap muka akan dilaksanakan</small>
                </div>
                @endif
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            </form>
        </div>
    </div>
</div>

@if($konseling->konfirmasi_pengguna === 'Minta Reschedule')
<div class="card mb-xl" style="border:1px solid var(--amber-200); border-left:4px solid var(--amber-400); background:var(--amber-50);">
    <div class="card-header"><h4 style="margin-bottom:0;"><i class="fas fa-rotate" style="color:var(--amber-800);"></i> Permintaan Reschedule</h4></div>
    <div class="card-body">
        <div class="result-detail"><span class="label">Tanggal diminta</span><span class="value">{{ $konseling->tanggal_reschedule_diminta ? \Carbon\Carbon::parse($konseling->tanggal_reschedule_diminta)->format('d M Y H:i') : '-' }}</span></div>
        @if($konseling->catatan_reschedule)
            <div class="result-detail"><span class="label">Alasan</span><span class="value">{{ $konseling->catatan_reschedule }}</span></div>
        @endif
        <div style="border-top:1px solid var(--amber-200); margin-top:var(--space-md); padding-top:var(--space-md);">
            <div style="max-width:400px; margin:0 auto;">
            <form method="POST" action="{{ route('admin.konseling.setuju-reschedule', $konseling->id_konseling) }}" style="margin-bottom:var(--space-sm);">
                @csrf
                @method('PUT')
                <button class="btn btn-primary btn-block"><i class="fas fa-check-circle"></i> Setuju & Jadwalkan</button>
            </form>
            <form method="POST" action="{{ route('admin.konseling.jadwalkan-ulang', $konseling->id_konseling) }}">
                @csrf
                @method('PUT')
                <input type="datetime-local" name="jadwal_konseling" class="form-control mb-sm" required>
                <button class="btn btn-amber btn-block"><i class="fas fa-calendar-alt"></i> Jadwalkan Ulang</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Catatan Konseling -->
<div class="card mb-xl">
    <div class="card-header d-flex justify-between align-center">
        <h4 style="margin-bottom:0;"><i class="fas fa-file-medical" style="color:var(--teal-400);"></i> Catatan Konseling</h4>
    </div>
    <div class="card-body">
        @foreach($konseling->catatanKonseling as $c)
        <div class="card mb-md" style="background:var(--gray-50);">
            <div class="card-body">
                <div class="d-flex justify-between mb-sm">
                    <strong>{{ $c->tanggal_catatan->format('d M Y') }}</strong>
                    <span class="badge badge-gray">Oleh Admin</span>
                </div>
                <p style="margin-bottom:0;">{{ $c->deskripsi_hasil }}</p>
            </div>
        </div>
        @endforeach

        <form method="POST" action="{{ route('admin.konseling.catatan', $konseling->id_konseling) }}" class="mt-lg">
            @csrf
            <div class="form-group">
                <label class="form-label">Tambah Catatan Baru</label>
                <textarea name="deskripsi_hasil" class="form-control" rows="3" placeholder="Deskripsi atau hasil konseling..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Simpan Catatan</button>
        </form>
    </div>
</div>

<!-- Rujukan -->
<div class="card">
    <div class="card-header d-flex justify-between align-center">
        <h4 style="margin-bottom:0;"><i class="fas fa-hospital" style="color:var(--amber-400);"></i> Rujukan Fasilitas Kesehatan</h4>
    </div>
    <div class="card-body">
        @foreach($konseling->rujukan as $r)
        <div class="card mb-md" style="background:var(--amber-50); border-color:var(--amber-100);">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.rujukan.update', $r->id_rujukan) }}" class="d-flex gap-md align-center flex-wrap">
                    @csrf @method('PUT')
                    <input type="text" name="lokasi_rujukan" class="form-control" value="{{ $r->lokasi_rujukan }}" required style="flex:2;">
                    <input type="date" name="tanggal_rujukan" class="form-control" value="{{ $r->tanggal_rujukan->format('Y-m-d') }}" required style="flex:1;">
                    <select name="status" class="form-control" required style="flex:1;">
                        <option value="Aktif" {{ $r->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Selesai" {{ $r->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ $r->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i></button>
                </form>
                <form method="POST" action="{{ route('admin.rujukan.destroy', $r->id_rujukan) }}" style="margin-top:8px;" onsubmit="return confirm('Yakin hapus rujukan ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                </form>
            </div>
        </div>
        @endforeach

        <form method="POST" action="{{ route('admin.konseling.rujukan.store', $konseling->id_konseling) }}" class="mt-lg">
            @csrf
            <h5>Tambah Rujukan Baru</h5>
            <div class="grid grid-3">
                <div class="form-group">
                    <label class="form-label">Lokasi Rujukan *</label>
                    <input type="text" name="lokasi_rujukan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Rujukan *</label>
                    <input type="date" name="tanggal_rujukan" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Rujukan</button>
        </form>
    </div>
</div>
@endsection
