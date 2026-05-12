@extends('layouts.app')
@section('title', 'Skrining - Pertanyaan ' . ($current + 1) . ' - ANHIVA')

@section('content')
<!-- Progress Bar -->
<div class="reading-progress" style="width: {{ (($current) / $pertanyaan->count()) * 100 }}%;"></div>

<section class="section" style="background-color:var(--gray-50); background-image:url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22%3E%3Ccircle cx=%222%22 cy=%222%22 r=%221%22 fill=%22%231d9e75%22 fill-opacity=%220.04%22/%3E%3C/svg%3E'); min-height:80vh; display:flex; align-items:center; position:relative; overflow:hidden;">
    <div style="position:absolute; right:2%; top:18%; font-size:14rem; color:rgba(29, 158, 117, 0.05); pointer-events:none; z-index:0;">
        <i class="fas fa-stethoscope"></i>
    </div>
    <div class="container container-sm">
        @php $currentPertanyaan = $pertanyaan[$current]; @endphp

        <div class="card" style="border:none; box-shadow:var(--shadow-lg);">
            <div class="card-body" style="padding:var(--space-2xl);">
                <div style="font-size:0.85rem; font-weight:700; color:var(--teal-400); text-transform:uppercase; margin-bottom:var(--space-sm); display:flex; justify-content:space-between;">
                    <span><i class="fas fa-tag"></i> {{ $currentPertanyaan->kategori }}</span>
                    <span>{{ $current + 1 }} / {{ $pertanyaan->count() }}</span>
                </div>

                <h3 style="font-size:1.5rem; color:var(--gray-900); margin-bottom:var(--space-xl); line-height:1.4;">
                    {{ $currentPertanyaan->isi_pertanyaan }}
                </h3>

                @if(session('error'))
                <div style="background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:var(--radius-md); margin-bottom:var(--space-lg); font-size:0.9rem;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('skrining.jawaban') }}" id="skriningForm" onsubmit="return validasiSkrining()">
                    @csrf
                    <input type="hidden" name="current" value="{{ $current }}">
                    <input type="hidden" name="id_pertanyaan" value="{{ $currentPertanyaan->id_pertanyaan }}">
                    <input type="hidden" name="direction" value="next" id="directionInput">
                    <input type="hidden" name="pilihan_teks" value="" id="pilihanTeksInput">

                    @php
                        $existingJawaban = session('skrining_jawaban', []);
                        $selectedSkor = isset($existingJawaban[$current]) ? $existingJawaban[$current]['skor_kontribusi'] : null;
                    @endphp

                    <div style="display:flex; flex-direction:column; gap:var(--space-md); margin-bottom:var(--space-xl);">
                        @foreach($currentPertanyaan->pilihan_jawaban as $pilihan)
                        <label class="answer-card {{ $selectedSkor !== null && $selectedSkor == $pilihan['bobot'] ? 'selected' : '' }}">
                            <input type="radio" name="pilihan" value="{{ $pilihan['bobot'] }}"
                                   data-teks="{{ $pilihan['teks'] }}"
                                   {{ $selectedSkor !== null && $selectedSkor == $pilihan['bobot'] ? 'checked' : '' }}
                                   onchange="this.closest('.card-body').querySelectorAll('.answer-card').forEach(c=>c.classList.remove('selected')); this.closest('.answer-card').classList.add('selected'); document.getElementById('pilihanTeksInput').value = this.dataset.teks; document.getElementById('skriningError').style.display='none';" style="display:none;">
                            <div style="width:24px; height:24px; border:2px solid var(--teal-400); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;" class="radio-indicator">
                                <div style="width:12px; height:12px; border-radius:50%; background:var(--teal-400); transform:scale({{ $selectedSkor !== null && $selectedSkor == $pilihan['bobot'] ? '1' : '0' }}); transition:transform 0.2s;" class="radio-inner"></div>
                            </div>
                            <span style="font-size:1.05rem; width:100%;">{{ $pilihan['teks'] }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div id="skriningError" style="display:none; color:#dc2626; font-size:0.9rem; margin-bottom:var(--space-md); text-align:center;">
                        <i class="fas fa-exclamation-circle"></i> Silakan pilih salah satu jawaban terlebih dahulu.
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--gray-100); padding-top:var(--space-lg);">
                        @if($current > 0)
                        <button type="submit" class="btn btn-secondary" onclick="document.getElementById('directionInput').value='prev'">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        @else
                        <div></div>
                        @endif

                        @if($current < $pertanyaan->count() - 1)
                        <button type="submit" class="btn btn-primary btn-lg" onclick="document.getElementById('directionInput').value='next'">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                        @else
                        <button type="submit" class="btn btn-amber btn-lg" onclick="document.getElementById('directionInput').value='next'">
                            <i class="fas fa-check-circle"></i> Selesai Skrining
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function validasiSkrining() {
    const direction = document.getElementById('directionInput').value;
    if (direction === 'prev') {
        return true;
    }
    const selected = document.querySelector('input[name="pilihan"]:checked');
    if (!selected) {
        document.getElementById('skriningError').style.display = 'block';
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelector('input[name="pilihan"]:checked');
    if (checked) {
        document.getElementById('pilihanTeksInput').value = checked.dataset.teks;
    }

    // Add interactivity to custom radio indicator
    const answerCards = document.querySelectorAll('.answer-card');
    answerCards.forEach(card => {
        const input = card.querySelector('input[type="radio"]');
        input.addEventListener('change', function() {
            // Reset all
            document.querySelectorAll('.radio-inner').forEach(inner => inner.style.transform = 'scale(0)');
            // Set current
            if(this.checked) {
                card.querySelector('.radio-inner').style.transform = 'scale(1)';
            }
        });
    });
});
</script>
<style>
    .answer-card {
        padding:var(--space-lg);
        border:2px solid var(--gray-100);
        border-radius:var(--radius-md);
        display:flex;
        align-items:center;
        gap:var(--space-md);
        cursor:pointer;
        transition:all 0.2s ease;
        background:white;
    }
    .answer-card:hover {
        border-color:var(--teal-200);
        background:var(--teal-50);
    }
    .answer-card.selected {
        border-color:var(--teal-400);
        background:var(--teal-50);
        font-weight:600;
        box-shadow:var(--shadow-sm);
    }
</style>
@endpush
