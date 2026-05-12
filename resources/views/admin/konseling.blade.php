@extends('layouts.admin')
@section('title', 'Konseling - Admin ANHIVA')
@section('page-title', 'Manajemen Konseling')

@section('content')
<div class="filter-tabs" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:var(--space-lg);">
    <a href="{{ route('admin.konseling.index') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">
        Semua
    </a>
    @foreach(['Menunggu', 'Dijadwalkan', 'Menunggu Reschedule', 'Selesai'] as $s)
    <a href="{{ route('admin.konseling.index', ['status' => $s]) }}"
       class="btn btn-sm {{ request('status') == $s ? 'btn-primary' : 'btn-outline' }}">
        {{ $s }}
        @php $count = $konselingCount[$s] ?? 0; @endphp
        @if($count > 0)
        <span style="background:rgba(255,255,255,0.3); border-radius:999px; padding:1px 7px; font-size:0.75rem; margin-left:4px;">{{ $count }}</span>
        @endif
    </a>
    @endforeach
</div>
@if(request('status'))
<a href="{{ route('admin.konseling.index') }}" class="btn btn-sm btn-secondary mb-lg">
    <i class="fas fa-arrow-left"></i> Kembali ke Semua Konseling
</a>
@endif
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Kode Unik</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jadwal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konseling as $k)
            <tr>
                <td><code style="color:var(--teal-400);">{{ $k->kode_unik }}</code></td>
                <td>{{ $k->jenis }}</td>
                <td><span class="badge {{ $k->status_badge_class }}">{{ $k->status }}</span></td>
                <td>{{ $k->tanggal_pengajuan->format('d M Y') }}</td>
                <td>{{ $k->jadwal_konseling ? $k->jadwal_konseling->format('d M Y H:i') : '-' }}</td>
                <td>
                    <div class="d-flex gap-sm flex-wrap">
                        <a href="{{ route('admin.konseling.show', $k->id_konseling) }}" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> Detail</a>
                        <form id="hapus-konseling-{{ $k->id_konseling }}" action="{{ route('admin.konseling.destroy', $k->id_konseling) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            @if(in_array($k->status, ['Dijadwalkan', 'Menunggu Reschedule']))
                                <button type="button" class="btn btn-sm btn-danger btn-disabled" disabled title="Konseling yang sedang aktif tidak dapat dihapus"><i class="fas fa-trash"></i> Hapus</button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger" onclick="hapusKonseling({{ $k->id_konseling }})"><i class="fas fa-trash"></i> Hapus</button>
                            @endif
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="padding:var(--space-2xl); color:var(--gray-400);">Belum ada pengajuan konseling.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $konseling->links('pagination.simple') }}
@endsection

@push('scripts')
<script>
@if($showRescheduleNotif)
Swal.fire({
    icon: 'info',
    title: 'Perhatian',
    text: 'Ada {{ $pendingReschedule }} permintaan reschedule konseling yang perlu ditangani!',
    confirmButtonColor: '#1D9E75',
    confirmButtonText: 'Lihat Sekarang',
    showCancelButton: true,
    cancelButtonText: 'Nanti',
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = '{{ route('admin.konseling.index', ['status' => 'Menunggu Reschedule']) }}';
    }
});
@endif

function hapusKonseling(id) {
    Swal.fire({
        title: 'Apakah Anda yakin ingin menghapus data konseling ini?',
        text: 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#A32D2D',
        cancelButtonColor: '#888780',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('hapus-konseling-' + id).submit();
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.btn-disabled {
    opacity: 0.45;
    cursor: not-allowed;
}
</style>
@endpush
