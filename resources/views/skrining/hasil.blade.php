@extends('layouts.app')
@section('title', 'Hasil Skrining - ANHIVA')

@section('content')
<section class="section">
    <div class="container container-sm">
        <div class="section-header">
            <h2>Hasil Skrining</h2>
            <p>Berikut adalah hasil skrining risiko HIV Anda</p>
            <div class="section-line"></div>
        </div>

        <div class="result-card fade-in" id="hasilSkrining">
            <div class="result-header risiko-{{ strtolower($skrining->tingkat_risiko) }}" style="text-align:center; padding:var(--space-2xl) 0; border-bottom:1px solid var(--gray-100); background:var(--gray-50);">
                <div class="result-gauge {{ $skrining->tingkat_risiko }} {{ $skrining->tingkat_risiko == 'Tinggi' ? 'pulse-high' : '' }}">
                    {{ $skrining->skor_total }}
                </div>
                <div class="result-risk-level" style="font-size:1.5rem; font-weight:800; color:var(--gray-900);">Risiko {{ $skrining->tingkat_risiko }}</div>
                <p style="font-size:0.9rem; color:var(--gray-400); margin-top:var(--space-sm);">Berdasarkan analisis sistem terhadap jawaban Anda</p>
            </div>
            <div class="result-body">
                <div class="result-detail">
                    <span class="label">Kode Unik</span>
                    <span class="value" style="font-family:monospace; color:var(--teal-400);">{{ $skrining->kode_unik }}</span>
                </div>
                <div class="result-detail">
                    <span class="label">Tanggal Skrining</span>
                    <span class="value">{{ $skrining->tanggal_skrining->format('d M Y') }}</span>
                </div>
                <div class="result-detail">
                    <span class="label">Skor Total</span>
                    <span class="value">{{ $skrining->skor_total }}</span>
                </div>
                <div class="result-detail">
                    <span class="label">Tingkat Risiko</span>
                    <span class="badge badge-risiko-{{ strtolower($skrining->tingkat_risiko) }}">{{ $skrining->tingkat_risiko }}</span>
                </div>

                <div class="disclaimer-box" style="margin-top:var(--space-xl);">
                    <div class="disclaimer-title"><i class="fas fa-hand-holding-medical"></i> Rekomendasi</div>
                    <p>{{ $rekomendasi }}</p>
                </div>

                <div class="disclaimer-box" style="background:var(--risk-high-bg); border-color:#F5C5C5;">
                    <div class="disclaimer-title" style="color:var(--risk-high-text);"><i class="fas fa-exclamation-triangle"></i> Disclaimer</div>
                    <p style="color:var(--risk-high-text);">Hasil skrining ini bukan merupakan diagnosis medis. Silakan konsultasikan dengan tenaga kesehatan profesional untuk pemeriksaan lebih lanjut.</p>
                </div>

                <div class="result-actions">
                    <button onclick="downloadPDF()" class="btn btn-primary">
                        <i class="fas fa-download"></i> Unduh PDF
                    </button>
                    <a href="{{ route('konseling.pengajuan') }}" class="btn btn-amber">
                        <i class="fas fa-comments"></i> Ajukan Konseling
                    </a>
                    <a href="{{ route('skrining.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Skrining Ulang
                    </a>
                </div>

                @guest
                <div class="card mt-lg" style="background:var(--amber-50); border-color:var(--amber-100);">
                    <div class="card-body">
                        <p style="font-size:0.85rem; color:var(--amber-800); margin-bottom:0;">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Simpan kode unik Anda: <span style="font-family:monospace;">{{ $skrining->kode_unik }}</span></strong><br>
                            Kode ini diperlukan untuk mengakses riwayat skrining Anda di kemudian hari.
                        </p>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(20);
    doc.setTextColor(29, 158, 117);
    doc.text('ANHIVA - Hasil Skrining', 20, 30);

    doc.setFontSize(10);
    doc.setTextColor(136, 135, 128);
    doc.text('Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim', 20, 38);

    doc.setDrawColor(211, 209, 199);
    doc.line(20, 42, 190, 42);

    doc.setFontSize(12);
    doc.setTextColor(44, 44, 42);

    let y = 55;
    const data = [
        ['Kode Unik', '{{ $skrining->kode_unik }}'],
        ['Tanggal Skrining', '{{ $skrining->tanggal_skrining->format("d M Y") }}'],
        ['Skor Total', '{{ $skrining->skor_total }}'],
        ['Tingkat Risiko', '{{ $skrining->tingkat_risiko }}'],
    ];

    data.forEach(([label, value]) => {
        doc.setTextColor(136, 135, 128);
        doc.text(label, 20, y);
        doc.setTextColor(44, 44, 42);
        doc.setFont(undefined, 'bold');
        doc.text(value, 90, y);
        doc.setFont(undefined, 'normal');
        y += 10;
    });

    y += 10;
    doc.setFontSize(11);
    doc.setTextColor(133, 79, 11);
    doc.text('Rekomendasi:', 20, y);
    y += 7;
    doc.setTextColor(44, 44, 42);
    doc.setFontSize(10);
    const lines = doc.splitTextToSize(@json($rekomendasi), 170);
    doc.text(lines, 20, y);

    y += lines.length * 6 + 15;
    doc.setFontSize(8);
    doc.setTextColor(163, 45, 45);
    doc.text('Disclaimer: Hasil skrining ini bukan merupakan diagnosis medis.', 20, y);
    doc.text('Silakan konsultasikan dengan tenaga kesehatan profesional.', 20, y + 5);

    doc.save('Hasil_Skrining_{{ $skrining->kode_unik }}.pdf');
}
</script>
@endpush
