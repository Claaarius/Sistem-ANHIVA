@extends('layouts.app')
@section('title', $materi->judul . ' - ANHIVA')

@section('content')
<div id="readingProgressBar" style="position:fixed; top:0; left:0; height:4px; width:0; background:var(--teal-400); z-index:9999;"></div>
<section class="section">
    <div class="container">
        <a href="{{ route('edukasi.index') }}" class="btn btn-sm btn-secondary mb-lg"><i class="fas fa-arrow-left"></i> Kembali ke Edukasi</a>

        <div class="grid" style="grid-template-columns:68% 30%; gap:2%;">
            <div class="card fade-in">
                <div style="height:300px; background:linear-gradient(135deg, var(--teal-700), var(--teal-400)); display:flex; align-items:center; justify-content:center;">
                    @if($materi->thumbnail)
                         <img src="{{ $materi->thumbnail }}"
                            alt="{{ $materi->judul }}"
                            style="width:100%; height:100%; object-fit:cover;"
                            onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-book-open\' style=\'font-size:4rem;color:#fff;\'></i>';">
                        @else
                            <i class="fas fa-book-open" style="font-size:4rem; color:#fff;"></i>
                        @endif
                </div>
                <div class="card-body" style="padding: var(--space-2xl);">
                    <h1 style="font-size:1.8rem; margin-bottom:var(--space-sm);">{{ $materi->judul }}</h1>
                    <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:var(--space-xl); font-size:0.85rem; color:var(--gray-400);">
                        <span class="badge badge-teal">{{ $materi->kategori }}</span>
                        <span><i class="far fa-calendar"></i> {{ $materi->tanggal_publish->format('d M Y') }}</span>
                        <span><i class="fas fa-user"></i> Admin</span>
                        <span><i class="far fa-clock"></i> {{ ceil(str_word_count(strip_tags($materi->isi_materi)) / 200) }} menit baca</span>
                    </div>
                    <div class="materi-content">
                        {!! $materi->isi_materi !!}
                    </div>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:var(--space-lg);">
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom:var(--space-md);">Materi Terkait</h4>
                        @forelse($materiTerkait as $m)
                            <div style="padding:10px 0; border-bottom:1px solid var(--gray-100);">
                                <a href="{{ route('edukasi.show', $m->id_materi) }}" style="font-weight:700;">{{ $m->judul }}</a>
                                <p style="font-size:0.85rem; color:var(--gray-400); margin:4px 0 0;">{{ Str::limit(strip_tags($m->isi_materi), 80) }}</p>
                            </div>
                        @empty
                            <p style="font-size:0.9rem; color:var(--gray-400); margin:0;">Belum ada materi terkait.</p>
                        @endforelse
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 style="margin-bottom:var(--space-md);">Bagikan</h4>
                        <button type="button" class="btn btn-outline btn-block" onclick="copyMateriLink()"><i class="fas fa-link"></i> Copy Link</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
window.addEventListener('scroll', function () {
    const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
    document.getElementById('readingProgressBar').style.width = scrolled + '%';
});

function copyMateriLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Link materi berhasil disalin.' });
    });
}
</script>
@endpush

@push('styles')
<style>
.materi-content { line-height:1.9; font-size:1rem; }
.materi-content h1, .materi-content h2, .materi-content h3, .materi-content h4 { border-bottom:2px solid var(--teal-50); padding-bottom:8px; margin-top:24px; }
.materi-content p { margin-bottom:16px; }
@media (max-width: 992px) { .grid[style*="68%"] { grid-template-columns: 1fr !important; gap: var(--space-lg) !important; } }
</style>
@endpush
