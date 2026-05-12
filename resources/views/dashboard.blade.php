@extends('layouts.app')
@section('title', 'ANHIVA - Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim')

@section('content')
<!-- Section 1: Hero -->
<section class="hero" style="position:relative; min-height:70vh; display:flex; align-items:center; padding:var(--space-2xl) 0;">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between; position:relative; z-index:2;">
        <div class="hero-content slide-up" style="max-width:600px;">
            <h1 style="font-size:2.4rem;">{{ $hero->judul ?? 'ANHIVA' }}</h1>
            <p>{{ $hero->konten ?? 'Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim. Lindungi diri Anda dengan pengetahuan yang tepat dan skrining risiko.' }}</p>
            <a href="{{ route('skrining.index') }}" class="btn btn-primary btn-lg" style="box-shadow:var(--shadow-md);">
                <i class="fas fa-clipboard-list"></i> {{ $hero->tombol_teks ?? 'Mulai Skrining' }}
            </a>
            <div style="margin-top:var(--space-2xl); opacity:0.8; font-size:0.9rem;">
                <span>Scroll ke bawah untuk informasi lebih lanjut <i class="fas fa-arrow-down floating-shape" style="margin-left:8px;"></i></span>
            </div>
        </div>
        <!-- Decorative Icon -->
        <div class="hero-image floating-shape" style="opacity:0.2; transform:scale(1.5);">
            <svg width="300" height="300" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <path d="M12 8v4"></path>
                <path d="M10 10h4"></path>
            </svg>
        </div>
    </div>
</section>

<!-- Section 2: Statistik & Fakta HIV -->
<section class="section" id="stats-section" style="background:#F1EFE8;">
    <div class="container">
        <div class="section-header">
            <h2>Statistik Sistem</h2>
            <p>Data penggunaan sistem ANHIVA secara real-time</p>
            <div class="section-line"></div>
        </div>

        <div class="grid grid-4" style="margin-bottom: var(--space-3xl);">
            <div class="stat-card" style="padding:var(--space-xl) var(--space-lg);">
                <div class="stat-icon teal" style="background:linear-gradient(135deg, var(--teal-50), white);"><i class="fas fa-clipboard-check"></i></div>
                <div class="stat-number count-anim" data-target="{{ $totalSkrining ?? 0 }}">0</div>
                <div class="stat-label">Total Skrining</div>
            </div>
            <div class="stat-card" style="padding:var(--space-xl) var(--space-lg);">
                <div class="stat-icon teal" style="background:linear-gradient(135deg, var(--teal-50), white);"><i class="fas fa-chart-pie"></i></div>
                <div class="stat-number">{{ ($distribusiRisiko['rendah']??0) }}/{{ ($distribusiRisiko['sedang']??0) }}/{{ ($distribusiRisiko['tinggi']??0) }}</div>
                <div class="stat-label">Rendah / Sedang / Tinggi</div>
            </div>
            <div class="stat-card" style="padding:var(--space-xl) var(--space-lg);">
                <div class="stat-icon amber" style="background:linear-gradient(135deg, var(--amber-50), white);"><i class="fas fa-users"></i></div>
                <div class="stat-number count-anim" data-target="{{ $totalPengguna ?? 0 }}">0</div>
                <div class="stat-label">Pengguna Terdaftar</div>
            </div>
            <div class="stat-card" style="padding:var(--space-xl) var(--space-lg);">
                <div class="stat-icon amber" style="background:linear-gradient(135deg, var(--amber-50), white);"><i class="fas fa-book-open"></i></div>
                <div class="stat-number count-anim" data-target="{{ $totalMateri ?? 0 }}">0</div>
                <div class="stat-label">Materi Edukasi</div>
            </div>
        </div>

    </div>
</section>

