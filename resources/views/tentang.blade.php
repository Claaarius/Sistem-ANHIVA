@extends('layouts.app')
@section('title', 'Tentang Kami - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <div class="section-header text-center mb-xl">
            <h2 style="font-size:2.5rem; font-weight:800; color:var(--teal-800);">Tentang ANHIVA</h2>
            <p style="color:var(--gray-500); font-size:1.1rem; max-width:600px; margin:0 auto;">Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim</p>
            <div class="section-line" style="margin: 20px auto;"></div>
        </div>

        <div class="card fade-in mb-lg" style="border-radius:12px; overflow:hidden; box-shadow:var(--shadow-md);">
            <div style="background:var(--teal-50); padding:var(--space-2xl); text-align:center;">
                <i class="fas fa-shield-halved" style="font-size:4rem; color:var(--teal-600); margin-bottom:var(--space-md);"></i>
                <h3 style="color:var(--teal-800); font-weight:700;">Visi & Misi</h3>
                <p style="color:var(--gray-600); font-size:1rem; line-height:1.7; max-width:700px; margin:0 auto;">
                    ANHIVA didedikasikan untuk memberikan edukasi yang komprehensif serta deteksi dini risiko HIV dengan menjamin <strong>privasi dan anonimitas</strong> penuh penggunanya. Kami percaya bahwa setiap individu berhak mendapatkan akses informasi dan layanan kesehatan tanpa takut akan stigma atau diskriminasi.
                </p>
            </div>
            <div class="card-body" style="padding:var(--space-xl);">
                <div class="grid grid-3 gap-lg text-center">
                    <div>
                        <div style="width:60px; height:60px; background:var(--teal-100); color:var(--teal-600); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-md); font-size:1.5rem;">
                            <i class="fas fa-user-secret"></i>
                        </div>
                        <h4 style="font-size:1.1rem; font-weight:700;">100% Anonim</h4>
                        <p style="color:var(--gray-500); font-size:0.9rem;">Anda dapat menggunakan layanan skrining dan konseling tanpa harus memberikan identitas asli.</p>
                    </div>
                    <div>
                        <div style="width:60px; height:60px; background:var(--amber-100); color:var(--amber-600); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-md); font-size:1.5rem;">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h4 style="font-size:1.1rem; font-weight:700;">Skrining Mandiri</h4>
                        <p style="color:var(--gray-500); font-size:0.9rem;">Fitur skrining kami dirancang untuk membantu Anda mengidentifikasi tingkat risiko secara mandiri.</p>
                    </div>
                    <div>
                        <div style="width:60px; height:60px; background:var(--blue-100); color:var(--blue-600); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-md); font-size:1.5rem;">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4 style="font-size:1.1rem; font-weight:700;">Konseling Aman</h4>
                        <p style="color:var(--gray-500); font-size:0.9rem;">Tersedia dukungan konselor profesional secara tatap muka maupun online yang menjaga kerahasiaan Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-xl fade-in">
            <h3 style="font-weight:700; margin-bottom:var(--space-md);">Punya Pertanyaan?</h3>
            <p style="color:var(--gray-500); margin-bottom:var(--space-lg);">Tim layanan dan konselor kami siap membantu Anda kapan saja.</p>
            <a href="{{ route('konseling.pengajuan') }}" class="btn btn-primary btn-lg" style="border-radius:99px; padding:12px 32px;"><i class="fas fa-comments"></i> Hubungi Konselor</a>
        </div>
    </div>
</section>
@endsection
