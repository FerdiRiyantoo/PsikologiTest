<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Papikostick</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #0f172a; /* Navy Deep */
            --sidebar-accent: #1e293b;
            --primary-color: #38bdf8; /* Sky Blue */
            --text-muted: #94a3b8;
            --glass-bg: rgba(255, 255, 255, 0.03);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e2937;
            overflow-x: hidden;
        }

        /* ====== SIDEBAR ====== */
        .sidebar {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            background-color: var(--sidebar-bg);
            height: 100vh;
            position: sticky;
            top: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .brand-wrapper {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Glassmorphism Profile Card */
        .profile-card {
            margin: 0 1.2rem 1.5rem;
            padding: 1rem;
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-2px);
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-color), #818cf8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: white;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
            text-transform: uppercase;
        }

        .nav-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            font-weight: 700;
            margin: 1.5rem 1.5rem 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            margin: 0.3rem 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link i {
            font-size: 1.1rem;
            transition: transform 0.2s;
        }

        .sidebar-link:hover {
            color: white;
            background-color: var(--sidebar-accent);
        }

        .sidebar-link:hover i {
            transform: translateX(3px);
            color: var(--primary-color);
        }

        .sidebar-link.active {
            background-color: var(--primary-color);
            color: #0f172a;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.25);
        }

        .sidebar-link.active i {
            color: #0f172a;
        }

        /* ====== TOPBAR ====== */
        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            z-index: 999;
        }

        .btn-notif {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
            border: none;
            transition: all 0.2s;
        }

        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 120px);
        }

        .footer {
            background: white;
            padding: 1rem 2rem;
            font-size: 0.8rem;
            border-top: 1px solid #f1f5f9;
        }
        .bg-blue-gradient {
        background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%) !important;
        color: #ffffff !important;
        }
    
    /* Opsional: Membuat badge lebih estetik dengan sedikit transparansi */
        .badge.bg-blue-gradient {
            padding: 0.5em 0.8em;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="d-flex">
    {{-- ====== SIDEBAR ====== --}}
    <aside class="sidebar d-flex flex-column">
        <div class="brand-wrapper mb-2">
            <h5 class="mb-0 fw-bold text-white d-flex align-items-center" style="letter-spacing: 1px;">
                <i class="bi bi-hexagon-fill text-primary me-2"></i>
                <span>PAPI<span class="text-primary">KOSTICK</span></span>
            </h5>
        </div>

        {{-- Profile Card --}}
        <div class="profile-card">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle">
                    @php $adminName = session('admin_name', Auth::user()->name ?? 'Admin'); @endphp
                    {{ strtoupper(substr($adminName, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-white fw-bold mb-0 text-truncate" style="font-size: 13px;">
                        {{ $adminName }}
                    </p>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-success p-0" style="width: 6px; height: 6px; border-radius: 50%;"></span>
                        <small class="badge p-0" style="font-size: 10px;">Super Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-grow-1 overflow-auto">
            <div class="nav-label">Main Menu</div>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard Overview</span>
            </a>

            @php 
                $pendingCount = 0;
                if (class_exists('\App\Models\AccessRequest')) {
                    $pendingCount = \App\Models\AccessRequest::where('status','pending')->count(); 
                }
            @endphp

            <a href="{{ route('admin.requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i>
                <span>Access Requests</span>
                @if($pendingCount > 0)
                    <span class="badge rounded-pill bg-danger ms-auto" style="font-size: 10px;">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.results.index') }}" class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <span>Test Analytics</span>
            </a>

            <div class="nav-label">Settings</div>
            
            <a href="{{ route('admin.profile') }}" class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="bi bi-shield-lock-fill"></i>
                <span>My Security</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-3">
            <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Yakin ingin keluar?')">
                @csrf
                <button type="submit" class="btn btn-link sidebar-link w-100 text-start border-0 mb-0" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-power"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ====== CONTENT AREA ====== --}}
    <main class="flex-grow-1 d-flex flex-column" style="min-width: 0;">
        
        {{-- Topbar --}}
        <header class="topbar d-flex justify-content-between align-items-center sticky-top">
            <div class="d-flex align-items-center">
                <h6 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h6>
                <div class="vr mx-3 text-muted opacity-25"></div>
                <div class="text-muted d-none d-md-flex align-items-center" style="font-size: 13px;">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                @if($pendingCount > 0)
                <a href="{{ route('admin.requests.index') }}" class="btn-notif position-relative text-decoration-none">
                    <i class="bi bi-bell-fill"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
                @endif

                <div class="d-flex align-items-center gap-2">
                    <div class="text-end d-none d-md-block">
                        <div class="fw-bold" style="font-size: 12px;">System Active</div>
                        <div class="text-muted" style="font-size: 10px;">{{ session('logged_in_at', 'Now') }}</div>
                    </div>
                    <div class="avatar-circle" style="width:32px; height:32px; font-size: 12px;">
                        {{ strtoupper(substr($adminName, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div>{!! session('success') !!}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="footer text-muted d-flex justify-content-between">
            <span>&copy; {{ date('Y') }} <b>Papikostick Admin</b>. All rights reserved.</span>
            <span class="d-none d-md-inline">v2.1.1-stable</span>
        </footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>