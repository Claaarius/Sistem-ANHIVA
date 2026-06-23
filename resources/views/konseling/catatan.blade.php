@extends('layouts.app')
@section('title', 'Catatan Konseling - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <a href="{{ route('konseling.index') }}" class="btn btn-sm btn-secondary mb-lg"><i class="fas fa-arrow-left"></i> Kembali</a>

        <h2>Catatan Konseling</h2>

        @guest
        @if(!$catatan)
        <div class="card fade-in">
            <div class="card-body" style="padding:var(--space-2xl); text-align:center;">
                <i class="fas fa-key" style="font-size:3rem; color:var(--gray-100); margin-bottom:var(--space-lg);"></i>
                <h3>Masukkan Kode Unik</h3>
                <p style="color:var(--gray-400); margin-bottom:var(--space-lg);">Masukkan kode unik ANH-XXXXXXXXXX untuk melihat catatan konseling Anda.</p>
                <form method="GET" action="{{ route('konseling.catatan') }}" style="max-width:400px; margin:0 auto;">
                    <div class="form-group">
                        <input type="text" name="kode_unik" class="form-control @error('kode_unik') is-invalid @enderror" placeholder="ANH-XXXXXXXXXX" value="{{ old('kode_unik') }}" style="text-align:center; font-size:1.1rem; font-family:monospace;" required>
                        @error('kode_unik')
                            <div style="color:#C53030; font-size:0.9rem; margin-top:0.5rem;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Lihat Catatan</button>
                </form>
            </div>
        </div>
        @endif
        @endguest

        @if($catatan !== null && $catatan->count() > 0)
            @foreach($catatan as $c)
            <div class="card fade-in mb-lg">
                <div class="card-body">
                    <div class="d-flex justify-between align-center mb-lg">
                        <h4 style="margin-bottom:0;"><i class="fas fa-file-medical" style="color:var(--teal-400);"></i> Catatan Konseling</h4>
                        <span style="font-size:0.8rem; color:var(--gray-400);">{{ $c->tanggal_catatan->format('d M Y H:i') }}</span>
                    </div>
                    <div style="padding:var(--space-md); background:var(--gray-50); border-radius:var(--radius-md); border-left:4px solid var(--teal-400);">
                        <p style="font-size:0.95rem; line-height:1.8; margin:0; color:var(--gray-700);">{{ $c->deskripsi_hasil }}</p>
                    </div>

                    @if($c->konseling && $c->konseling->rujukan && $c->konseling->rujukan->count() > 0)
                    <div style="margin-top:var(--space-lg);">
                        <h5 style="margin-bottom:var(--space-md);"><i class="fas fa-hospital" style="color:var(--amber-400);"></i> Rujukan Fasilitas Kesehatan</h5>
                        @foreach($c->konseling->rujukan as $r)
                        <div class="card mb-sm" style="background:var(--amber-50); border-color:var(--amber-100);">
                            <div class="card-body" style="padding:var(--space-md);">
                                <strong>{{ $r->lokasi_rujukan }}</strong>
                                <div style="font-size:0.85rem; color:var(--gray-500); margin-top:4px;">
                                    Tanggal: {{ $r->tanggal_rujukan->format('d M Y') }} • 
                                    Status: <span class="badge badge-{{ $r->status == 'Aktif' ? 'teal' : ($r->status == 'Selesai' ? 'gray' : 'amber') }}">{{ $r->status }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        @elseif($catatan !== null && $catatan->count() === 0)
            <div class="empty-state">
                <i class="fas fa-file-medical"></i>
                <h3>Belum ada catatan konseling</h3>
                <p>Catatan akan muncul setelah sesi konseling Anda selesai.</p>
            </div>
        @endif
    </div>
</section>
@endsection
