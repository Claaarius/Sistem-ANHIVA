@extends('layouts.admin')
@section('title', 'Skrining - Admin ANHIVA')
@section('page-title', 'Pertanyaan Skrining')

@section('page-actions')
<a href="{{ route('admin.skrining.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pertanyaan</a>
@endsection

@section('content')
<!-- Aggregate Stats -->
<!-- Aggregate Stats -->
<div class="grid grid-4 mb-xl">

    <!-- Total Skrining -->
    <div class="stat-card">
        <div class="stat-icon teal">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-number">
            {{ $stats['total'] }}
        </div>
        <div class="stat-label">
            Total Skrining
        </div>
    </div>

    <!-- Risiko Rendah -->
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-shield-heart"></i>
        </div>
        <div class="stat-number" style="color:var(--risk-low-text);">
            {{ $stats['rendah'] }}
        </div>
        <div class="stat-label">
            Risiko Rendah
        </div>
    </div>

    <!-- Risiko Sedang -->
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="stat-number" style="color:var(--risk-med-text);">
            {{ $stats['sedang'] }}
        </div>
        <div class="stat-label">
            Risiko Sedang
        </div>
    </div>

    <!-- Risiko Tinggi -->
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fas fa-circle-exclamation"></i>
        </div>
        <div class="stat-number" style="color:var(--risk-high-text);">
            {{ $stats['tinggi'] }}
        </div>
        <div class="stat-label">
            Risiko Tinggi
        </div>
    </div>

</div>

<!-- Questions List -->
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Kategori</th>
                <th>Opsi Jawaban</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pertanyaan as $p)
            <tr>
                <td>{{ $p->urutan }}</td>
                <td style="max-width:300px;">{{ Str::limit($p->isi_pertanyaan, 80) }}</td>
                <td><span class="badge badge-teal">{{ $p->kategori }}</span></td>
                <td>{{ count($p->pilihan_jawaban) }} opsi</td>
                <td>
                    <div class="d-flex gap-sm">
                        <a href="{{ route('admin.skrining.edit', $p->id_pertanyaan) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.skrining.destroy', $p->id_pertanyaan) }}" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center" style="padding:var(--space-2xl); color:var(--gray-400);">Belum ada pertanyaan skrining.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
