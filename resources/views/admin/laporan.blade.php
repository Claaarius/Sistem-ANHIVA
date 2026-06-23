@extends('layouts.admin')
@section('title', 'Laporan - Admin ANHIVA')
@section('page-title', 'Kelola Laporan')

@section('content')
<div class="laporan-wrap">

    {{-- FORM FILTER --}}
    <div class="filter-card d-print-none">
        <div class="filter-title">
            <i class="fas fa-filter"></i>
            Pilih jenis & periode laporan
        </div>
        <form method="GET" action="{{ route('admin.laporan.preview') }}" id="form-laporan">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Jenis Laporan</label>
                    <select name="jenis" class="filter-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="skrining"  {{ (request('jenis') ?? ($jenis ?? '')) == 'skrining'  ? 'selected' : '' }}>Laporan Skrining</option>
                        <option value="konseling" {{ (request('jenis') ?? ($jenis ?? '')) == 'konseling' ? 'selected' : '' }}>Laporan Konseling</option>
                        <option value="pengguna"  {{ (request('jenis') ?? ($jenis ?? '')) == 'pengguna'  ? 'selected' : '' }}>Laporan Pengguna</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Bulan</label>
                    <select name="bulan" class="filter-select" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ (request('bulan') ?? ($bulan ?? '')) == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group filter-group-sm">
                    <label class="filter-label">Tahun</label>
                    <input type="number" name="tahun" class="filter-select" placeholder="YYYY"
                        value="{{ request('tahun') ?? ($tahun ?? date('Y')) }}"
                        required min="2000" max="2100">
                </div>
                <button type="submit" class="btn-filter-submit">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    {{-- PREVIEW LAPORAN --}}
    @if(isset($showPreview) && $showPreview)

    <div class="laporan-header-card mb-lg">
        <div class="laporan-header-inner">
            <div>
                <div class="laporan-header-badge">
                    <i class="fas fa-file-alt"></i>
                    {{ ucfirst($jenis) }}
                </div>
                <h2 class="laporan-header-title">Laporan {{ ucfirst($jenis) }} — {{ $label }}</h2>
                <p class="laporan-header-sub">Digenerate pada {{ now()->translatedFormat('d F Y, H:i') }}</p>
            </div>
            <a href="{{ route('admin.laporan.pdf', ['jenis' => $jenis, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="btn-pdf d-print-none" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- ═══ SKRINING ═══ --}}
    @if($jenis === 'skrining')
    <div class="stats-grid-4 mb-lg">
        <div class="stat-card-new teal">
            <div class="stat-icon-bg"><i class="fas fa-clipboard-check"></i></div>
            <div class="stat-num">{{ $total }}</div>
            <div class="stat-lbl">Total Skrining</div>
        </div>
        <div class="stat-card-new blue">
            <div class="stat-icon-bg"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-num">{{ number_format($rataRata, 1) }}</div>
            <div class="stat-lbl">Rata-rata Skor</div>
        </div>
        <div class="stat-card-new amber">
            <div class="stat-icon-bg"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-num">{{ $dominan }}</div>
            <div class="stat-lbl">Risiko Dominan</div>
        </div>
        <div class="stat-card-new purple">
            <div class="stat-icon-bg"><i class="fas fa-user-secret"></i></div>
            <div class="stat-num">{{ $anonim }}</div>
            <div class="stat-lbl">Pengguna Anonim</div>
        </div>
    </div>

    <div class="chart-grid mb-lg">
        <div class="card-new">
            <div class="card-new-header">Distribusi Tingkat Risiko</div>
            <canvas id="chartRisiko" height="220"></canvas>
        </div>
        <div class="card-new">
            <div class="card-new-header">Tren Skrining 6 Bulan Terakhir</div>
            <canvas id="chartTren" height="220"></canvas>
        </div>
    </div>

    <div class="card-new">
        <div class="card-new-header">Detail Skrining</div>
        <div style="overflow-x:auto;">
            <table class="table-new">
                <thead>
                    <tr>
                        <th>No</th><th>Kode Unik</th><th>Tanggal</th>
                        <th>Skor</th><th>Tingkat Risiko</th><th>Jenis Pengguna</th>
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
                            @if($s->tingkat_risiko === 'Rendah')
                                <span class="badge-new badge-rendah">Rendah</span>
                            @elseif($s->tingkat_risiko === 'Sedang')
                                <span class="badge-new badge-sedang">Sedang</span>
                            @else
                                <span class="badge-new badge-tinggi">Tinggi</span>
                            @endif
                        </td>
                        <td>{{ $s->id_pengguna ? 'Terdaftar' : 'Anonim' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-td">Tidak ada data skrining pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
    <div class="stats-grid-4 mb-lg">
        <div class="stat-card-new teal">
            <div class="stat-icon-bg"><i class="fas fa-comments"></i></div>
            <div class="stat-num">{{ $total }}</div>
            <div class="stat-lbl">Total Pengajuan</div>
        </div>
        <div class="stat-card-new green">
            <div class="stat-icon-bg"><i class="fas fa-check-circle"></i></div>
            <div class="stat-num">{{ $selesai }}</div>
            <div class="stat-lbl">Konseling Selesai</div>
        </div>
        <div class="stat-card-new blue">
            <div class="stat-icon-bg"><i class="fas fa-laptop"></i></div>
            <div class="stat-num">{{ $online }}</div>
            <div class="stat-lbl">Konseling Online</div>
        </div>
        <div class="stat-card-new amber">
            <div class="stat-icon-bg"><i class="fas fa-hospital"></i></div>
            <div class="stat-num">{{ $rujukan }}</div>
            <div class="stat-lbl">Total Rujukan</div>
        </div>
    </div>

    <div class="chart-grid mb-lg">
        <div class="card-new">
            <div class="card-new-header">Online vs Tatap Muka</div>
            <canvas id="chartJenis" height="220"></canvas>
        </div>
        <div class="card-new">
            <div class="card-new-header">Tren Konseling 6 Bulan Terakhir</div>
            <canvas id="chartTren" height="220"></canvas>
        </div>
    </div>

    <div class="card-new">
        <div class="card-new-header">Detail Konseling</div>
        <div style="overflow-x:auto;">
            <table class="table-new">
                <thead>
                    <tr>
                        <th>No</th><th>Kode Unik</th><th>Tanggal</th>
                        <th>Jenis</th><th>Status</th><th>Rujukan</th>
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
                            @if($k->status === 'Selesai')
                                <span class="badge-new badge-rendah">Selesai</span>
                            @elseif($k->status === 'Dijadwalkan')
                                <span class="badge-new badge-dijadwalkan">Dijadwalkan</span>
                            @else
                                <span class="badge-new badge-sedang">{{ $k->status }}</span>
                            @endif
                        </td>
                        <td>{{ $k->rujukan->count() > 0 ? '✅ Ada' : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-td">Tidak ada data konseling pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
    <div class="stats-grid-2 mb-lg">
        <div class="stat-card-new teal">
            <div class="stat-icon-bg"><i class="fas fa-users"></i></div>
            <div class="stat-num">{{ $total }}</div>
            <div class="stat-lbl">Total Pengguna Terdaftar</div>
        </div>
        <div class="stat-card-new blue">
            <div class="stat-icon-bg"><i class="fas fa-user-plus"></i></div>
            <div class="stat-num">{{ $baru }}</div>
            <div class="stat-lbl">Pengguna Baru Periode Ini</div>
        </div>
    </div>

    <div class="card-new mb-lg">
        <div class="card-new-header">Pertumbuhan Pengguna 6 Bulan Terakhir</div>
        <canvas id="chartTren" height="140"></canvas>
    </div>

    <div class="card-new">
        <div class="card-new-header">Pengguna Baru Periode Ini</div>
        <div style="overflow-x:auto;">
            <table class="table-new">
                <thead>
                    <tr><th>No</th><th>Kode Unik</th><th>Username</th><th>Tanggal Daftar</th></tr>
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
                    <tr><td colspan="4" class="empty-td">Tidak ada pengguna baru pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
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

    @else
    {{-- DASHBOARD REKAP --}}
    <div id="dashboard-laporan">
        <div class="dashboard-top d-print-none">
            <div class="section-title-new">
                <i class="fas fa-chart-pie"></i>
                Ringkasan data keseluruhan
            </div>
            <button onclick="window.print()" class="btn-print-new d-print-none">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>

        <div class="stats-grid-6 mb-lg">
            <div class="stat-card-new teal">
                <div class="stat-icon-bg"><i class="fas fa-users"></i></div>
                <div class="stat-num">{{ number_format($totalPengguna) }}</div>
                <div class="stat-lbl">Total Pengguna</div>
            </div>
            <div class="stat-card-new blue">
                <div class="stat-icon-bg"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-num">{{ number_format($totalSkrining) }}</div>
                <div class="stat-lbl">Data Skrining</div>
            </div>
            <div class="stat-card-new amber">
                <div class="stat-icon-bg"><i class="fas fa-comments"></i></div>
                <div class="stat-num">{{ number_format($totalKonseling) }}</div>
                <div class="stat-lbl">Data Konseling</div>
            </div>
            <div class="stat-card-new red">
                <div class="stat-icon-bg"><i class="fas fa-message"></i></div>
                <div class="stat-num">{{ number_format($totalKomentar) }}</div>
                <div class="stat-lbl">Total Komentar</div>
            </div>
            <div class="stat-card-new green">
                <div class="stat-icon-bg"><i class="fas fa-book-open"></i></div>
                <div class="stat-num">{{ number_format($totalEdukasi) }}</div>
                <div class="stat-lbl">Materi Edukasi</div>
            </div>
            <div class="stat-card-new purple">
                <div class="stat-icon-bg"><i class="fas fa-images"></i></div>
                <div class="stat-num">{{ number_format($totalGallery) }}</div>
                <div class="stat-lbl">Foto Gallery</div>
            </div>
        </div>

        <div class="tables-grid-3">
            <div class="table-card-new">
                <div class="table-card-header">
                    <i class="fas fa-stethoscope" style="color:#378ADD;"></i>
                    <span>Skrining terbaru</span>
                </div>
                <table class="table-new">
                    <thead><tr><th>Tanggal</th><th>Pengguna</th><th>Risiko</th></tr></thead>
                    <tbody>
                        @forelse($latestSkrining as $s)
                        <tr>
                            <td>{{ $s->tanggal_skrining->format('d/m/Y') }}</td>
                            <td>{{ $s->pengguna->nama_tampil ?? 'Anonim' }}</td>
                            <td>
                                @if($s->tingkat_risiko === 'Rendah')
                                    <span class="badge-new badge-rendah">Rendah</span>
                                @elseif($s->tingkat_risiko === 'Sedang')
                                    <span class="badge-new badge-sedang">Sedang</span>
                                @else
                                    <span class="badge-new badge-tinggi">Tinggi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="empty-td">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card-new">
                <div class="table-card-header">
                    <i class="fas fa-comments" style="color:#EF9F27;"></i>
                    <span>Konseling terbaru</span>
                </div>
                <table class="table-new">
                    <thead><tr><th>Tanggal</th><th>Pengguna</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($latestKonseling as $k)
                        <tr>
                            <td>{{ $k->tanggal_pengajuan->format('d/m/Y') }}</td>
                            <td>{{ $k->pengguna->nama_tampil ?? 'Anonim' }}</td>
                            <td>
                                @if($k->status === 'Selesai')
                                    <span class="badge-new badge-rendah">Selesai</span>
                                @elseif($k->status === 'Dijadwalkan')
                                    <span class="badge-new badge-dijadwalkan">Dijadwalkan</span>
                                @else
                                    <span class="badge-new badge-sedang">{{ $k->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="empty-td">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-card-new">
                <div class="table-card-header">
                    <i class="fas fa-comment-dots" style="color:#E24B4A;"></i>
                    <span>Komentar terbaru</span>
                </div>
                <table class="table-new">
                    <thead><tr><th>Tanggal</th><th>Komentar</th></tr></thead>
                    <tbody>
                        @forelse($latestKomentar as $kom)
                        <tr>
                            <td>{{ $kom->tanggal_komentar->format('d/m/Y') }}</td>
                            <td>
                                <div style="font-weight:600; font-size:0.8rem;">{{ $kom->nama_tampil }}</div>
                                <div style="color:var(--gray-500); font-size:0.75rem;">{{ Str::limit($kom->isi_komentar, 30) }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="empty-td">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
/* Filter */
.filter-card { background:white; border:1px solid #eee; border-radius:12px; padding:20px; margin-bottom:24px; }
.filter-title { font-size:13px; font-weight:600; color:#6b7280; display:flex; align-items:center; gap:6px; margin-bottom:14px; }
.filter-row { display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; }
.filter-group { display:flex; flex-direction:column; gap:4px; flex:1; min-width:140px; }
.filter-group-sm { max-width:100px; flex:0 0 100px; }
.filter-label { font-size:12px; color:#6b7280; font-weight:500; }
.filter-select { padding:8px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:13px; color:#374151; background:white; height:38px; }
.btn-filter-submit { background:#1D9E75; color:white; border:none; padding:0 18px; height:38px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; flex-shrink:0; white-space:nowrap; }

/* Laporan header */
.laporan-header-card { background:white; border-radius:12px; border-left:4px solid #1D9E75; border:1px solid #eee; border-left:4px solid #1D9E75; padding:20px 24px; margin-bottom:20px; }
.laporan-header-inner { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
.laporan-header-badge { display:inline-flex; align-items:center; gap:6px; background:#E1F5EE; color:#0F6E56; font-size:11px; font-weight:700; padding:3px 10px; border-radius:99px; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; }
.laporan-header-title { font-size:1.1rem; font-weight:700; color:#111827; margin:0; }
.laporan-header-sub { font-size:0.8rem; color:#9ca3af; margin:4px 0 0; }
.btn-pdf { background:#1D9E75; color:white; text-decoration:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:6px; }

/* Stat cards */
.stats-grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.stats-grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
.stats-grid-6 { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:12px; }
.stat-card-new { background:white; border:1px solid #eee; border-radius:12px; padding:16px 18px; position:relative; overflow:hidden; }
.stat-card-new::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:12px 12px 0 0; }
.stat-card-new.teal::before { background:#1D9E75; }
.stat-card-new.blue::before { background:#378ADD; }
.stat-card-new.amber::before { background:#EF9F27; }
.stat-card-new.red::before { background:#E24B4A; }
.stat-card-new.green::before { background:#639922; }
.stat-card-new.purple::before { background:#7F77DD; }
.stat-icon-bg { font-size:22px; opacity:0.1; position:absolute; bottom:10px; right:14px; }
.stat-card-new.teal .stat-icon-bg { color:#1D9E75; opacity:0.15; }
.stat-card-new.blue .stat-icon-bg { color:#378ADD; opacity:0.15; }
.stat-card-new.amber .stat-icon-bg { color:#EF9F27; opacity:0.15; }
.stat-card-new.red .stat-icon-bg { color:#E24B4A; opacity:0.15; }
.stat-card-new.green .stat-icon-bg { color:#639922; opacity:0.15; }
.stat-card-new.purple .stat-icon-bg { color:#7F77DD; opacity:0.15; }
.stat-num { font-size:26px; font-weight:700; margin:4px 0 2px; color:#111827; }
.stat-lbl { font-size:12px; color:#6b7280; }

/* Cards */
.card-new { background:white; border:1px solid #eee; border-radius:12px; overflow:hidden; margin-bottom:16px; }
.card-new-header { padding:14px 18px; font-size:13px; font-weight:600; color:#374151; border-bottom:1px solid #f3f4f6; background:#fafafa; }
.card-new > canvas { padding:16px; }
.chart-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* Tables */
.tables-grid-3 { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px; }
.table-card-new { background:white; border:1px solid #eee; border-radius:12px; overflow:hidden; }
.table-card-header { padding:12px 16px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px; background:#fafafa; }
.table-card-header span { font-size:13px; font-weight:600; color:#374151; }
.table-new { width:100%; border-collapse:collapse; font-size:12.5px; }
.table-new th { padding:9px 16px; text-align:left; color:#6b7280; font-weight:600; border-bottom:1px solid #f3f4f6; background:#fafafa; font-size:11px; text-transform:uppercase; letter-spacing:.4px; }
.table-new td { padding:10px 16px; border-bottom:1px solid #f9fafb; color:#374151; }
.table-new tr:last-child td { border-bottom:none; }
.table-new tr:hover td { background:#fafafa; }
.empty-td { text-align:center; color:#9ca3af; padding:20px !important; }

/* Badges */
.badge-new { font-size:11px; font-weight:600; padding:3px 9px; border-radius:99px; }
.badge-rendah { background:#EAF3DE; color:#3B6D11; }
.badge-sedang { background:#FAEEDA; color:#854F0B; }
.badge-tinggi { background:#FCEBEB; color:#A32D2D; }
.badge-dijadwalkan { background:#E6F1FB; color:#185FA5; }

/* Dashboard top */
.dashboard-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.section-title-new { font-size:15px; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px; }
.section-title-new i { color:#1D9E75; }
.btn-print-new { background:#f3f4f6; color:#374151; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; }
.btn-print-new:hover { background:#e5e7eb; }

/* mb-lg */
.mb-lg { margin-bottom:20px; }

/* Print */
@media print {
    .d-print-none, aside, header, nav, .sidebar, .navbar, .admin-topbar, .admin-sidebar { display:none !important; }
    .admin-main, .admin-content, .laporan-wrap { width:100% !important; margin:0 !important; padding:0 !important; }
    body { color:#000 !important; background:#fff !important; }
    .card-new, .stat-card-new, .table-card-new { border:1px solid #ccc !important; break-inside:avoid; }
    .stats-grid-6 { grid-template-columns: repeat(3,1fr) !important; }
    .tables-grid-3 { grid-template-columns: 1fr 1fr 1fr !important; }
}

@media (max-width: 768px) {
    .stats-grid-4 { grid-template-columns: repeat(2,1fr); }
    .chart-grid { grid-template-columns: 1fr; }
    .filter-group-sm { max-width:100%; flex:1; }
}
</style>
@endpush

@endsection