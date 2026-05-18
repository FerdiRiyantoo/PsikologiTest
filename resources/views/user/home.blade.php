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
            <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('request.store') }}">
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
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-12">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-md-8">
                            <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir"
                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                value="{{ old('tempat_lahir') }}" required>
                            @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Usia <span class="text-danger">*</span></label>
                            <input type="number" name="usia"
                                class="form-control @error('usia') is-invalid @enderror"
                                value="{{ old('usia') }}" min="15" max="99" required>
                            @error('usia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            
                            <div class="col-12">
                            <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2"
                                class="form-control @error('alamat') is-invalid @enderror"
                                required>{{ old('alamat') }}</textarea>
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
                            <label class="form-label fw-semibold">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <select name="pendidikan_terakhir"
                                class="form-select @error('pendidikan_terakhir') is-invalid @enderror" required>
                                <option value="">-- Pilih --</option>
                                <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                            @error('pendidikan_terakhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-12">
                            <label class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan"
                                class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan') }}" required>
                            @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-12">
                            <label class="form-label fw-semibold">Posisi yang dilamar <span class="text-danger">*</span></label>
                            <input type="text" name="posisi_yang_dilamar"
                                class="form-control @error('posisi_yang_dilamar') is-invalid @enderror"
                                value="{{ old('posisi_yang_dilamar') }}" required>
                            @error('posisi_yang_dilamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-12">
                            <label class="form-label fw-semibold">Posisi/Jabatan Terakhir <span class="text-danger">*</span></label>
                            <input type="text" name="posisi_jabatan_terakhir"
                                class="form-control @error('posisi_jabatan_terakhir') is-invalid @enderror"
                                value="{{ old('posisi_jabatan_terakhir') }}" required>
                            @error('posisi_jabatan_terakhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                            <div class="col-12">
                            <label class="form-label fw-semibold">Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required>
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
                            <div class="icon-box" style="background: #fff; width: 40px; height: 40px; min-width:40px;">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h3 class="mb-0" style="font-size: 1.1rem;">Konfigurasi Tes</h3>
                        </div>
                        <p class="small text-muted mb-0">Tentukan instrumen dan waktu pelaksanaan tes Anda.</p>
                    </div>

                    {{-- Pilihan Jenis Tes (Disesuaikan dengan Database) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Instrumen Tes <span class="text-danger">*</span></label>
                        <select name="jenis_tes" class="form-select @error('jenis_tes') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Pilih Tes --</option>
                            <option value="PapiKostick" {{ old('jenis_tes') == 'PapiKostick' ? 'selected' : '' }}>PAPI-Kostick</option>
                            <option value="Kraepelin" {{ old('jenis_tes') == 'Kraepelin' ? 'selected' : '' }}>Kraepelin</option>
                        </select>
                        @error('jenis_tes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Pilihan Tanggal --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Tes <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_tes"
                            class="form-control @error('tanggal_tes') is-invalid @enderror"
                            value="{{ old('tanggal_tes') }}" required>
                        @error('tanggal_tes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center flex-wrap gap-3 mt-5">
                <a href="{{ route('home') }}" class="btn-submit-modern btn-danger-custom text-white text-decoration-none">
                    Batalkan dan Kembali
                </a>
                <button type="submit" class="btn-submit-modern">
                    Daftar Sekarang <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection