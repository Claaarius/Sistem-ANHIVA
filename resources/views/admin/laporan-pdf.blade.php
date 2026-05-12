<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: sans-serif; font-size: 12px; color: #1e293b; margin: 0; padding: 20px; }
  .header { text-align: center; border-bottom: 2px solid #1D9E75; padding-bottom: 12px; margin-bottom: 20px; }
  .header h1 { font-size: 18px; color: #085041; margin: 0 0 4px; }
  .header p  { font-size: 11px; color: #888; margin: 0; }
  .summary-grid { display: flex; gap: 12px; margin-bottom: 20px; }
  .summary-card { flex: 1; border: 1px solid #e2e0d8; border-radius: 6px; padding: 12px; text-align: center; }
  .summary-card .num  { font-size: 22px; font-weight: 700; color: #1D9E75; }
  .summary-card .lbl  { font-size: 10px; color: #888; margin-top: 2px; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th { background: #1D9E75; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
  td { padding: 7px 10px; border-bottom: 1px solid #f0f0f0; font-size: 11px; }
  tr:nth-child(even) td { background: #f8fafc; }
  .badge { padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 600; }
  .badge-rendah  { background: #EAF3DE; color: #3B6D11; }
  .badge-sedang  { background: #FAEEDA; color: #854F0B; }
  .badge-tinggi  { background: #FCEBEB; color: #A32D2D; }
  .badge-selesai { background: #EAF3DE; color: #3B6D11; }
  .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #e2e0d8; padding-top: 10px; }
</style>
</head>
<body>

<div class="header">
  <h1>ANHIVA &mdash; Laporan {{ ucfirst($jenis) }}</h1>
  <p>Periode: {{ $label }} &nbsp;|&nbsp; Digenerate: {{ $generatedAt }}</p>
</div>

@if($jenis === 'skrining')
<div class="summary-grid">
  <div class="summary-card"><div class="num">{{ $total }}</div><div class="lbl">Total Skrining</div></div>
  <div class="summary-card"><div class="num">{{ number_format($rataRata,1) }}</div><div class="lbl">Rata-rata Skor</div></div>
  <div class="summary-card"><div class="num">{{ $rendah }}</div><div class="lbl">Risiko Rendah</div></div>
  <div class="summary-card"><div class="num">{{ $sedang }}</div><div class="lbl">Risiko Sedang</div></div>
  <div class="summary-card"><div class="num">{{ $tinggi }}</div><div class="lbl">Risiko Tinggi</div></div>
  <div class="summary-card"><div class="num">{{ $anonim }}</div><div class="lbl">Pengguna Anonim</div></div>
</div>
<table>
  <thead><tr><th>No</th><th>Kode Unik</th><th>Tanggal</th><th>Skor</th><th>Tingkat Risiko</th><th>Jenis Pengguna</th></tr></thead>
  <tbody>
    @forelse($detail as $i => $s)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $s->kode_unik }}</td>
      <td>{{ $s->tanggal_skrining->format('d M Y') }}</td>
      <td>{{ $s->skor_total }}</td>
      <td><span class="badge badge-{{ strtolower($s->tingkat_risiko) }}">{{ $s->tingkat_risiko }}</span></td>
      <td>{{ $s->id_pengguna ? 'Terdaftar' : 'Anonim' }}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:#aaa;">Tidak ada data.</td></tr>
    @endforelse
  </tbody>
</table>

@elseif($jenis === 'konseling')
<div class="summary-grid">
  <div class="summary-card"><div class="num">{{ $total }}</div><div class="lbl">Total Pengajuan</div></div>
  <div class="summary-card"><div class="num">{{ $selesai }}</div><div class="lbl">Konseling Selesai</div></div>
  <div class="summary-card"><div class="num">{{ $online }}</div><div class="lbl">Online</div></div>
  <div class="summary-card"><div class="num">{{ $tatapMuka }}</div><div class="lbl">Tatap Muka</div></div>
  <div class="summary-card"><div class="num">{{ $rujukan }}</div><div class="lbl">Total Rujukan</div></div>
</div>
<table>
  <thead><tr><th>No</th><th>Kode Unik</th><th>Tanggal</th><th>Jenis</th><th>Status</th><th>Rujukan</th></tr></thead>
  <tbody>
    @forelse($detail as $i => $k)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $k->kode_unik }}</td>
      <td>{{ $k->tanggal_pengajuan->format('d M Y') }}</td>
      <td>{{ $k->jenis }}</td>
      <td><span class="badge badge-{{ $k->status === 'Selesai' ? 'selesai' : 'sedang' }}">{{ $k->status }}</span></td>
      <td>{{ $k->rujukan->count() > 0 ? 'Ada' : '—' }}</td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:#aaa;">Tidak ada data.</td></tr>
    @endforelse
  </tbody>
</table>

@elseif($jenis === 'pengguna')
<div class="summary-grid">
  <div class="summary-card"><div class="num">{{ $total }}</div><div class="lbl">Total Pengguna</div></div>
  <div class="summary-card"><div class="num">{{ $baru }}</div><div class="lbl">Pengguna Baru</div></div>
</div>
<table>
  <thead><tr><th>No</th><th>Kode Unik</th><th>Username</th><th>Tanggal Daftar</th></tr></thead>
  <tbody>
    @forelse($detail as $i => $p)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $p->kode_unik }}</td>
      <td>{{ $p->username }}</td>
      <td>{{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="4" style="text-align:center;color:#aaa;">Tidak ada data.</td></tr>
    @endforelse
  </tbody>
</table>
@endif

<div class="footer">
  Dokumen ini digenerate otomatis oleh sistem ANHIVA &nbsp;|&nbsp; Bersifat rahasia dan hanya untuk keperluan internal
</div>

</body>
</html>
