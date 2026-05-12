@extends('layouts.app')
@section('title', 'Kelola Akun - ANHIVA')

@section('content')
<section class="section" style="background:var(--gray-50);">
    <div class="container">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary mb-md"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="section-header">
            <h2>Kelola Akun</h2>
            <p>Atur profil, keamanan, dan lihat aktivitas Anda</p>
            <div class="section-line"></div>
        </div>

        <div class="grid" style="grid-template-columns: 300px 1fr; gap:var(--space-2xl); align-items:start;">
            
            <!-- Sidebar -->
            <div class="card" style="position:sticky; top:100px;">
                <div class="card-body text-center">
                    @if(!empty($pengguna->foto_profil))
                        <img src="{{ asset('storage/' . $pengguna->foto_profil) }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect width=\'400\' height=\'300\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'48\'%3E📷%3C/text%3E%3C/svg%3E';" alt="{{ $pengguna->username }}" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin:0 auto var(--space-md); display:block;">
                    @else
                        <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg, var(--teal-400), var(--teal-600)); color:white; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:2rem; margin:0 auto var(--space-md);">
                            {{ strtoupper(substr($pengguna->username, 0, 1)) }}
                        </div>
                    @endif
                    <h3 style="margin-bottom:var(--space-xs);">{{ $pengguna->username }}</h3>
                    <div class="badge badge-teal mb-lg">{{ $pengguna->kode_unik }}</div>
                    
                    <div style="text-align:left; border-top:1px solid var(--gray-100); padding-top:var(--space-md); margin-bottom:var(--space-md);">
                        <a href="#profil" class="btn btn-secondary btn-block" style="justify-content:flex-start; margin-bottom:var(--space-sm); background:transparent; border-color:transparent;"><i class="fas fa-user mb-0 w-20"></i> Profil Anda</a>
                        <a href="#keamanan" class="btn btn-secondary btn-block" style="justify-content:flex-start; margin-bottom:var(--space-sm); background:transparent; border-color:transparent;"><i class="fas fa-lock mb-0 w-20"></i> Keamanan</a>
                        <a href="#komentar" class="btn btn-secondary btn-block" style="justify-content:flex-start; background:transparent; border-color:transparent;"><i class="fas fa-comments mb-0 w-20"></i> Riwayat Komentar</a>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div>
                <!-- Edit Profil -->
                <div id="profil" class="card" style="margin-bottom:var(--space-lg);">
                    <div class="card-header">
                        <h4 style="margin:0;"><i class="fas fa-user-edit" style="color:var(--teal-400);"></i> Edit Profil</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('akun.profil') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username', $pengguna->username) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $pengguna->email) }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Foto Profil</label>
                                <div style="display:flex; align-items:center; gap:var(--space-md); flex-wrap:wrap;">
                                    <div id="foto-preview-wrapper" style="width:80px; height:80px; border-radius:50%; overflow:hidden; border:2px solid var(--teal-200); flex-shrink:0;">
                                        @if(!empty($pengguna->foto_profil))
                                            <img id="foto-preview" src="{{ asset('storage/' . $pengguna->foto_profil) }}" alt="Preview" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div id="foto-preview-placeholder" style="width:100%; height:100%; background:linear-gradient(135deg, var(--teal-400), var(--teal-600)); display:flex; align-items:center; justify-content:center; color:white; font-size:2rem; font-weight:bold;">
                                                {{ strtoupper(substr($pengguna->username, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div style="flex:1;">
                                        <input type="file" name="foto_profil" id="foto_profil" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-control" style="font-size:0.9rem;" onchange="previewFoto(this)">
                                        <p style="font-size:0.78rem; color:var(--gray-400); margin-top:4px;">Format: JPG, PNG, WEBP. Maks. 2MB.</p>
                                    </div>
                                </div>
                                @error('foto_profil')
                                    <div class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Profil</button>
                        </form>
                    </div>
                </div>

                <!-- Ganti Password -->
                <div id="keamanan" class="card" style="margin-bottom:var(--space-lg);">
                    <div class="card-header">
                        <h4 style="margin:0;"><i class="fas fa-key" style="color:var(--teal-400);"></i> Ganti Password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('akun.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="form-group" style="position:relative;">
                                <label class="form-label">Password Saat Ini</label>
                                <div style="position:relative;">
                                    <input type="password" name="current_password" id="current_password" class="form-control" required style="padding-right:40px;">
                                    <button type="button" onclick="togglePassword('current_password', 'toggle-curr')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;"><i class="fas fa-eye" id="toggle-curr"></i></button>
                                </div>
                            </div>
                            <div class="grid grid-2">
                                <div class="form-group" style="position:relative;">
                                    <label class="form-label">Password Baru</label>
                                    <div style="position:relative;">
                                        <input type="password" name="password" id="password_new" class="form-control" required placeholder="Min 8, maks 20 karakter" maxlength="20" style="padding-right:40px;">
                                        <button type="button" onclick="togglePassword('password_new', 'toggle-new')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;"><i class="fas fa-eye" id="toggle-new"></i></button>
                                    </div>
                                </div>
                                <div class="form-group" style="position:relative;">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <div style="position:relative;">
                                        <input type="password" name="password_confirmation" id="password_conf" class="form-control" required maxlength="20" style="padding-right:40px;">
                                        <button type="button" onclick="togglePassword('password_conf', 'toggle-conf')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--gray-400); cursor:pointer;"><i class="fas fa-eye" id="toggle-conf"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Ubah Password</button>
                        </form>
                    </div>
                </div>

                <!-- Riwayat Komentar -->
                <div id="komentar" class="card" style="margin-bottom:var(--space-lg);">
                    <div class="card-header">
                        <h4 style="margin:0;"><i class="fas fa-comments" style="color:var(--teal-400);"></i> Riwayat Komentar Saya</h4>
                    </div>
                    <div class="card-body">
                        @if($riwayatKomentar->count() > 0)
                            <div style="display:flex; flex-direction:column; gap:var(--space-md);">
                                @foreach($riwayatKomentar as $komentar)
                                <div style="padding:var(--space-md); border:1px solid var(--gray-100); border-radius:var(--radius-md); background:var(--gray-50);">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:var(--space-sm);">
                                        <span class="badge @if($komentar->status=='Disetujui') badge-risiko-rendah @elseif($komentar->status=='Menunggu') badge-status-menunggu @else badge-risiko-tinggi @endif">{{ $komentar->status }}</span>
                                        <span style="font-size:0.8rem; color:var(--gray-400);">{{ $komentar->tanggal_komentar->format('d M Y H:i') }}</span>
                                    </div>
                                    <p style="font-size:0.95rem; margin:0;" id="ktext-{{ $komentar->id_komentar }}">{{ $komentar->isi_komentar }}</p>
                                    
                                    @if($komentar->status == 'Disetujui' && $komentar->jumlah_edit < 3)
                                    <!-- Edit Inline Form -->
                                    <form id="kform-{{ $komentar->id_komentar }}" action="{{ route('komentar.update', $komentar->id_komentar) }}" method="POST" style="display:none; margin-top:var(--space-sm);">
                                        @csrf @method('PUT')
                                        <textarea name="isi_komentar" class="form-control mb-sm" rows="2" required>{{ $komentar->isi_komentar }}</textarea>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEditKomentar({{ $komentar->id_komentar }})">Batal</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Simpan Edit</button>
                                        </div>
                                    </form>
                                    <div class="text-right mt-sm" id="kbtn-{{ $komentar->id_komentar }}">
                                        <button type="button" class="btn btn-sm btn-outline" onclick="toggleEditKomentar({{ $komentar->id_komentar }})"><i class="fas fa-edit"></i> Edit</button>
                                    </div>
                                    @endif
                                    <div class="text-right mt-sm">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusKomentar({{ $komentar->id_komentar }})"><i class="fas fa-trash"></i> Hapus</button>
                                    </div>
                                    <form id="hapus-komentar-{{ $komentar->id_komentar }}" action="{{ route('akun.komentar.hapus', $komentar->id_komentar) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center" style="padding:var(--space-xl) 0;">
                                <i class="fas fa-comment-slash" style="font-size:3rem; color:var(--gray-100); margin-bottom:var(--space-md);"></i>
                                <p style="color:var(--gray-400);">Anda belum memiliki riwayat komentar.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Hapus Akun -->
                <div class="card" style="border-color:var(--risk-high-text);">
                    <div class="card-body" style="background:#fffafa;">
                        <h4 style="color:var(--risk-high-text);"><i class="fas fa-exclamation-triangle"></i> Hapus Akun Permanen</h4>
                        <p style="font-size:0.9rem; color:var(--gray-400); margin-bottom:var(--space-md);">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen dari sistem.</p>
                        <button onclick="confirmDelete()" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Hapus Akun Saya</button>
                        
                        <form id="delete-form" action="{{ route('akun.destroy') }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
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

function confirmDelete() {
    Swal.fire({
        title: 'Yakin hapus akun?',
        text: "Semua data profil dan riwayat Anda akan terhapus. Tindakan ini permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#A32D2D',
        cancelButtonColor: '#888780',
        confirmButtonText: 'Ya, Hapus Permanen',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    })
}

function toggleEditKomentar(id) {
    const textEl = document.getElementById('ktext-' + id);
    const formEl = document.getElementById('kform-' + id);
    const btnEl =  document.getElementById('kbtn-' + id);
    
    if (formEl.style.display === 'none') {
        formEl.style.display = 'block';
        textEl.style.display = 'none';
        btnEl.style.display = 'none';
    } else {
        formEl.style.display = 'none';
        textEl.style.display = 'block';
        btnEl.style.display = 'block';
    }
}

function hapusKomentar(id) {
    Swal.fire({
        title: 'Apakah Anda yakin ingin menghapus komentar ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#A32D2D'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('hapus-komentar-' + id).submit();
        }
    });
}

function previewFoto(input) {
    const wrapper = document.getElementById('foto-preview-wrapper');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            wrapper.innerHTML = '<img id="foto-preview" src="' + e.target.result + '" alt="Preview" style="width:100%; height:100%; object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<style>
    @media (max-width: 768px) {
        .grid[style*="column"] { grid-template-columns: 1fr !important; }
        .card[style*="position:sticky"] { position:static !important; }
    }
</style>
@endpush
