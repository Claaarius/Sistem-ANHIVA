<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - ANHIVA')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body>
    @php
        $admin = auth('admin')->user();

        $notifKonseling = \App\Models\Konseling::where('sudah_dilihat_admin', false)->count();

        $unreadCount = \App\Models\Komentar::where('is_edited', true)
                ->where('sudah_dilihat_admin', false)
                ->count()
            + \App\Models\Komentar::where('status', 'Menunggu')->count();
    @endphp

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="brand">
                    <i class="fas fa-shield-halved"></i> ANHIVA
                </div>
                <div class="brand-sub">Panel Administrasi</div>
            </div>

            <nav class="admin-sidebar-nav">
                <div class="admin-nav-section">
                    <div class="admin-nav-section-title">Menu Utama</div>

                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>

                    <a href="{{ route('admin.edukasi.index') }}" class="admin-nav-link {{ request()->routeIs('admin.edukasi*') ? 'active' : '' }}">
                        <i class="fas fa-book-open"></i> Edukasi
                    </a>

                    <a href="{{ route('admin.skrining.index') }}" class="admin-nav-link {{ request()->routeIs('admin.skrining*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i> Skrining
                    </a>

                    <a href="{{ route('admin.konseling.index') }}" class="admin-nav-link {{ request()->routeIs('admin.konseling*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i> Konseling

                        @if($notifKonseling > 0)
                            <span class="nav-badge">{{ $notifKonseling }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.komentar.index') }}" class="admin-nav-link {{ request()->routeIs('admin.komentar*') ? 'active' : '' }}">
                        <i class="fas fa-message"></i> Komentar

                        @if($unreadCount > 0)
                            <span class="nav-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.gallery.index') }}" class="admin-nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i> Gallery
                    </a>

                    <a href="{{ route('admin.laporan.index') }}" class="admin-nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                        <i class="fas fa-file-lines"></i> Laporan
                    </a>
                </div>

                @if($admin && $admin->isSuperAdmin())
                    <div class="admin-nav-section">
                        <div class="admin-nav-section-title">Super Admin</div>

                        <a href="{{ route('admin.manajemen.index') }}" class="admin-nav-link {{ request()->routeIs('admin.manajemen*') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i> Manajemen Admin
                        </a>
                    </div>
                @endif

                <div class="admin-nav-section">
                    <div class="admin-nav-section-title">Akun</div>

                    <a href="{{ route('admin.password') }}" class="admin-nav-link {{ request()->routeIs('admin.password') || request()->routeIs('admin.profil.update') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i> Kelola Akun
                    </a>

                    <a href="{{ route('admin.logout') }}" class="admin-nav-link"
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>

                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            </nav>

            @if($admin)
                <div class="admin-sidebar-footer">
                    <div class="admin-user-info">
                        <div class="admin-user-avatar">
                            {{ strtoupper(substr($admin->nama_admin, 0, 1)) }}
                        </div>

                        <div>
                            <div class="admin-user-name">{{ $admin->nama_admin }}</div>
                            <div class="admin-user-role">{{ $admin->role }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-topbar">
                <div class="d-flex align-center gap-md">
                    <button class="navbar-toggle"
                            onclick="document.getElementById('adminSidebar').classList.toggle('active')"
                            style="display:none;"
                            id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="admin-topbar-actions">
                    @yield('page-actions')
                </div>
            </div>

            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonColor: '#1D9E75'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: @json(session('error')),
                    confirmButtonColor: '#1D9E75'
                });
            });
        </script>
    @endif

    <script>
        function handleSidebarToggle() {
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (sidebarToggle) {
                sidebarToggle.style.display = window.innerWidth <= 992 ? 'block' : 'none';
            }
        }

        handleSidebarToggle();

        window.addEventListener('resize', handleSidebarToggle);
    </script>

    @stack('scripts')
</body>
</html>