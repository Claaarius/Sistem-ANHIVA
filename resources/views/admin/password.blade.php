@extends('layouts.admin')
@section('title', 'Kelola Akun Admin')
@section('page-title', 'Kelola Akun Admin')

@section('content')
<div class="grid grid-2" style="align-items:start;">

    <!-- Profil Admin -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin:0;"><i class="fas fa-user-edit text-teal"></i> Update Profil</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profil.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label" for="nama_admin">Nama Admin</label>
                    <input type="text" id="nama_admin" name="nama_admin" class="form-control" value="{{ Auth::guard('admin')->user()->nama_admin }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Profil</button>
            </form>
        </div>
    </div>

    <!-- Ganti Password -->
    <div class="card">
        <div class="card-header">
            <h4 style="margin:0;"><i class="fas fa-key text-teal"></i> Ganti Password</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div style="background:var(--success-bg); color:var(--success-text); padding:var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-md); border-left:4px solid var(--success-text);">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:var(--risk-high-bg); color:var(--risk-high-text); padding:var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-md); border-left:4px solid var(--risk-high-text);">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
            <div style="background:var(--risk-high-bg); color:var(--risk-high-text); padding:var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-md); border-left:4px solid var(--risk-high-text);">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group" style="position:relative;">
                    <label class="form-label" for="current_password">Password Saat Ini</label>
                    <div style="position:relative;">
                        <input type="password" id="current_password" name="current_password" class="form-control" required style="padding-right:40px;">
                        <button type="button" onclick="togglePassword('current_password', 'toggleIcon1')" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;">
                            <i class="fas fa-eye" id="toggleIcon1"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div style="color:#C53030; font-size:0.9rem; margin-top:0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="position:relative;">
                    <label class="form-label" for="password">Password Baru</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Minimal 8 karakter" style="padding-right:40px;">
                        <button type="button" onclick="togglePassword('password', 'toggleIcon2')" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;">
                            <i class="fas fa-eye" id="toggleIcon2"></i>
                        </button>
                    </div>
                    @error('password')
                        <div style="color:#C53030; font-size:0.9rem; margin-top:0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="position:relative;">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <div style="position:relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required style="padding-right:40px;">
                        <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon3')" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;">
                            <i class="fas fa-eye" id="toggleIcon3"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div style="color:#C53030; font-size:0.9rem; margin-top:0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-amber"><i class="fas fa-lock"></i> Perbarui Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
<style>
    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; }
    }
</style>
@endpush
