@extends('layouts.app')
@section('title', 'Admin Login - ANHIVA')

@section('content')
<div class="auth-page">
    <div class="auth-card fade-in">
        <div class="auth-header">
            <div class="auth-logo"><i class="fas fa-shield-halved"></i> ANHIVA</div>
            <p class="auth-subtitle">Panel Administrasi</p>
        </div>

        <form method="POST" action="{{ route('admin.login.submit') }}" id="adminLoginForm">
            @csrf

            @if(session('error'))
            <div style="background:#fef2f2; color:#991b1b; padding:var(--space-md); border-radius:var(--radius-md); margin-bottom:var(--space-lg); border-left:4px solid #ef4444; font-size:0.9rem;">
                <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i> {{ session('error') }}
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">Email Admin</label>
                <input type="email" name="email" class="form-control" placeholder="admin@anhiva.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Masuk ke Panel Admin</button>
        </form>

        <p class="text-center mt-lg" style="font-size:0.8rem; color:var(--gray-400);">
            <a href="{{ route('dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke halaman utama</a>
        </p>
    </div>
</div>
@endsection
