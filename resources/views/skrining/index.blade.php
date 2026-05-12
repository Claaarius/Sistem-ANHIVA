@extends('layouts.app')
@section('title', 'Skrining Risiko HIV - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <div class="section-header">
            <h2>Skrining Risiko HIV</h2>
            <p>Lakukan penilaian risiko HIV secara mandiri dan anonim</p>
            <div class="section-line"></div>
        </div>

        <div class="card fade-in">
            <div class="card-body" style="padding: var(--space-2xl);">
                <h3><i class="fas fa-list-check" style="color:var(--teal-400);"></i> Tata Cara Skrining</h3>
                <ol style="margin:var(--space-lg) 0; padding-left:var(--space-lg); line-height:2;">
                    <li>Baca setiap pertanyaan dengan seksama</li>
                    <li>Pilih jawaban yang paling sesuai dengan kondisi Anda</li>
                    <li>Anda dapat kembali ke pertanyaan sebelumnya jika diperlukan</li>
                    <li>Setelah selesai, sistem akan menghitung skor dan menampilkan tingkat risiko Anda</li>
                    <li>Hasil skrining dapat diunduh dalam bentuk PDF atau gambar</li>
                </ol>

                <div class="disclaimer-box">
                    <div class="disclaimer-title">
                        <i class="fas fa-exclamation-triangle"></i> Disclaimer Penting
                    </div>
                    <p>Hasil skrining ini <strong>bukan merupakan diagnosis medis</strong>. Skrining ini bertujuan untuk memberikan gambaran awal mengenai tingkat risiko HIV Anda. Untuk diagnosis yang akurat, silakan konsultasikan dengan tenaga kesehatan profesional di fasilitas kesehatan terdekat.</p>
                </div>

                @guest
                <div class="card" style="background:var(--teal-50); border-color:var(--teal-200); margin-bottom:var(--space-lg);">
                    <div class="card-body">
                        <p style="margin-bottom:var(--space-sm); font-size:0.9rem;">
                            <i class="fas fa-info-circle" style="color:var(--teal-400);"></i>
                            <strong>Login untuk menyimpan riwayat secara otomatis.</strong> Jika Anda melanjutkan tanpa login, Anda akan mendapat kode unik anonim yang harus disimpan untuk mengakses riwayat.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline">Login Terlebih Dahulu</a>
                    </div>
                </div>
                @endguest

                <div class="d-flex gap-md flex-wrap">
                    <a href="{{ route('skrining.mulai') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-play"></i> Mulai Skrining
                    </a>
                    <a href="{{ route('skrining.riwayat') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-history"></i> Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
