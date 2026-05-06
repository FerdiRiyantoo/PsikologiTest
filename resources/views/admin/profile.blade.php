@extends('layouts.admin')
@section('title', 'Profil & Keamanan')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            {{-- Card Informasi Akun --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="bi bi-person-badge-fill fs-5"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Informasi Akun Admin</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4 bg-light bg-opacity-50 border">
                        <div class="avatar-large" style="width: 55px; height: 55px; background: #0f172a; color: #38bdf8; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 20px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</h6>
                            <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 small">
                            <tr>
                                <td class="ps-0 text-muted py-2" width="120">Role Access</td>
                                <td class="fw-semibold py-2"><span class="badge bg-success bg-opacity-10 text-success rounded-pill">Super Administrator</span></td>
                            </tr>
                            <tr>
                                <td class="ps-0 text-muted py-2">Terakhir Login</td>
                                <td class="fw-medium py-2">{{ session('logged_in_at', '-') }}</td>
                            </tr>
                            <tr>
                                <td class="ps-0 text-muted py-2">Status Akun</td>
                                <td class="py-2 text-primary fw-bold"><i class="bi bi-patch-check-fill me-1"></i>Verified</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Card Ganti Password --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 d-flex align-items-center gap-2">
                    <div class="p-2 bg-warning bg-opacity-10 rounded-3 text-warning">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Keamanan & Password</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="text-muted small mb-4">Ubah password Anda secara berkala untuk menjaga keamanan akses dashboard.</p>
                    
                    <form method="POST" action="{{ route('admin.profile.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Lama</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-key text-muted"></i></span>
                                <input type="password" name="current_password" id="oldPass"
                                       class="form-control bg-light border-0 @error('current_password') is-invalid @enderror"
                                       placeholder="Masukkan password saat ini" required style="padding: 10px;">
                                <button type="button" class="btn btn-light border-0 px-3" onclick="togglePass('oldPass','eyeOld')">
                                    <i class="bi bi-eye text-muted" id="eyeOld"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger" style="font-size: 11px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" id="newPass"
                                       class="form-control bg-light border-0 @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required style="padding: 10px;">
                                <button type="button" class="btn btn-light border-0 px-3" onclick="togglePass('newPass','eyeNew')">
                                    <i class="bi bi-eye text-muted" id="eyeNew"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger" style="font-size: 11px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 px-3"><i class="bi bi-lock-check text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="confPass"
                                       class="form-control bg-light border-0"
                                       placeholder="Ulangi password baru" required style="padding: 10px;">
                                <button type="button" class="btn btn-light border-0 px-3" onclick="togglePass('confPass','eyeConf')">
                                    <i class="bi bi-eye text-muted" id="eyeConf"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm rounded-3 py-2">
                            <i class="bi bi-check-circle-fill me-2"></i>Perbarui Keamanan Akun
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye text-muted' : 'bi bi-eye-slash text-muted';
}
</script>
@endpush