@extends('layouts.admin')
@section('title', 'Edukasi - Admin ANHIVA')
@section('page-title', 'Materi Edukasi')

@section('page-actions')
<a href="{{ route('admin.edukasi.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Materi</a>
@endsection

@section('content')
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:100px;">Thumbnail</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal Publish</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materi as $m)
            <tr style="vertical-align:middle;">
                <td>
                    @if($m->thumbnail)
                        <img src="{{ asset('storage/' . $m->thumbnail) }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="Thumb" style="width:80px; height:60px; object-fit:cover; border-radius:var(--radius-sm);">
                    @else
                        <div style="width:80px; height:60px; background:var(--gray-100); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; color:var(--gray-400);">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $m->judul }}</strong></td>
                <td><span class="badge badge-teal">{{ $m->kategori }}</span></td>
                <td>{{ $m->tanggal_publish->format('d M Y') }}</td>
                <td>
                    <div class="d-flex gap-sm">
                        <a href="{{ route('admin.edukasi.edit', $m->id_materi) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.edukasi.destroy', $m->id_materi) }}" onsubmit="return confirm('Yakin hapus materi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center" style="padding:var(--space-2xl); color:var(--gray-400);">Belum ada materi edukasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $materi->links('pagination.simple') }}
@endsection
