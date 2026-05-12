@extends('layouts.app')
@section('title', 'Login - ANHIVA')

@section('content')
<div class="auth-split">
    <div class="auth-split-left" style="position:relative; overflow:hidden; background:linear-gradient(135deg, var(--teal-800) 0%, var(--teal-600) 50%, var(--teal-400) 100%);">
        <!-- Background shapes -->
        <div style="position:absolute; top:-20%; left:-10%; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.1); z-index:0;"></div>
        <div style="position:absolute; bottom:-10%; right:-20%; width:400px; height:400px; border-radius:50%; background:rgba(255,255,255,0.05); z-index:0;"></div>

        <div style="position:relative; z-index:1; max-width:400px;">
            <a href="{{ route('dashboard') }}" style="color:white; text-decoration:none; display:inline-flex; align-items:center; gap:10px; font-size:2rem; font-weight:800; margin-bottom:var(--space-2xl);">
                <div style="width:48px; height:48px; background:white; color:var(--teal-600); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                ANHIVA
            </a>

            <h2 style="font-size:2.5rem; color:white; margin-bottom:var(--space-lg); line-height:1.2;">Selamat Datang Kembali</h2>
            <p style="font-size:1.1rem; color:rgba(255,255,255,0.9); margin-bottom:var(--space-xl);">Masuk untuk melanjutkan proses konsultasi, skrining, atau berbagi pengalaman Anda di komunitas kami.</p>

            <ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:var(--space-md);">
                <li style="display:flex; align-items:center; gap:15px; font-size:1.05rem; color:white;">
                    <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;"><i class="fas fa-user-secret"></i></div>
                    Anonim & Terjaga
                </li>
                <li style="display:flex; align-items:center; gap:15px; font-size:1.05rem; color:white;">
                    <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;"><i class="fas fa-lock"></i></div>
                    Sistem Keamanan Kuat
                </li>
                <li style="display:flex; align-items:center; gap:15px; font-size:1.05rem; color:white;">
                    <div style="width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;"><i class="fas fa-bolt"></i></div>
                    Proses Cepat & Mudah
                </li>
            </ul>
        </div>
    </div>

    <div class="auth-split-right">
        <div style="width:100%; max-width:450px;">
            <div style="text-align:center; margin-bottom:var(--space-2xl);">
                <h2 style="font-size:2rem; font-weight:800; color:var(--gray-900);">Masuk Akun</h2>
                <p style="color:var(--gray-400);">Gunakan email dan password yang terdaftar</p>
                <div class="section-line" style="margin:var(--space-md) auto 0; width:40px; height:4px; background:var(--teal-400); border-radius:var(--radius-full);"></div>
            </div>

            @if(session('error'))
            <div style="background:var(--risk-high-bg); color:var(--risk-high-text); padding:var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-lg); border-left:4px solid var(--risk-high-text);">
                <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i> {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" onsubmit="this.querySelector('button[type=submit]').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Memproses...'; this.querySelector('button[type=submit]').disabled = true;">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    @error('email')
                        <div class="form-error" style="margin-bottom:6px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="contoh@email.com">
                </div>

                <div class="form-group" style="position:relative;">
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimal 8 karakter" maxlength="20" style="padding-right: 40px;">
                        <button type="button" onclick="togglePassword('password', 'toggleIcon')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:var(--space-xl); transition:all 0.3s;">
    Masuk Sekarang
</button>
</form>

            <div class="text-center mt-xl">
                <p style="color:var(--gray-400);">Belum punya akun? <a href="{{ route('register') }}" style="font-weight:600;">Daftar di sini</a></p>
                <div style="margin-top:var(--space-lg);">
                    <a href="{{ route('dashboard') }}" style="color:var(--gray-400); font-size:0.9rem;"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
                </div>
            </div>
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
@endpush
