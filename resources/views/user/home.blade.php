@extends('layouts.app')
@section('title', 'Form Kandidat Psikotes')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
@section('content')

<div class="modern-form-wrapper">
    <div class="form-container">
        
        <div class="form-header">
            <h1>Lengkapi <span class="gradient-text">Data Diri</span></h1>
            <p class="text-muted">Pastikan informasi yang Anda masukkan sudah <strong>benar</strong> untuk proses verifikasi tes.</p>
        </div>

        {{-- Menampilkan Pesan Error Global jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4" role="alert">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('request.store') }}" id="kandidatForm">
            @csrf
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="main-form-card h-100">
                        <div class="section-title">
                            <div class="icon-box">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3>Data Pribadi</h3>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required>
                                <div class="form-text small text-muted">Usia akan terisi otomatis setelah memilih tanggal.</div>
                                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="tempat_lahir" class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                    class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta" required>
                                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="usia" class="form-label fw-semibold">Usia <span class="text-danger">*</span></label>
                                <input type="number" name="usia" id="usia"
                                    class="form-control @error('usia') is-invalid @enderror"
                                    value="{{ old('usia') }}" min="15" max="99" inputmode="numeric" placeholder="Mis: 25" required readonly>
                                @error('usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="alamat" class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                                <textarea name="alamat" id="alamat" rows="2"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    placeholder="Masukkan alamat lengkap domisili saat ini" required>{{ old('alamat') }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="main-form-card h-100">
                        <div class="section-title">
                            <div class="icon-box">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3>Pendidikan & Karier</h3>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="pendidikan_terakhir" class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                    class="form-select @error('pendidikan_terakhir') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Pilih Tingkat Pendidikan --</option>
                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan_terakhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="jurusan" class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                                <input type="text" name="jurusan" id="jurusan"
                                    class="form-control @error('jurusan') is-invalid @enderror"
                                    value="{{ old('jurusan') }}" placeholder="Contoh: Teknik Informatika" required>
                                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="posisi_yang_dilamar" class="form-label fw-semibold">Posisi yang dilamar <span class="text-danger">*</span></label>
                                <input type="text" name="posisi_yang_dilamar" id="posisi_yang_dilamar"
                                    class="form-control @error('posisi_yang_dilamar') is-invalid @enderror"
                                    value="{{ old('posisi_yang_dilamar') }}" placeholder="Contoh: Staff Administrasi" required>
                                @error('posisi_yang_dilamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="posisi_jabatan_terakhir" class="form-label fw-semibold">Posisi/Jabatan Terakhir <span class="text-danger">*</span></label>
                                <input type="text" name="posisi_jabatan_terakhir" id="posisi_jabatan_terakhir"
                                    class="form-control @error('posisi_jabatan_terakhir') is-invalid @enderror"
                                    value="{{ old('posisi_jabatan_terakhir') }}" placeholder="Isi '-' jika belum ada pengalaman" required>
                                @error('posisi_jabatan_terakhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="phone" class="form-label fw-semibold">Nomor HP / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">+62</span>
                                    <input type="text" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*" placeholder="81234567890" required>
                                </div>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Jadwal & Jenis Tes --}}
            <div class="main-form-card shadow-none border-0 mt-4" style="background-color: #f1f5f9 !important; border-radius: 20px;">
                <div class="row g-4 align-items-end">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-box shadow-sm" style="background: #fff; width: 40px; height: 40px; min-width:40px; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                                <i class="fas fa-clipboard-list text-primary"></i>
                            </div>
                            <h3 class="mb-0" style="font-size: 1.1rem;">Konfigurasi Tes</h3>
                        </div>
                        <p class="small text-muted mb-0">Tentukan instrumen dan waktu pelaksanaan tes Anda.</p>
                    </div>

                    {{-- Pilihan Jenis Tes --}}
                    <div class="col-md-4">
                        <label for="jenis_tes" class="form-label fw-semibold">Instrumen Tes <span class="text-danger">*</span></label>
                        <select name="jenis_tes" id="jenis_tes" class="form-select @error('jenis_tes') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Pilih Jenis Tes --</option>
                            <option value="PapiKostick" {{ old('jenis_tes') == 'PapiKostick' ? 'selected' : '' }}>PAPI-Kostick</option>
                            <option value="Kraepelin" {{ old('jenis_tes') == 'Kraepelin' ? 'selected' : '' }}>Kraepelin</option>
                        </select>
                        @error('jenis_tes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Pilihan Tanggal (Minimal hari ini) --}}
                    <div class="col-md-4">
                        <label for="tanggal_tes" class="form-label fw-semibold">Tanggal Tes <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_tes" id="tanggal_tes"
                            class="form-control @error('tanggal_tes') is-invalid @enderror"
                            value="{{ old('tanggal_tes') }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_tes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center flex-wrap gap-3 mt-5">
                <a href="{{ route('home') }}" class="btn btn-light px-4 py-2 border rounded-pill text-decoration-none shadow-sm fw-semibold" style="color: #475569;">
                    <i class="fas fa-arrow-left me-2"></i> Batalkan
                </a>
                <button type="submit" class="btn-submit-modern px-5 rounded-pill shadow-sm" id="btnSubmit">
                    <span>Daftar Sekarang</span> <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Auto-Calculate Age based on Date of Birth
        const tanggalLahirInput = document.getElementById('tanggal_lahir');
        const usiaInput = document.getElementById('usia');

        if (tanggalLahirInput && usiaInput) {
            tanggalLahirInput.addEventListener('change', function() {
                if (this.value) {
                    const dob = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const m = today.getMonth() - dob.getMonth();
                    
                    // Adjust age if birthday hasn't occurred yet this year
                    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    
                    usiaInput.value = age > 0 ? age : 0;
                    
                    // Hilangkan class error jika tadinya error
                    usiaInput.classList.remove('is-invalid');
                }
            });
        }

        // 2. Prevent Double Submission & Loading State
        const form = document.getElementById('kandidatForm');
        const btnSubmit = document.getElementById('btnSubmit');

        if (form && btnSubmit) {
            form.addEventListener('submit', function() {
                // Pastikan form valid sebelum mengubah tombol
                if(form.checkValidity()) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = 'Memproses... <i class="fas fa-spinner fa-spin ms-2"></i>';
                    btnSubmit.style.opacity = '0.7';
                    btnSubmit.style.cursor = 'not-allowed';
                }
            });
        }
    });
</script>

@endsection