@extends('layouts.admin')
@section('title', 'Laporan - Admin ANHIVA')
@section('page-title', 'Kelola Laporan')

@section('content')
<div class="laporan-wrap">

    {{-- FORM FILTER --}}
    <div class="card mb-xl">
        <div class="card-body">
            <h3 style="font-size:1rem; font-weight:700; color:var(--teal-800); margin-bottom:var(--space-lg);">
                <i class="fas fa-filter"></i> Pilih Jenis & Periode Laporan
            </h3>
            <form method="GET" action="{{ route('admin.laporan.preview') }}" id="form-laporan">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:var(--space-md); align-items:end;">

                    {{-- Jenis Laporan --}}
                    <div class="form-group mb-0">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="jenis" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="skrining"  {{ (request('jenis') ?? ($jenis ?? '')) == 'skrining'  ? 'selected' : '' }}>Laporan Skrining</option>
                            <option value="konseling" {{ (request('jenis') ?? ($jenis ?? '')) == 'konseling' ? 'selected' : '' }}>Laporan Konseling</option>
                            <option value="pengguna"  {{ (request('jenis') ?? ($jenis ?? '')) == 'pengguna'  ? 'selected' : '' }}>Laporan Pengguna</option>
                        </select>
                    </div>

                    {{-- Bulan --}}
                    <div class="form-group mb-0">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-control" required>
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                            <option value="{{ $i+1 }}" {{ (request('bulan') ?? ($bulan ?? '')) == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun --}}
                    <div class="form-group mb-0">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-control" required>
                            <option value="">-- Pilih Tahun --</option>
                            @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ (request('tahun') ?? ($tahun ?? '')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- PREVIEW LAPORAN --}}
    @if(isset($showPreview) && $showPreview)

    {{-- Header Laporan --}}
    <div class="card mb-lg" id="laporan-preview">
        <div class="card-body" style="border-left:4px solid var(--teal-400);">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:var(--space-md);">
                <div>
                    <h2 style="font-size:1.2rem; font-weight:700; color:var(--teal-800); margin:0;">
                        Laporan {{ ucfirst($jenis) }} — {{ $label }}
                    </h2>
                    <p style="color:var(--gray-400); font-size:0.82rem; margin:4px 0 0;">
                        Digenerate pada {{ now()->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
                <a href="{{ route('admin.laporan.pdf', ['jenis' => $jenis, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                   class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ═══ SKRINING ═══ --}}
    @if($jenis === 'skrining')

    {{-- Kartu Ringkasan --}}
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:var(--space-md); margin-bottom:var(--space-lg);">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $total }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Total Skrining</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ number_format($rataRata, 1) }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Rata-rata Skor</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $dominan }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Risiko Dominan</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $anonim }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Pengguna Anonim</div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-lg); margin-bottom:var(--space-lg);">
        <div class="card">
            <div class="card-body">
                <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Distribusi Tingkat Risiko</h4>
                <canvas id="chartRisiko" height="220"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Tren Skrining 6 Bulan Terakhir</h4>
                <canvas id="chartTren" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="card">
        <div class="card-body">
            <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Detail Skrining</h4>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Unik</th>
                            <th>Tanggal</th>
                            <th>Skor</th>
                            <th>Tingkat Risiko</th>
                            <th>Jenis Pengguna</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detail as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="font-family:monospace; color:var(--teal-600);">{{ $s->kode_unik }}</td>
                            <td>{{ $s->tanggal_skrining->format('d M Y') }}</td>
                            <td>{{ $s->skor_total }}</td>
                            <td>
                                <span class="badge" style="background:{{ $s->tingkat_risiko === 'Rendah' ? '#EAF3DE' : ($s->tingkat_risiko === 'Sedang' ? '#FAEEDA' : '#FCEBEB') }}; color:{{ $s->tingkat_risiko === 'Rendah' ? '#3B6D11' : ($s->tingkat_risiko === 'Sedang' ? '#854F0B' : '#A32D2D') }}; padding:3px 10px; border-radius:999px; font-size:0.75rem; font-weight:600;">
                                    {{ $s->tingkat_risiko }}
                                </span>
                            </td>
                            <td>{{ $s->id_pengguna ? 'Terdaftar' : 'Anonim' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center" style="color:var(--gray-400);">Tidak ada data skrining pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    new Chart(document.getElementById('chartRisiko'), {
        type: 'pie',
        data: {
            labels: ['Rendah', 'Sedang', 'Tinggi'],
            datasets: [{ data: [{{ $rendah }}, {{ $sedang }}, {{ $tinggi }}], backgroundColor: ['#EAF3DE','#FAEEDA','#FCEBEB'], borderColor: ['#3B6D11','#854F0B','#A32D2D'], borderWidth: 2 }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{ label: 'Jumlah Skrining', data: {!! json_encode($trenData) !!}, borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,0.1)', tension: 0.4, fill: true }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    </script>
    @endpush

    {{-- ═══ KONSELING ═══ --}}
    @elseif($jenis === 'konseling')

    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:var(--space-md); margin-bottom:var(--space-lg);">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $total }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Total Pengajuan</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $selesai }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Konseling Selesai</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $online }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Konseling Online</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $rujukan }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Total Rujukan</div>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-lg); margin-bottom:var(--space-lg);">
        <div class="card">
            <div class="card-body">
                <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Online vs Tatap Muka</h4>
                <canvas id="chartJenis" height="220"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Tren Konseling 6 Bulan Terakhir</h4>
                <canvas id="chartTren" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Detail Konseling</h4>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Unik</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Rujukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detail as $i => $k)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="font-family:monospace; color:var(--teal-600);">{{ $k->kode_unik }}</td>
                            <td>{{ $k->tanggal_pengajuan->format('d M Y') }}</td>
                            <td>{{ $k->jenis }}</td>
                            <td>
                                <span class="badge" style="background:{{ $k->status === 'Selesai' ? '#EAF3DE' : ($k->status === 'Dijadwalkan' ? '#E6F1FB' : '#FAEEDA') }}; color:{{ $k->status === 'Selesai' ? '#3B6D11' : ($k->status === 'Dijadwalkan' ? '#1D4ED8' : '#854F0B') }}; padding:3px 10px; border-radius:999px; font-size:0.75rem; font-weight:600;">
                                    {{ $k->status }}
                                </span>
                            </td>
                            <td>{{ $k->rujukan->count() > 0 ? '✅ Ada' : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center" style="color:var(--gray-400);">Tidak ada data konseling pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    new Chart(document.getElementById('chartJenis'), {
        type: 'bar',
        data: {
            labels: ['Online', 'Tatap Muka'],
            datasets: [{ label: 'Jumlah', data: [{{ $online }}, {{ $tatapMuka }}], backgroundColor: ['rgba(29,158,117,0.7)', 'rgba(239,159,39,0.7)'], borderColor: ['#1D9E75', '#EF9F27'], borderWidth: 2, borderRadius: 6 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{ label: 'Jumlah Konseling', data: {!! json_encode($trenData) !!}, borderColor: '#EF9F27', backgroundColor: 'rgba(239,159,39,0.1)', tension: 0.4, fill: true }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    </script>
    @endpush

    {{-- ═══ PENGGUNA ═══ --}}
    @elseif($jenis === 'pengguna')

    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:var(--space-md); margin-bottom:var(--space-lg);">
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $total }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Total Pengguna Terdaftar</div>
            </div>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <div style="font-size:2rem; font-weight:700; color:var(--teal-600);">{{ $baru }}</div>
                <div style="font-size:0.8rem; color:var(--gray-400);">Pengguna Baru Periode Ini</div>
            </div>
        </div>
    </div>

    <div class="card mb-lg">
        <div class="card-body">
            <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Pertumbuhan Pengguna 6 Bulan Terakhir</h4>
            <canvas id="chartTren" height="140"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 style="font-size:0.9rem; font-weight:600; margin-bottom:var(--space-md);">Pengguna Baru Periode Ini</h4>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Unik</th>
                            <th>Username</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detail as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td style="font-family:monospace; color:var(--teal-600);">{{ $p->kode_unik }}</td>
                            <td>{{ $p->nama_tampil ?? $p->username }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center" style="color:var(--gray-400);">Tidak ada pengguna baru pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{ label: 'Pengguna Baru', data: {!! json_encode($trenData) !!}, borderColor: '#1D9E75', backgroundColor: 'rgba(29,158,117,0.1)', tension: 0.4, fill: true }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    </script>
    @endpush

    @endif
    @endif

</div>
@endsection