@if(isset($faktaHiv) && $faktaHiv->count() > 0)
<section class="section" style="background:#fff;">
    <div class="container">
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:var(--space-lg);">
                <h3 class="text-center mb-0" style="flex:1;">Fakta & Statistik HIV di Indonesia</h3>

                @if($faktaHiv->count() > 4)
                <div style="display:flex; gap:10px;">
                    <button type="button" class="fact-page-btn" onclick="changeFactPage(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="fact-page-btn" onclick="changeFactPage(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                @endif
            </div>

            @foreach($faktaHiv->values()->chunk(4) as $pageIndex => $faktaPage)
            <div class="grid grid-2 fact-page {{ $pageIndex === 0 ? 'active' : '' }}" style="gap:var(--space-lg);" data-page="{{ $pageIndex }}">
                @foreach($faktaPage->values() as $index => $fakta)
                    @php
                        $nomorFakta = ($pageIndex * 4) + $index + 1;
                    @endphp

                    <div class="fact-card" style="position:relative; padding:var(--space-2xl); border-left:4px solid var(--amber-400); box-shadow:var(--shadow-sm); min-height:220px;">
                        <div style="position:absolute; top:0; left:12px; font-size:3rem; font-weight:900; color:var(--amber-100); line-height:1; z-index:0;">
                            {{ str_pad($nomorFakta, 2, '0', STR_PAD_LEFT) }}
                        </div>

                        <div style="position:relative; z-index:1;">
                            <div class="fact-title">
                                <i class="fas fa-info-circle" style="color:var(--amber-400);"></i>
                                {{ $fakta->judul }}
                            </div>

                            <div class="fact-text">{{ $fakta->konten }}</div>

                            @if($fakta->sumber)
                                <div class="fact-source">Sumber: {{ $fakta->sumber }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @endforeach

            @if($faktaHiv->count() > 4)
            <div class="text-center" style="margin-top:var(--space-lg); color:var(--gray-400); font-size:0.9rem;">
                <span id="factPageInfo">1</span> / {{ ceil($faktaHiv->count() / 4) }}
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Section 3: Fitur Utama -->
<section class="section" style="background-color: #f0f4f8;">
    <div class="container">
        <div class="section-header">
            <h2>Fitur Utama ANHIVA</h2>
            <p>Layanan lengkap untuk edukasi dan skrining risiko HIV secara anonim</p>
            <div class="section-line"></div>
        </div>

        <div class="grid grid-3">
            <div class="feature-card" style="padding:var(--space-xl); transition:all 0.3s; border: 2px solid transparent; border-radius:var(--radius-lg); background:white;" onmouseover="this.style.borderColor='var(--teal-200)'" onmouseout="this.style.borderColor='transparent'">
                <div style="font-size:1.5rem; font-weight:800; color:var(--gray-100); margin-bottom:var(--space-sm);">01</div>
                <div class="feature-icon"><i class="fas fa-clipboard-list"></i></div>
                <h4>Skrining Risiko</h4>
                <p>Lakukan penilaian risiko HIV secara mandiri dan anonim. Dapatkan hasil analisis dengan rekomendasi tindak lanjut yang tepat.</p>
            </div>
            <div class="feature-card" style="padding:var(--space-xl); transition:all 0.3s; border: 2px solid transparent; border-radius:var(--radius-lg); background:white;" onmouseover="this.style.borderColor='var(--teal-200)'" onmouseout="this.style.borderColor='transparent'">
                <div style="font-size:1.5rem; font-weight:800; color:var(--gray-100); margin-bottom:var(--space-sm);">02</div>
                <div class="feature-icon" style="background:var(--amber-50); color:var(--amber-400);"><i class="fas fa-book-open"></i></div>
                <h4>Edukasi HIV/AIDS</h4>
                <p>Akses materi edukasi lengkap tentang HIV/AIDS meliputi pencegahan, pengobatan, mitos dan fakta, serta informasi terkini.</p>
            </div>
            <div class="feature-card" style="padding:var(--space-xl); transition:all 0.3s; border: 2px solid transparent; border-radius:var(--radius-lg); background:white;" onmouseover="this.style.borderColor='var(--teal-200)'" onmouseout="this.style.borderColor='transparent'">
                <div style="font-size:1.5rem; font-weight:800; color:var(--gray-100); margin-bottom:var(--space-sm);">03</div>
                <div class="feature-icon"><i class="fas fa-comments"></i></div>
                <h4>Konseling</h4>
                <p>Ajukan konseling bersama konselor profesional secara online maupun tatap muka sebagai tindak lanjut dari hasil skrining.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Materi Edukasi Terbaru -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Materi Edukasi Terbaru</h2>
            <p>Pelajari informasi penting seputar HIV/AIDS</p>
            <div class="section-line"></div>
        </div>

        @if(isset($materiTerbaru) && $materiTerbaru->count() > 0)
        <div class="grid grid-3">
            @foreach($materiTerbaru as $m)
            <div class="card" style="display:flex; flex-direction:column;">
                <div style="height:180px; background:linear-gradient(135deg, var(--teal-600), var(--teal-300)); display:flex; align-items:center; justify-content:center;">
                    @if($m->thumbnail)
                        <img src="{{ $m->thumbnail }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="{{ $m->judul }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <i class="fas fa-book-open" style="font-size:3rem; color:#fff;"></i>
                    @endif
                </div>
                <div class="card-body" style="padding:var(--space-md);">
                    <span class="badge badge-teal mb-sm">{{ $m->kategori }}</span>
                    <h4 class="card-title" style="font-size:1rem;">{{ $m->judul }}</h4>
                    <p class="card-text" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $m->ringkasan }}</p>
                    <span style="font-size:0.8rem; color:var(--gray-400); margin-bottom:var(--space-sm); display:block;">
                        <i class="far fa-calendar"></i> {{ $m->tanggal_publish->format('d M Y') }}
                    </span>
                    <a href="{{ route('edukasi.show', $m->id_materi) }}" class="btn btn-sm btn-outline">Baca</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-xl">
            <a href="{{ route('edukasi.index') }}" class="btn btn-primary">Lihat Semua Materi <i class="fas fa-arrow-right"></i></a>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>Belum ada materi edukasi</h3>
            <p>Materi edukasi akan segera tersedia.</p>
        </div>
        @endif
    </div>
</section>

<!-- Section 5: Gallery -->
@if(isset($gallery) && $gallery->count() > 0)
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <div class="section-header">
            <h2>Gallery Dokumentasi</h2>
            <p>Kegiatan dan dokumentasi mitra ANHIVA</p>
            <div class="section-line"></div>
        </div>

        <div class="gallery-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(250px, 1fr)); gap:1rem;">
            @foreach($gallery as $g)
            <div class="gallery-item" style="position:relative; overflow:hidden; border-radius:var(--radius-md); cursor:pointer; height:200px;"
                 onclick="showGalleryLightbox('{{ $g->foto }}', '{{ addslashes($g->keterangan) }}')"
                 onmouseover="this.querySelector('img').style.transform='scale(1.1)'; this.querySelector('.gallery-caption').style.bottom='0';"
                 onmouseout="this.querySelector('img').style.transform='scale(1)'; this.querySelector('.gallery-caption').style.bottom='-100%';">
                <img src="{{ $g->foto }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="{{ $g->keterangan }}" style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s;">
                <div class="gallery-caption" style="position:absolute; bottom:-100%; left:0; width:100%; background:rgba(0,0,0,0.7); color:white; padding:10px; transition:bottom 0.3s; font-size:0.85rem;">
                    {{ $g->keterangan }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Section 6: Komentar & Testimoni -->
<section class="section" style="background: #ffffff; background-image: radial-gradient(rgba(0,0,0,0.03) 1px, transparent 1px); background-size: 20px 20px;">
    <div class="container">
        <div class="section-header">
            <h2>Pengalaman Pengguna</h2>
            <p>Testimoni dan pengalaman dari pengguna ANHIVA</p>
            <div class="section-line"></div>
        </div>

        @if(isset($komentar) && $komentar->count() > 0)
<div class="comment-slider-wrapper" style="position:relative; padding:0 60px;">

    <button type="button" class="comment-arrow comment-arrow-left" onclick="slideKomentar(-1)" title="Sebelumnya">
        <i class="fas fa-chevron-left"></i>
    </button>

    @foreach($komentar->chunk(6) as $index => $chunk)
        <div class="comment-page {{ $index == 0 ? 'active' : '' }}" data-page="{{ $index }}">
            <div class="grid grid-3">
                @foreach($chunk as $k)
                    @php
                        $namaTampil = $k->nama_tampil;
                        $isOwner = auth()->check() && $k->id_pengguna === auth()->id();
                        $canEdit = $isOwner && $k->status === 'Disetujui' && $k->jumlah_edit < 3;
                    @endphp

                    <div class="card" style="padding:var(--space-lg); position:relative; display:flex; flex-direction:column; height:100%;">
                        <div class="comment-quote">❝</div>
                        <div class="comment-card-content" style="display:flex; flex-direction:column; flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:var(--space-md);">
                                <div style="display:flex; align-items:center; gap:var(--space-sm);">
                                    @if($k->id_pengguna && $k->pengguna && $k->pengguna->foto_profil)
                                        <img src="{{ asset('storage/' . $k->pengguna->foto_profil) }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'48\' height=\'48\'%3E%3Crect width=\'48\' height=\'48\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'24\'%3E👤%3C/text%3E%3C/svg%3E';" alt="{{ $namaTampil }}" style="width:48px; height:48px; border-radius:50%; object-fit:cover; display:block;">
                                    @else
                                        <div style="width:48px; height:48px; border-radius:50%; background:linear-gradient(135deg, var(--teal-400), var(--teal-600)); color:white; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:1.2rem;">
                                            {{ strtoupper(substr($namaTampil, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:700;">{{ $namaTampil }}</div>
                                        <div style="font-size:0.75rem; color:var(--gray-400);">{{ $k->tanggal_komentar->format('d M Y') }}</div>
                                    </div>
                                </div>
                                @if($canEdit)
                                    <button onclick="toggleEditComment('form-{{ $k->id_komentar }}')" style="background:none; border:none; color:var(--gray-400); cursor:pointer;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                @endif
                            </div>

                            <div style="font-size:0.9rem; color:var(--gray-900); line-height:1.6; max-height: calc(1.6em * 6); overflow-y: auto;" id="text-{{ $k->id_komentar }}">
                                {{ $k->isi_komentar }}
                            </div>

                            <div style="margin-top:auto; padding-top:var(--space-sm);">
                                <button type="button" class="btn btn-sm btn-outline lihat-detail-btn"
                                    data-isi="{{ $k->isi_komentar }}"
                                    data-nama="{{ $namaTampil }}"
                                    data-tanggal="{{ $k->tanggal_komentar->format('d M Y') }}">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </button>
                            </div>

                            @if($canEdit)
                            <div id="form-{{ $k->id_komentar }}" style="display:none; margin-top:var(--space-md);">
                                <form action="{{ route('komentar.update', $k->id_komentar) }}" method="POST">
                                    @csrf @method('PUT')
                                    <textarea name="isi_komentar" class="form-control mb-sm" rows="3" required>{{ $k->isi_komentar }}</textarea>
                                    <div class="text-right">
                                        <button type="button" onclick="toggleEditComment('form-{{ $k->id_komentar }}')" class="btn btn-sm btn-secondary">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button type="button" class="comment-arrow comment-arrow-right" onclick="slideKomentar(1)" title="Selanjutnya">
        <i class="fas fa-chevron-right"></i>
    </button>

</div>

{{-- Dot Indicator --}}
@if($komentar->chunk(6)->count() > 1)
<div id="comment-dots" style="display:flex; justify-content:center; gap:8px; margin-top:var(--space-lg);">
    @foreach($komentar->chunk(6) as $i => $c)
    <button onclick="goToKomentarPage({{ $i }})" class="comment-dot {{ $i == 0 ? 'active' : '' }}" data-dot="{{ $i }}"></button>
    @endforeach
</div>
@endif
@else
        <div class="empty-state">
            <i class="fas fa-comments"></i>
            <h3>Belum ada komentar</h3>
            <p>Jadilah yang pertama berbagi pengalaman.</p>
        </div>
        @endif

        <!-- Comment Form -->
        <div class="card mt-xl" style="max-width:800px; margin-left:auto; margin-right:auto; border: 2px solid var(--teal-100); padding:var(--space-xl);">
            <div class="text-center mb-lg">
                <h3 style="font-size:1.5rem;"><i class="fas fa-pen" style="color:var(--teal-400);"></i> Bagikan Pengalaman Anda</h3>
                <p style="font-size:0.9rem;color:var(--gray-400);">Komentar Anda akan dibaca dan dapat membantu pengguna lain.</p>
            </div>
            <form method="POST" action="{{ route('komentar.store') }}">
                @csrf
                <div class="form-group">
                    <textarea name="isi_komentar" class="form-control" rows="5" placeholder="Tulis pengalaman atau testimoni Anda di sini..." required maxlength="1000" style="font-size:1rem; padding:var(--space-md);"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block" style="font-size:1.1rem;">Kirim Komentar</button>
            </form>
        </div>
    </div>
</section>
<style>
.comment-slider-wrapper {
    position: relative;
}

.comment-page {
    display: none;
    animation: fadeSlide 0.35s ease;
}

@keyframes fadeSlide {
    from { opacity: 0; transform: translateX(20px); }
    to   { opacity: 1; transform: translateX(0); }
}

.comment-page.active {
    display: block;
}

.comment-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 20;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 2px solid var(--teal-200);
    background: white;
    color: var(--teal-600);
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.comment-arrow:hover {
    background: var(--teal-500);
    color: white;
    border-color: var(--teal-500);
    box-shadow: 0 6px 20px rgba(29,158,117,0.3);
    transform: translateY(-50%) scale(1.08);
}

.comment-arrow-left {
    left: 4px;
}

.comment-arrow-right {
    right: 4px;
}

.comment-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: none;
    background: var(--gray-200);
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}

.comment-dot.active {
    background: var(--teal-500);
    width: 28px;
    border-radius: 5px;
}

@media (max-width: 768px) {
    .comment-arrow-left { left: 0; }
    .comment-arrow-right { right: 0; }
    .comment-slider-wrapper { padding: 0 44px; }
}
</style>
@endsection
@push('styles')
<style>
.fact-page {
    display: none;
}

.fact-page.active {
    display: grid;
    min-height: 460px;
    align-content: start;
}

.fact-page-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 50%;
    background: var(--amber-400);
    color: white;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.fact-page-btn:hover {
    transform: translateY(-2px);
    background: #d98f16;
}

@media (max-width: 768px) {
    .fact-page.active {
        grid-template-columns: 1fr;
        min-height: 240px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentKomentarPage = 0;

function slideKomentar(direction) {
    const pages = document.querySelectorAll('.comment-page');
    if (pages.length === 0) return;

    pages[currentKomentarPage].classList.remove('active');

    currentKomentarPage += direction;

    if (currentKomentarPage < 0) {
        currentKomentarPage = pages.length - 1;
    }
    if (currentKomentarPage >= pages.length) {
        currentKomentarPage = 0;
    }

    pages[currentKomentarPage].classList.add('active');
    updateDots(currentKomentarPage);
}

function goToKomentarPage(index) {
    const pages = document.querySelectorAll('.comment-page');
    if (pages.length === 0) return;
    pages[currentKomentarPage].classList.remove('active');
    currentKomentarPage = index;
    pages[currentKomentarPage].classList.add('active');
    updateDots(currentKomentarPage);
}

function updateDots(active) {
    document.querySelectorAll('.comment-dot').forEach((dot, i) => {
        dot.classList.toggle('active', i === active);
    });
}

// Animated counter with Intersection Observer
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.count-anim');
    const speed = 200;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const updateCount = () => {
                    const target = +entry.target.getAttribute('data-target');
                    const count = +entry.target.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        entry.target.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 10);
                    } else {
                        entry.target.innerText = target.toLocaleString();
                    }
                };
                updateCount();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});

// Gallery Lightbox
function showGalleryLightbox(imgSrc, keterangan) {
    Swal.fire({
        imageUrl: imgSrc,
        imageAlt: 'Gallery Image',
        title: keterangan,
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'gallery-lightbox'
        },
        padding: '1rem'
    });
}

// Edit Comment Toggle
function toggleEditComment(id) {
    const el = document.getElementById(id);
    const textEl = document.getElementById('text-' + id.split('-')[1]);

    if (el.style.display === 'none') {
        el.style.display = 'block';
        textEl.style.display = 'none';
    } else {
        el.style.display = 'none';
        textEl.style.display = 'block';
    }
}

// Detail Komentar
function lihatDetailKomentar(isi, nama, tanggal) {
    Swal.fire({
        title: '<i class="fas fa-comment-alt" style="color:var(--teal-500);margin-right:8px;"></i>Detail Komentar',
        html: `
            <div style="text-align:left; font-size:0.92rem;">
                <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; width:110px; vertical-align:top;">Pengguna</td>
                        <td style="padding:5px 8px; font-weight:600;">${nama}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; vertical-align:top;">Tanggal</td>
                        <td style="padding:5px 8px;">${tanggal}</td>
                    </tr>
                </table>
                <div style="border-top:1px solid #e5e7eb; padding-top:14px;">
                    <div style="font-size:0.78rem; text-transform:uppercase; font-weight:700; color:#9ca3af; margin-bottom:8px; letter-spacing:.05em;">Isi Komentar</div>
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:14px; line-height:1.75; max-height:300px; overflow-y:auto; white-space:pre-wrap; word-break:break-word;">${isi}</div>
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#1D9E75',
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.lihat-detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            lihatDetailKomentar(this.dataset.isi, this.dataset.nama, this.dataset.tanggal);
        });
    });
});
let currentFactPage = 0;

function changeFactPage(direction) {
    const pages = document.querySelectorAll('.fact-page');
    if (!pages.length) return;

    pages[currentFactPage].classList.remove('active');

    currentFactPage += direction;

    if (currentFactPage < 0) {
        currentFactPage = pages.length - 1;
    }

    if (currentFactPage >= pages.length) {
        currentFactPage = 0;
    }

    pages[currentFactPage].classList.add('active');

    const info = document.getElementById('factPageInfo');
    if (info) {
        info.textContent = currentFactPage + 1;
    }
}
@if(session('success') && str_contains(session('success'), 'Komentar'))
Swal.fire({
    icon: 'success',
    title: 'Komentar Terkirim!',
    text: 'Komentar Anda sedang menunggu persetujuan admin.',
    confirmButtonColor: '#1D9E75',
    confirmButtonText: 'OK',
    timer: 4000,
    timerProgressBar: true,
});
@endif

</script>
@endpush
