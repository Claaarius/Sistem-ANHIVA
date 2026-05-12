@extends('layouts.app')
@section('title', 'Edukasi HIV/AIDS - ANHIVA')

@section('content')
    <section class="section">
        <div class="container">
            <div class="card mb-xl"
                style="height:220px; background:linear-gradient(135deg, var(--teal-800), var(--teal-400)); color:#fff; overflow:hidden; position:relative;">
                <div class="card-body"
                    style="height:100%; display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <h1 style="font-size:2.2rem; font-weight:800; color:#fff; margin-bottom:8px;">Materi Edukasi
                            HIV/AIDS</h1>
                        <p style="color:rgba(255,255,255,0.9); margin:0;">Belajar dari materi terpercaya untuk pencegahan,
                            pemahaman, dan dukungan yang tepat.</p>
                    </div>
                    <div style="font-size:7rem; opacity:0.15;">
                        <i class="fas fa-book-medical"></i>
                    </div>
                </div>
            </div>

            <div class="category-tabs">
                <a href="{{ route('edukasi.index') }}" class="category-tab {{ !$selectedKategori ? 'active' : '' }}"><i
                        class="fas fa-layer-group"></i> Semua</a>
                @foreach($kategori as $k)
                    @php
                        $ikonKategori = match (strtolower($k)) {
                            'pencegahan' => 'fa-shield-virus',
                            'pengobatan' => 'fa-pills',
                            'mitos & fakta' => 'fa-circle-question',
                            default => 'fa-book-open',
                        };
                    @endphp
                    <a href="{{ route('edukasi.index', ['kategori' => $k]) }}"
                        class="category-tab {{ $selectedKategori == $k ? 'active' : '' }}"><i
                            class="fas {{ $ikonKategori }}"></i> {{ $k }}</a>
                @endforeach
            </div>
            <p style="color:var(--gray-400); margin-bottom:var(--space-lg);">Menampilkan {{ $materi->count() }} materi</p>

            @if($materi->count() > 0)
                <div class="grid grid-3">
                    @foreach($materi as $m)
                        <div class="card fade-in materi-card">
                            <div
                                style="height:180px; background:linear-gradient(135deg, var(--teal-600), var(--teal-300)); display:flex; align-items:center; justify-content:center;">
                                @if($m->thumbnail)
                                    <img src="{{ $m->thumbnail }}"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';"
                                        alt="{{ $m->judul }}" style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <i class="fas fa-book-open" style="font-size:3rem; color:#fff;"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <span class="badge badge-teal mb-sm">{{ $m->kategori }}</span>
                                <h4 class="card-title" style="font-weight:800;">{{ $m->judul }}</h4>
                                <p style="font-size:0.8rem; color:var(--gray-400); margin-bottom:8px;"><i class="far fa-clock"></i>
                                    {{ ceil(str_word_count(strip_tags($m->isi_materi)) / 200) }} menit baca</p>
                                <p class="card-text">{{ $m->ringkasan }}</p>
                                <div class="d-flex justify-between align-center" style="margin-top:var(--space-md);">
                                    <span style="font-size:0.75rem;color:var(--gray-400);"><i class="far fa-calendar"></i>
                                        {{ $m->tanggal_publish->format('d M Y') }}</span>
                                    <a href="{{ route('edukasi.show', $m->id_materi) }}" class="btn btn-sm btn-outline">Baca
                                        Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-between align-center mt-xl">
                    {{ $materi->withQueryString()->links('pagination.simple') }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>Belum ada materi</h3>
                    <p>Materi edukasi untuk kategori ini belum tersedia.</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .category-tab {
            padding: 12px 20px;
            border: 2px solid var(--teal-400);
            color: var(--teal-400);
            font-weight: 700;
        }

        .category-tab.active {
            background: var(--teal-400);
            color: #fff;
        }

        .materi-card {
            transition: transform .2s ease, box-shadow .2s ease;
            border: 1px solid var(--teal-100);
            overflow: hidden;
        }

        .materi-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        @media (max-width: 992px) {
            .grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush