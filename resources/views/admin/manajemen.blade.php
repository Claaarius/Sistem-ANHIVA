@extends('layouts.admin')
@section('title', 'Manajemen Admin - ANHIVA')
@section('page-title', 'Manajemen Admin')

@section('page-actions')
<button class="btn btn-sm btn-primary" onclick="document.getElementById('addAdminModal').classList.add('active')">
    <i class="fas fa-plus"></i> Tambah Admin
</button>
@endsection

@section('content')
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $a)
            <tr>
                <td><strong>{{ $a->nama_admin }}</strong></td>
                <td>{{ $a->email }}</td>
                <td>
                    <span class="badge {{ $a->role == 'Super Admin' ? 'badge-amber' : 'badge-teal' }}">{{ $a->role }}</span>
                </td>
                <td>
                    @if($a->id_admin !== Auth::guard('admin')->user()->id_admin)
                    <form method="POST" action="{{ route('admin.manajemen.destroy', $a->id_admin) }}" onsubmit="return confirm('Yakin hapus admin ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                    @else
                    <span class="badge badge-gray">Akun Anda</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Admin Modal -->
<div class="modal-overlay" id="addAdminModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Admin Baru</h3>
            <button class="modal-close" onclick="this.closest('.modal-overlay').classList.remove('active')">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.manajemen.store') }}" id="addAdminForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Admin *</label>
                    <input type="text" name="nama_admin" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-control" required>
                        <option value="Admin">Admin</option>
                        <option value="Super Admin">Super Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').classList.remove('active')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
