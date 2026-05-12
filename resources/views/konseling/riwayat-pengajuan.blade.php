@extends('layouts.app')
@section('title', 'Riwayat Pengajuan Konseling')

@section('content')
<section class="section" style="background:var(--gray-50); min-height:80vh;">
    <div class="container">
        <a href="{{ route('konseling.index') }}" class="btn btn-sm btn-secondary mb-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        
        <div class="text-center mb-xl">
            <h2 style="font-size:2rem; font-weight:800; color:var(--gray-900);">Riwayat Pengajuan Konseling</h2>
            <p style="color:var(--gray-400);">Pantau status dan jadwal pengajuan konseling Anda</p>
        </div>

        @if(!auth()->check() && !isset($riwayat))
            <!-- Form Input Kode Unik (Untuk Anonim) -->
            <div class="card" style="max-width:500px; margin:0 auto;">
                <div class="card-body">
                    <h4 class="text-center mb-md"><i class="fas fa-search text-teal"></i> Cek Status Terkini</h4>
                    <form action="{{ route('konseling.riwayat.cari') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="kode_unik">Masukkan Kode Unik Anda</label>
                            <input type="text" name="kode_unik" class="form-control" placeholder="ANH-XXXXXXXXXX" required>
                            <small class="form-hint">Kode unik diberikan saat Anda mengajukan konseling secara anonim.</small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Cek Status</button>
                    </form>
                </div>
            </div>
        @else
            <!-- Menampilkan Data Riwayat -->
            @if(isset($riwayat) && count($riwayat) > 0)
                <div class="grid" style="gap:var(--space-md); max-width:800px; margin:0 auto;">
                    @foreach($riwayat as $k)
                    <div class="card" style="border-left: 4px solid @if($k->status=='Menunggu') var(--amber-400) @elseif($k->status=='Dijadwalkan') var(--teal-400) @elseif($k->status=='Selesai') var(--risk-low-text) @else var(--risk-high-text) @endif;">
                        <div class="card-body">
                            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:var(--space-sm); align-items:center; margin-bottom:var(--space-sm);">
                                <h4 style="margin:0;"><i class="fas fa-calendar-check"></i> Pengajuan {{ $k->tanggal_pengajuan->format('d M Y') }}</h4>
                                <span class="badge @if($k->status=='Menunggu') badge-status-menunggu @elseif($k->status=='Dijadwalkan') badge-status-dijadwalkan @elseif($k->status=='Selesai') badge-risiko-rendah @else badge-risiko-tinggi @endif">{{ $k->status }}</span>
                            </div>
                            
                            <div style="color:var(--gray-400); font-size:0.95rem; margin-bottom:var(--space-md);">
                                <strong>Topik:</strong> {{ $k->alasan }} <br>
                                <strong>Metode:</strong> {{ $k->jenis }} <br>
                                @if($k->jadwal_konseling)
                                    <strong>Jadwal Konseling:</strong> {{ \Carbon\Carbon::parse($k->jadwal_konseling)->format('d M Y, H:i') }} <br>
                                @endif
                                @if($k->tanggal_reschedule_diminta)
                                    <strong>Tanggal Reschedule Diminta:</strong> {{ \Carbon\Carbon::parse($k->tanggal_reschedule_diminta)->format('d M Y, H:i') }} <br>
                                @endif
                                @if($k->catatan_reschedule)
                                    <strong>Alasan:</strong> {{ $k->catatan_reschedule }} <br>
                                @endif
                            </div>
                            
                            @if($k->status == 'Dijadwalkan' && $k->jadwal_konseling)
                                <div style="background:var(--teal-50); padding:var(--space-sm) var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-md); color:var(--teal-800);">
                                    <strong><i class="fas fa-clock"></i> Jadwal Konseling:</strong> {{ \Carbon\Carbon::parse($k->jadwal_konseling)->format('d M Y, H:i') }}
                                </div>

                                @if($k->lokasi_konseling)
                                <div style="margin-top:var(--space-sm); padding:var(--space-sm) var(--space-md); background:var(--teal-50); border-radius:var(--radius-md); border-left:3px solid var(--teal-400);">
                                    <i class="fas fa-map-marker-alt" style="color:var(--teal-400);"></i>
                                    <strong>Lokasi:</strong> {{ $k->lokasi_konseling }}
                                </div>
                                @endif

                                @if($k->jadwal_konseling != $k->tanggal_reschedule_diminta && $k->tanggal_reschedule_diminta != null && $k->konfirmasi_pengguna == null)
                                <div style="background:var(--amber-50); border:1px solid var(--amber-100); border-left:4px solid var(--amber-400); border-radius:var(--radius-md); padding:var(--space-md); margin-bottom:var(--space-md); margin-top:var(--space-md);">
                                    <p style="margin:0; font-size:0.85rem; color:var(--amber-800);">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Jadwal yang ditetapkan berbeda dari permintaan Anda.<br>
                                        <span>Jadwal diminta: <strong>{{ \Carbon\Carbon::parse($k->tanggal_reschedule_diminta)->format('d M Y, H:i') }}</strong></span><br>
                                        <span>Jadwal ditetapkan: <strong>{{ \Carbon\Carbon::parse($k->jadwal_konseling)->format('d M Y, H:i') }}</strong></span><br>
                                        Silakan konfirmasi jadwal baru ini atau minta reschedule kembali jika belum mencapai batas.
                                    </p>
                                </div>
                                @endif

                                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:var(--space-md);">
                                    @if($k->konfirmasi_pengguna == 'Dikonfirmasi')
                                        <span style="font-size:0.9rem; color:var(--risk-low-text);"><i class="fas fa-check-circle"></i> Kehadiran telah dikonfirmasi</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary" onclick="konfirmasiKehadiran({{ $k->id_konseling }})"><i class="fas fa-check"></i> Konfirmasi Kehadiran</button>
                                        @if(($k->jumlah_reschedule ?? 0) < 2)
                                            <button type="button" class="btn btn-sm btn-outline" onclick="mintaReschedule({{ $k->id_konseling }})"><i class="fas fa-calendar-alt"></i> Minta Reschedule</button>
                                        @else
                                            <span style="font-size:0.85rem; color:var(--amber-600);"><i class="fas fa-exclamation-circle"></i> Anda telah mencapai batas maksimal reschedule (2x).</span>
                                            <form id="batalkan-form-{{ $k->id_konseling }}" action="{{ route('konseling.batalkan', $k->id_konseling) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm" style="background:transparent; color:var(--risk-high-text); border:1px solid var(--risk-high-text);" onclick="batalkanPengajuanMaxReschedule({{ $k->id_konseling }})" title="Jika jadwal ini tidak sesuai, Anda hanya dapat membatalkan pengajuan.">
                                                    <i class="fas fa-times-circle"></i> Batalkan Pengajuan
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <form id="reschedule-form-{{ $k->id_konseling }}" action="{{ route('konseling.reschedule', $k->id_konseling) }}" method="POST" style="display:none; margin-top:var(--space-md);">
                                    @csrf @method('PUT')
                                    <label class="form-label">Tanggal & Waktu yang Anda Inginkan</label>
                                    <input type="datetime-local" name="tanggal_reschedule_diminta" class="form-control mb-sm" required>
                                    <label class="form-label">Alasan (Opsional)</label>
                                    <textarea name="catatan_reschedule" class="form-control mb-sm" rows="3" placeholder="Opsional: berikan alasan singkat permintaan reschedule"></textarea>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('reschedule-form-{{ $k->id_konseling }}').style.display='none'">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-amber">Kirim Permintaan Reschedule</button>
                                    </div>
                                </form>
                            @endif

                            @if($k->status == 'Selesai')
                                @if($k->lokasi_konseling)
                                <div style="margin-top:var(--space-sm); padding:var(--space-sm) var(--space-md); background:var(--teal-50); border-radius:var(--radius-md); border-left:3px solid var(--teal-400);">
                                    <i class="fas fa-map-marker-alt" style="color:var(--teal-400);"></i>
                                    <strong>Lokasi:</strong> {{ $k->lokasi_konseling }}
                                </div>
                                @endif
                                <div style="display:flex; gap:10px; margin-top:var(--space-md);">
                                    <span style="color:var(--risk-low-text); font-size:0.9rem;"><i class="fas fa-check-circle"></i> Kehadiran telah dikonfirmasi</span>
                                </div>
                            @endif

                            @if($k->status == 'Menunggu Reschedule')
                                <div style="background:var(--amber-50); padding:var(--space-sm) var(--space-md); border-radius:var(--radius-md); color:var(--amber-800);">
                                    <i class="fas fa-info-circle"></i> Permintaan reschedule Anda sedang diproses oleh konselor.
                                </div>
                            @endif

                            @if($k->status == 'Menunggu')
                                <div style="margin-top:var(--space-sm);">
                                    <form id="batalkan-form-{{ $k->id_konseling }}" action="{{ route('konseling.batalkan', $k->id_konseling) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm" style="background:transparent; color:var(--risk-high-text); border:1px solid var(--risk-high-text);" onclick="batalkanPengajuan({{ $k->id_konseling }})">
                                            <i class="fas fa-times-circle"></i> Batalkan Pengajuan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card" style="max-width:500px; margin:0 auto; text-align:center;">
                    <div class="card-body" style="padding:var(--space-xl) var(--space-lg);">
                        <i class="fas fa-calendar-times" style="font-size:3rem; color:var(--gray-100); margin-bottom:var(--space-md);"></i>
                        <h4>Riwayat Tidak Ditemukan</h4>
                        <p style="color:var(--gray-400); margin-bottom:var(--space-lg);">Tidak ada riwayat pengajuan konseling yang sesuai.</p>
                        <a href="{{ route('konseling.pengajuan') }}" class="btn btn-primary">Ajukan Konseling Sekarang</a>
                        @if(!auth()->check())
                            <div style="margin-top:var(--space-md);">
                                <a href="{{ route('konseling.riwayat') }}" style="font-size:0.9rem; font-weight:600;">Cari Ulang Kode Unik</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
function mintaReschedule(id) {
    const form = document.getElementById('reschedule-form-' + id);
    if(form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function konfirmasiKehadiran(id) {
    Swal.fire({
        title: 'Apakah Anda yakin ingin mengkonfirmasi kehadiran untuk jadwal konseling ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1D9E75',
        cancelButtonColor: '#888780',
        confirmButtonText: 'Ya, Konfirmasi',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            fetch(`/konseling/${id}/konfirmasi`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            }).then(() => location.reload());
        }
    });
}

function batalkanPengajuanMaxReschedule(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Anda telah mencapai batas reschedule. Membatalkan pengajuan ini berarti Anda harus mengajukan konseling baru. Apakah Anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#A32D2D',
        cancelButtonColor: '#888780',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('batalkan-form-' + id).submit();
        }
    });
}

function batalkanPengajuan(id) {
    Swal.fire({
        title: 'Apakah Anda yakin ingin membatalkan pengajuan konseling ini?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#A32D2D',
        cancelButtonColor: '#888780',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('batalkan-form-' + id).submit();
        }
    });
}
</script>
@endpush
