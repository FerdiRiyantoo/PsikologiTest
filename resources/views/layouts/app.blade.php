<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Digitama Consulting - Psikotes PAPI-Kostick')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <script src="{{ asset('js/popup.js') }}"></script>
    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar-custom px-4 px-md-5">
    <div class="container-fluid d-flex align-items-center justify-content-between"
         style="max-width:1280px; margin:0 auto;">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-decoration-none">
            <img src="{{ asset('assets/Digitama.png') }}"
                 class="navbar-logo" alt="Digitama Logo"
                 onerror="this.style.display='none'">
        </a>

        {{-- Desktop Nav --}}
        <div class="d-none d-md-flex align-items-center gap-5">
            <a href="https://digitama.consulting/about/"
               class="nav-link-item"
               target="_blank">
                About Us
            </a>
            <a href="{{ route('panduan') }}"
               class="nav-link-item {{ request()->routeIs('panduan') ? 'active' : '' }}">
                Panduan Tes
            </a>
            <a href="{{ route('register') }}"
               class="nav-link-item {{ request()->routeIs('register') ? 'active' : '' }}">
                Daftar Tes
            </a>
            <a href="{{ route('enter.code') }}"
               class="nav-link-item {{ request()->routeIs('enter.code') ? 'active' : '' }}">
                Masukkan Kode
            </a>
            <a href="{{ route('admin.login') }}" class="btn-nav-login">
                Login
            </a>
        </div>

        {{-- Mobile Hamburger --}}
        <button class="d-md-none btn btn-link text-dark fs-4 p-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mobileMenu"
                aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div class="collapse d-md-none" id="mobileMenu">
        <div class="container-fluid py-3 d-flex flex-column gap-3"
             style="max-width:1280px; margin:0 auto; border-top:1px solid #f3f4f6; margin-top:12px; padding-top:16px;">
            <a href="https://digitama.consulting/about/"
               class="text-dark fw-semibold text-decoration-none"
               style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em;"
               target="_blank">
               About Us
            </a>
            <a href="{{ route('panduan') }}"
               class="fw-semibold text-decoration-none"
               style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em;
               color:{{ request()->routeIs('panduan') ? '#11c6bd' : '#1f2937' }}">
               Panduan Tes
            </a>
            <a href="{{ route('register') }}"
               class="fw-semibold text-decoration-none"
               style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em;
               color:{{ request()->routeIs('register') ? '#11c6bd' : '#1f2937' }}">
               Daftar Tes
            </a>
            <a href="{{ route('enter.code') }}"
               class="fw-semibold text-decoration-none"
               style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em;
               color:{{ request()->routeIs('enter.code') ? '#11c6bd' : '#1f2937' }}">
               Masukkan Kode
            </a>
            <a href="{{ route('admin.login') }}"
               style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.1em;
               color:#fff; background:#11c6bd; padding:8px 16px;
               border-radius:8px; width:fit-content; text-decoration:none; font-weight:700;">
               Login
            </a>
        </div>
    </div>
</nav>

{{-- Alert Session --}}
@if(session('success') || session('error'))
<div class="container py-3" style="max-width:1280px;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>{!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ===== CONTENT ===== --}}
@yield('content')

{{-- ===== FOOTER ===== --}}
<footer class="footer-custom px-4 px-md-5">
    <div class="footer-bg-image">
        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="">
    </div>
    <div class="container-fluid position-relative" style="z-index:10; max-width:1280px; margin:0 auto;">
        <div class="row g-5 mb-5">

            {{-- Info --}}
            <div class="col-12 col-md-4">
                <a href="#" class="text-decoration-none d-inline-block mb-3" style="margin-top: -20px;">
                    <img src="{{ asset('assets/Digitama.png') }}" 
                        alt="Digitama Logo" 
                        class="footer-logo"
                        style="width: 180px; height: auto; object-fit: contain; filter: brightness(0) invert(1);">
                </a>
                <div class="footer-contact">
                    <p>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>DTA Square ( Downtown Area ) Seturan Jl. Seturan Raya No.9a, Kledokan, Caturtunggal, Kec. Depok, Kabupaten Sleman, DIY 55281</span>
                    </p>
                    <p><i class="fas fa-phone"></i><span>+62 821-6000-8085</span></p>
                    <p><i class="fas fa-envelope"></i><span>info@digitama.consulting</span></p>
                </div>
            </div>

            {{-- Links --}}
            <div class="col-12 col-md-4">
                <div class="row">
                    <div class="col-6">
                        <h4 class="footer-heading">Links</h4>
                        <div class="footer-links">
                            <a href="https://digitama.consulting/karir/">Karir</a>
                            <a href="https://digitama.consulting/">SPBE Knowledge</a>
                            <a href="https://digitama.consulting/contact-digitama-consulting/">Contact</a>
                            <a href="https://digitama.consulting/portfolio-digitama-consulting/">Portfolio</a>
                        </div>
                    </div>
                    <div class="col-6 mt-5">
                        <div class="footer-links">
                            <a href="https://digitama.consulting/produk/">Produk</a>
                            <a href="https://digitama.consulting/faq/">FAQ</a>
                            <a href="https://digitama.consulting/download/">Download</a>
                            <a href="https://digitama.consulting/konsultasi-spbe/">Konsultasi SPBE</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="col-12 col-md-4">
                <h4 class="footer-heading">Newsletter</h4>
                <p class="footer-tagline">"Partner in Digital Transformation"</p>
                <div class="d-flex gap-3">
                    <a href="https://www.instagram.com/digitama.consulting/" class="social-btn" target="_blank">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://id.linkedin.com/company/digitama-indonesia" class="social-btn" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://www.youtube.com/@digitamaconsulting2631" class="social-btn" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Digitama Consulting. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>