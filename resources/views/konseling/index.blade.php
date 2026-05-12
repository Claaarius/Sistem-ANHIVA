@extends('layouts.app')
@section('title', 'Konseling - ANHIVA')

@section('content')
<section class="hero-small" style="background:var(--teal-600); color:white; padding:var(--space-3xl) 0;">
    <div class="container text-center">
        <h1 style="font-size:2.5rem; color:white; margin-bottom:var(--space-md);">Pusat Layanan Konseling</h1>
        <p style="font-size:1.1rem; max-width:600px; margin:0 auto; color:rgba(255,255,255,0.9);">Dapatkan pendampingan dari konselor profesional secara anonim melalui online maupun tatap muka.</p>
    </div>
</section>

<section class="section" style="background:var(--gray-50); min-height:60vh;">
    <div class="container">
        <div class="grid grid-3">
            <div class="card" style="transition:transform 0.3s; border:none; box-shadow:var(--shadow-md);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                <div class="card-body" style="text-align:center; padding:var(--space-2xl);">
                    <div class="feature-icon" style="margin:0 auto var(--space-lg); background:var(--teal-50); color:var(--teal-400); font-size:2rem; width:80px; height:80px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fas fa-pen-to-square"></i></div>
                    <h4>Pengajuan Baru</h4>
                    <p class="card-text mb-xl">Ajukan sesi konseling baru untuk mendiskusikan kekhawatiran Anda.</p>
                    <a href="{{ route('konseling.pengajuan') }}" class="btn btn-primary btn-block">Ajukan Konseling</a>
                </div>
            </div>
            
            <div class="card" style="transition:transform 0.3s; border:none; box-shadow:var(--shadow-md);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                <div class="card-body" style="text-align:center; padding:var(--space-2xl);">
                    <div class="feature-icon" style="background:var(--amber-50); color:var(--amber-400); margin:0 auto var(--space-lg); font-size:2rem; width:80px; height:80px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fas fa-history"></i></div>
                    <h4>Riwayat Pengajuan</h4>
                    <p class="card-text mb-xl">Pantau status, jadwalkan ulang, dan konfirmasi kehadiran.</p>
                    <a href="{{ route('konseling.riwayat') }}" class="btn btn-amber btn-block">Lihat Riwayat</a>
                </div>
            </div>

            <div class="card" style="transition:transform 0.3s; border:none; box-shadow:var(--shadow-md);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                <div class="card-body" style="text-align:center; padding:var(--space-2xl);">
                    <div class="feature-icon" style="background:#E1F5EE; color:#0F6E56; margin:0 auto var(--space-lg); font-size:2rem; width:80px; height:80px; display:flex; align-items:center; justify-content:center; border-radius:50%;"><i class="fas fa-file-medical"></i></div>
                    <h4>Catatan Konselor</h4>
                    <p class="card-text mb-xl">Lihat rekomendasi dan catatan dari konselor setelah sesi selesai.</p>
                    <a href="{{ route('konseling.catatan') }}" class="btn btn-outline btn-block">Cek Catatan</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
