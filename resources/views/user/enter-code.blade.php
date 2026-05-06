@extends('layouts.app')
@section('title', 'Masukkan Kode Akses')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/enter-code.css') }}">
@endpush
@section('content')


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card digitama-card p-4">
                <div class="card-body text-center">
                    <div class="mb-4 text-primary fs-1">
                        <i class="fas fa-key"></i> 
                    </div>
                    
                    <h3 class="fw-bold mb-3">Kode Akses</h3>
                    <p class="text-muted mb-4">Masukkan kode unik yang Anda terima untuk memulai sesi psikotes.</p>
                    
                    <form method="POST" action="{{ route('verify.code') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="access_code"
                                class="form-control form-control-lg text-center text-uppercase fw-bold custom-input-code @error('access_code') is-invalid @enderror"
                                placeholder="CONTOH: AB12CD" maxlength="10" required>
                            @error('access_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-digitama w-100 btn-lg shadow-sm">
                            Mulai Tes Sekarang
                        </button>
                    </form>
                    
                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection