@extends('layouts.app')
@section('title', 'Pengajuan Konseling - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <a href="{{ route('konseling.index') }}" class="btn btn-sm btn-secondary mb-lg"><i class="fas fa-arrow-left"></i> Kembali</a>

        <div class="text-center mb-xl">
            <h2 style="font-size:2rem; font-weight:800; color:var(--gray-900);">Pengajuan Konseling</h2>
            <p style="color:var(--gray-400);">Kami di sini untuk mendengarkan dan membantu Anda</p>
        </div>

        <!-- Langkah-langkah -->
        <div class="konseling-steps-box mb-xl">
            <div class="grid grid-3 konseling-steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h5>Isi Formulir</h5>
                <p style="font-size:0.85rem; color:var(--gray-400);">Lengkapi data pengajuan dan topik spesifik yang ingin dibahas.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h5>Tunggu Jadwal</h5>
                <p style="font-size:0.85rem; color:var(--gray-400);">Konselor akan meninjau dan menentukan jadwal untuk Anda.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h5>Konfirmasi</h5>
                <p style="font-size:0.85rem; color:var(--gray-400);">Konfirmasi kehadiran Anda atau minta reschedule jika berhalangan.</p>
            </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body" style="padding:var(--space-2xl);">
                <form method="POST" action="{{ route('konseling.store') }}">
                    @csrf
                    
                    <h4 class="mb-md"><i class="fas fa-handshake text-teal"></i> Pilih Metode Konseling</h4>
                    
                    <div class="grid grid-2 mb-lg">
                        <label class="konseling-type-card">
                            <input type="radio" name="jenis" value="Online" required style="display:none;" onchange="updateKonselingType()" {{ old('jenis') === 'Online' ? 'checked' : '' }}>
                            <div class="konseling-type-icon"><i class="fas fa-video"></i></div>
                            <div class="konseling-check"><i class="fas fa-check"></i></div>
                            <h5 style="margin-bottom:var(--space-xs);">Online (Video/Chat)</h5>
                            <p style="font-size:0.8rem; color:var(--gray-400); margin:0;">Konsultasi jarak jauh yang sangat menjaga privasi Anda.</p>
                        </label>
                        <label class="konseling-type-card">
                            <input type="radio" name="jenis" value="Tatap Muka" required style="display:none;" onchange="updateKonselingType()" {{ old('jenis') === 'Tatap Muka' ? 'checked' : '' }}>
                            <div class="konseling-type-icon"><i class="fas fa-user-md"></i></div>
                            <div class="konseling-check"><i class="fas fa-check"></i></div>
                            <h5 style="margin-bottom:var(--space-xs);">Tatap Muka</h5>
                            <p style="font-size:0.8rem; color:var(--gray-400); margin:0;">Bertemu langsung dengan konselor di fasilitas kesehatan terdekat.</p>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="alasan">Topik yang Ingin Dibahas *</label>
                        <textarea name="alasan" id="alasan" class="form-control" rows="5" placeholder="Ceritakan secara singkat apa yang sedang Anda rasakan atau butuhkan..." required>{{ old('alasan') }}</textarea>
                        <small class="form-hint">Semua informasi Anda dijamin kerahasiaannya.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nomor_kontak">Nomor yang Dapat Dihubungi (Opsional)</label>
                        <input type="tel" id="nomor_kontak" name="nomor_kontak" class="form-control" placeholder="08xxxxxxxx" value="{{ old('nomor_kontak') }}" pattern="[0-9]*" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </div>
                    
                    @if(!auth()->check())
                    <div class="form-group" style="background:var(--amber-50); padding:var(--space-md); border-radius:var(--radius-md); border-left:4px solid var(--amber-400);">
                        <p style="font-size:0.9rem; color:var(--amber-800); margin:0;">
                            <strong>Perhatian:</strong> Anda mengajukan konseling secara anonim. Harap simpan <strong>Kode Unik</strong> yang akan diberikan setelah ini untuk mengecek status dan jadwal Anda nanti.
                        </p>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fas fa-paper-plane"></i> Kirim Pengajuan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function updateKonselingType() {
    const cards = document.querySelectorAll('.konseling-type-card');
    cards.forEach(card => {
        const input = card.querySelector('input');
        if(input.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}
// Init selection
updateKonselingType();

@if(session('pengajuan_sukses'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Pengajuan konseling berhasil dikirim!',
    confirmButtonText: 'OK'
}).then(() => {
    window.location.href = '{{ url('/konseling/riwayat') }}';
});
@endif
</script>
<style>
.konseling-type-card {
    border: 2px solid var(--gray-100);
    border-radius: var(--radius-md);
    padding: var(--space-lg);
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.konseling-type-card .konseling-check {
    display: none;
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px;
    height: 28px;
    border-radius: 999px;
    background: var(--teal-400);
    color: #fff;
    align-items: center;
    justify-content: center;
}

.konseling-type-card.selected {
    border: 3px solid var(--teal-400);
    background: var(--teal-50);
    box-shadow: var(--shadow-sm);
}

.konseling-type-card.selected .konseling-check {
    display: flex;
}

.konseling-steps-box {
    background: #fff;
    border: 1px solid var(--teal-100);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: var(--space-xl);
}

.konseling-steps-grid .step-card {
    position: relative;
}

.konseling-steps-grid .step-card h5 {
    font-size: 1.15rem;
    margin-bottom: var(--space-xs);
}

.konseling-steps-grid .step-card .step-number {
    width: 40px;
    height: 40px;
    border-radius: 999px;
    background: var(--teal-400);
    color: #fff;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-sm);
    box-shadow: var(--shadow-sm);
}

.konseling-steps-grid .step-card:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 20px;
    right: -20px;
    width: 40px;
    border-top: 2px dashed var(--teal-200);
}

@media (max-width: 992px) {
    .konseling-steps-grid .step-card:not(:last-child)::after {
        display: none;
    }
}
</style>
@endpush
