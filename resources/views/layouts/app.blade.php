<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ANHIVA - Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim">
    <title>@yield('title', 'ANHIVA - Anonymous HIV Assessment')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <div class="brand-icon"><i class="fas fa-shield-halved"></i></div>
                ANHIVA
            </a>

            <button class="navbar-toggle" onclick="toggleMenu()" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-menu" id="navMenu">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('edukasi.index') }}" class="{{ request()->routeIs('edukasi.*') ? 'active' : '' }}">Edukasi</a></li>
                <li><a href="{{ route('skrining.index') }}" class="{{ request()->routeIs('skrining.*') ? 'active' : '' }}">Skrining</a></li>
                <li><a href="{{ route('konseling.index') }}" class="{{ request()->routeIs('konseling.*') ? 'active' : '' }}">Konseling</a></li>
            </ul>

            <div class="navbar-actions">
                @auth
                    <span style="font-size:0.8rem;color:var(--gray-400);margin-right:12px; display:flex; align-items:center; gap:6px;">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\'%3E%3Crect width=\'40\' height=\'40\' fill=\'%23E1F5EE\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%231D9E75\' font-size=\'20\'%3E👤%3C/text%3E%3C/svg%3E';" alt="{{ auth()->user()->username }}" style="width:28px; height:28px; border-radius:50%; object-fit:cover; display:block;">
                        @else
                            <i class="fas fa-user-circle"></i>
                        @endif
                        {{ auth()->user()->username }}
                    </span>
                    <a href="{{ route('akun.index') }}" class="btn btn-sm btn-outline" style="margin-right:8px;">Kelola Akun</a>
                    <a href="#" class="btn btn-sm btn-outline" onclick="confirmUserLogout(event)">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                Swal.fire({icon:'success', title:'Berhasil', text:'{{ session("success") }}', confirmButtonColor:'#1D9E75'});
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                Swal.fire({icon:'error', title:'Gagal', text:'{{ session("error") }}', confirmButtonColor:'#1D9E75'});
            });
        </script>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <h4 style="color:var(--teal-200)"><i class="fas fa-shield-halved"></i> ANHIVA</h4>
                    <p>Sistem Edukasi dan Skrining Risiko HIV Berbasis Anonim. Melindungi privasi Anda sembari memberikan akses ke informasi dan layanan kesehatan yang penting.</p>
                </div>
                <div>
                    <h4>Menu</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('edukasi.index') }}">Edukasi</a></li>
                        <li><a href="{{ route('skrining.index') }}">Skrining</a></li>
                        <li><a href="{{ route('konseling.index') }}">Konseling</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Informasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('tentang') }}">Tentang ANHIVA</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Disclaimer</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} ANHIVA. Seluruh data bersifat anonim dan rahasia.</p>
            </div>
        </div>
    </footer>

    <script>
        function confirmUserLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1D9E75',
                cancelButtonColor: '#888780',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',

                allowOutsideClick: true,
                allowEscapeKey: true
                
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('active');
        }
    </script>

    @if(session()->has('skrining_pertanyaan'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let popupSudahMuncul = false;

        document.querySelectorAll('a').forEach(function(link) {
            var href = link.getAttribute('href');
            if (!href) return;
            if (href.includes('skrining')) return;
            if (href.startsWith('#')) return;
            if (href.startsWith('javascript')) return;
            if (link.closest('.navbar-actions')) return;

            link.addEventListener('click', function(e) {

            if (popupSudahMuncul) {
                e.preventDefault();
                return;

                }

                e.preventDefault();
                popupSudahMuncul = true;

                Swal.fire({
                    title: 'Tinggalkan Skrining?',
                    text: 'Anda sedang dalam proses skrining. Yakin akan meninggalkan halaman ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1D9E75',
                    cancelButtonColor: '#888780',
                    confirmButtonText: 'Ya, Tinggalkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {

    if (result.isConfirmed) {

        fetch('{{ route("skrining.batalkan.session") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = href;
        })
        .catch(() => {
            window.location.href = href;
        });

    } else {

        popupSudahMuncul = false;

    }

});
            });
        });
    });
    </script>
    @endif

    @stack('scripts')
</body>
</html>