<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Papikostick</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-secondary d-flex align-items-center justify-content-center" style="min-height:100vh">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    {{-- Logo / Header --}}
                    <div class="text-center mb-4">
                        <i class="bi bi-clipboard-data text-primary" style="font-size:2.5rem"></i>
                        <h5 class="fw-bold mt-2 mb-0">Admin Papikostick</h5>
                        <small class="text-muted">Masuk ke panel administrasi</small>
                    </div>

                    {{-- Alert --}}
                    @if(session('error'))
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success py-2">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        </div>
                    @endif

                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('admin.login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="admin@email.com"
                                       required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       name="password"
                                       id="passwordInput"
                                       class="form-control"
                                       placeholder="••••••••"
                                       required>
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword()">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                            <label class="form-check-label text-muted small" for="rememberMe">
                                Ingat saya di perangkat ini
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </button>
                    </form>
                </div>

                {{-- Footer info --}}
                <div class="card-footer text-center bg-light py-2">
                    <small class="text-muted">
                        <i class="bi bi-shield-lock me-1"></i>
                        Akses terbatas untuk administrator
                    </small>
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