<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Tes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1rem; /* Tambahan padding untuk mobile */
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            overflow: hidden;
            width: 100%;
        }

        /* Branding Side (Kanan) */
        .branding-side {
            background: linear-gradient(135deg, #11c6bd 0%, #019192 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 3rem;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* RESPONSIVE FIXES */
        @media (max-width: 767.98px) {
            .branding-side {
                padding: 2rem 1rem;
                order: -1; /* Pindahkan logo ke atas pada mobile */
            }
            .brand-logo {
                width: 60px;
                height: 60px;
                margin-bottom: 0.5rem;
            }
            .branding-side h3 {
                font-size: 1.5rem;
            }
            .form-container {
                padding: 2rem !important; /* Kurangi padding p-5 pada mobile */
            }
        }

        .form-label {
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
            color: #94a3b8;
            padding-left: 1.25rem;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            background-color: #f8fafc;
            border-left: none;
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            border-radius: 0 12px 12px 0;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            background-color: #fff;
            box-shadow: none;
            border-color: #e2e8f0;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .btn-eye {
            border-color: #11c6bd;
            background-color: #fff;
        }

        .btn-eye {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: none;
            color: #94a3b8;
            border-radius: 0 12px 12px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, #11c6bd 0%, #019192 100%);
            border: none;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px rgba(17, 198, 189, 0.2);
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(17, 198, 189, 0.3);
            filter: brightness(1.1);
        }

        .alert {
            border-radius: 12px;
            font-size: 0.85rem;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card login-card border-0">
                <div class="row g-0">
                    
                    {{-- SISI KIRI (Form) --}}
                    <div class="col-md-7 p-5 form-container">
                        <div class="mb-4">
                            <h4 class="fw-800 text-dark mb-1">Selamat Datang Kembali</h4>
                            <p class="text-muted small">Silakan login untuk mengelola dashboard admin.</p>
                        </div>

                        {{-- Alert Messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center mb-4 shadow-sm">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login.post') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Alamat Email</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="admin@digitama.com" required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1" style="font-size: 11px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Kata Sandi</label>
                                <div class="input-group shadow-sm rounded-3">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" name="password" id="passwordInput" class="form-control border-end-0" placeholder="••••••••" required>
                                    <button type="button" class="btn btn-eye px-3" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4 d-flex align-items-center">
                                <input type="checkbox" name="remember" class="form-check-input me-2" id="rememberMe" style="width: 18px; height: 18px; margin-top: 0;">
                                <label class="form-check-label text-muted small" for="rememberMe">
                                    Ingat saya di perangkat ini
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                                Masuk Sekarang <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
                            </button>
                        </form>
                        
                        <div class="mt-4 pt-2 border-top d-none d-sm-block">
                            <small class="text-muted d-flex align-items-center">
                                <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
                                Sistem Keamanan Terenkripsi SSL
                            </small>
                        </div>
                    </div>

                    {{-- SISI KANAN (Logo) --}}
                    {{-- d-md-flex memastikan dia menyamping di layar laptop, d-flex memastikan dia muncul di mobile (tanpa d-none) --}}
                    <div class="col-md-5 branding-side text-center d-flex">
                        <div class="brand-logo">
                            <i class="bi bi-cpu-fill" style="font-size:2.5rem"></i>
                        </div>
                        <h3 class="fw-800 mb-2">Digikom</h3>
                        <p class="text-white text-opacity-75 small px-4 d-none d-md-block">Mastering the art of connection in a world defined by pixels and data.</p>
                        
                        <div class="mt-4 small text-white text-opacity-50">
                            &copy; {{ date('Y') }} PT Digitama
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>