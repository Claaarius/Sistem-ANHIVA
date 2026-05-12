@extends('layouts.app')
@section('title', 'Riwayat Skrining - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <div class="section-header">
            <h2>Riwayat Skrining</h2>
            <p>Lihat riwayat skrining yang pernah Anda lakukan</p>
            <div class="section-line"></div>
        </div>

        @guest
        @if(!$riwayat)
        <div class="card fade-in">
            <div class="card-body" style="padding:var(--space-2xl); text-align:center;">
                <i class="fas fa-key" style="font-size:3rem; color:var(--gray-100); margin-bottom:var(--space-lg);"></i>
                <h3>Masukkan Kode Unik</h3>
                <p style="color:var(--gray-400); margin-bottom:var(--space-lg);">Masukkan kode unik ANH-XXXXXXXXXX yang Anda dapatkan saat melakukan skrining untuk melihat riwayat.</p>
                <form method="GET" action="{{ route('skrining.riwayat') }}" style="max-width:400px; margin:0 auto;">
                    <div class="form-group">
                        <input type="text" name="kode_unik" class="form-control" placeholder="ANH-XXXXXXXXXX" style="text-align:center; font-size:1.1rem; font-family:monospace;" required pattern="ANH-[A-Za-z0-9]{6}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Cari Riwayat</button>
                </form>
            </div>
        </div>
        @endif
        @endguest

        @if($riwayat !== null)
            @if($riwayat->count() > 0)
            <div class="table-wrapper fade-in">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Unik</th>
                            <th>Skor</th>
                            <th>Risiko</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $r)
                        <tr>
                            <td>{{ $r->tanggal_skrining->format('d M Y, H:i') }}</td>
                            <td><code style="color:var(--teal-400);">{{ $r->kode_unik }}</code></td>
                            <td><strong>{{ $r->skor_total }}</strong></td>
                            <td><span class="badge {{ $r->risiko_badge_class }}">{{ $r->tingkat_risiko }}</span></td>
                            <td><a href="{{ route('skrining.hasil', $r->id_skrining) }}" class="btn btn-sm btn-outline">Detail</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $riwayat->withQueryString()->links('pagination.simple') }}
            @else
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Belum ada riwayat skrining</h3>
                <p>Riwayat skrining Anda akan muncul di sini.</p>
                <a href="{{ route('skrining.mulai') }}" class="btn btn-primary mt-md">Mulai Skrining</a>
            </div>
            @endif
        @endif
    </div>
</section>
@endsection
