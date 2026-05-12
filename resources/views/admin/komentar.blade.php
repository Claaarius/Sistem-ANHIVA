@extends('layouts.admin')
@section('title', 'Komentar - Admin ANHIVA')
@section('page-title', 'Moderasi Komentar')

@section('content')
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Info</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($komentar as $k)
            <tr style="{{ $k->is_edited && !$k->sudah_dilihat_admin ? 'background:var(--amber-50);' : '' }}">
                <td>
                    <div>
                        <strong>{{ $k->nama_tampil }}</strong>
                        <div style="font-size:0.75rem;color:var(--gray-400);font-family:monospace;">{{ \App\Models\Pengguna::sensorKodeUnik($k->kode_unik) }}</div>
                    </div>
                </td>
                <td style="max-width:280px;">
                    <span style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; font-size:0.9rem;">{{ $k->isi_komentar }}</span>
                    @if($k->is_edited && !$k->sudah_dilihat_admin)
                        <span class="badge badge-amber" style="margin-top:4px; display:inline-block;">Diedit</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $k->status == 'Disetujui' ? 'badge-risiko-rendah' : ($k->status == 'Ditolak' ? 'badge-risiko-tinggi' : 'badge-amber') }}">{{ $k->status }}</span>
                </td>
                <td>{{ $k->tanggal_komentar->format('d M Y') }}</td>
                <td>
                    @if($k->id_pengguna)
                    <span style="font-size:0.75rem; color:var(--gray-400);">
                        Edit: {{ $k->jumlah_edit }}/3
                    </span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-sm flex-wrap">
                        @if($k->status == 'Menunggu')
                        <form method="POST" action="{{ route('admin.komentar.approve', $k->id_komentar) }}">
                            @csrf @method('PUT')
                            <button class="btn btn-sm btn-primary" title="Setujui"><i class="fas fa-check"></i></button>
                        </form>
                        <form method="POST" action="{{ route('admin.komentar.reject', $k->id_komentar) }}">
                            @csrf @method('PUT')
                            <button class="btn btn-sm btn-secondary" title="Tolak"><i class="fas fa-times"></i></button>
                        </form>
                        @endif

                        {{-- Lihat Detail: selalu tampil untuk semua komentar --}}
                        <button type="button" class="btn btn-sm btn-outline" title="Lihat Detail Komentar"
                            onclick="lihatKomentar(this)"
                            data-id="{{ $k->id_komentar }}"
                            data-nama="{{ $k->nama_tampil }}"
                            data-kode="{{ \App\Models\Pengguna::sensorKodeUnik($k->kode_unik) }}"
                            data-isi="{{ e($k->isi_komentar) }}"
                            data-tanggal="{{ $k->tanggal_komentar->format('d M Y') }}"
                            data-edit="{{ $k->jumlah_edit }}"
                            data-login="{{ $k->id_pengguna ? '1' : '0' }}"
                            data-status="{{ $k->status }}"
                            data-perlu-read="{{ ($k->is_edited && !$k->sudah_dilihat_admin) ? '1' : '0' }}">
                            <i class="fas fa-eye"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.komentar.destroy', $k->id_komentar) }}" onsubmit="return confirm('Yakin hapus komentar ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="padding:var(--space-2xl); color:var(--gray-400);">Belum ada komentar.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $komentar->links('pagination.simple') }}
@endsection

@push('scripts')
<script>
function lihatKomentar(btn) {
    const id        = btn.dataset.id;
    const nama      = btn.dataset.nama;
    const kodeUnik  = btn.dataset.kode;
    const isi       = btn.dataset.isi;
    const tanggal   = btn.dataset.tanggal;
    const edit      = btn.dataset.edit;
    const isLogin   = btn.dataset.login === '1';
    const status    = btn.dataset.status;
    const perluRead = btn.dataset.perluRead === '1';

    const statusBadge = status === 'Disetujui'
        ? '<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:999px;font-size:0.78rem;">Disetujui</span>'
        : status === 'Ditolak'
        ? '<span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:999px;font-size:0.78rem;">Ditolak</span>'
        : '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:999px;font-size:0.78rem;">Menunggu</span>';

    Swal.fire({
        title: '<i class="fas fa-comment-alt" style="color:var(--teal-500);margin-right:8px;"></i>Detail Komentar',
        html: `
            <div style="text-align:left; font-size:0.92rem;">
                <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; width:110px; vertical-align:top;">Pengguna</td>
                        <td style="padding:5px 8px; font-weight:600;">${nama}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; vertical-align:top;">Kode Unik</td>
                        <td style="padding:5px 8px; font-family:monospace; font-size:0.85rem;">${kodeUnik}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; vertical-align:top;">Status</td>
                        <td style="padding:5px 8px;">${statusBadge}</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; vertical-align:top;">Tanggal</td>
                        <td style="padding:5px 8px;">${tanggal}</td>
                    </tr>
                    ${isLogin ? `
                    <tr>
                        <td style="padding:5px 8px; color:#6b7280; vertical-align:top;">Jumlah Edit</td>
                        <td style="padding:5px 8px;">${edit} / 3 kali</td>
                    </tr>` : ''}
                </table>
                <div style="border-top:1px solid #e5e7eb; padding-top:14px;">
                    <div style="font-size:0.78rem; text-transform:uppercase; font-weight:700; color:#9ca3af; margin-bottom:8px; letter-spacing:.05em;">Isi Komentar</div>
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:14px; line-height:1.75; max-height:300px; overflow-y:auto; white-space:pre-wrap; word-break:break-word;">${isi}</div>
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: perluRead ? '✅ Tandai Sudah Dibaca' : 'Tutup',
        confirmButtonColor: '#1D9E75',
        showCancelButton: perluRead,
        cancelButtonText: 'Tutup',
    }).then((result) => {
        if (result.isConfirmed && perluRead) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            fetch(`/admin/komentar/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            }).then(() => location.reload());
        }
    });
}
</script>
@endpush
