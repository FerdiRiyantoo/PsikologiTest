@extends('layouts.app')
@section('title', 'Digitama Consulting - Psikotes PAPI-Kostick')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endpush

@section('content')
<main class="hero-section px-4 px-md-5">
    <div class="container-fluid" style="max-width:1280px; margin:0 auto;">
        <div class="row align-items-center g-5">

            {{-- Kiri --}}
            <div class="col-12 col-md-6" style="position:relative; z-index:10;">
                <p class="hero-tag">Psikotes Resmi Digitama Consulting</p>
                <h1 class="hero-title">
                    Kenali Potensi Diri <br>
                    <span class="gradient-text">PAPI-Kostick</span>
                </h1>
                <p class="hero-desc">
                    Tes kepribadian berbasis ilmu psikologi yang mengukur
                    <strong>20 skala karakter kerja</strong>.
                    Digunakan untuk seleksi karyawan, pengembangan SDM, dan pemetaan potensi individu
                    bersama <strong>DIGITAMA Consulting</strong>.
                </p>

                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('register') }}" class="btn-cyan">
                        <i class="fas fa-pencil-alt me-2"></i>Daftar Tes Sekarang
                    </a>
                    <a href="{{ route('enter.code') }}" class="btn-outline-gray">
                        <i class="fas fa-key me-2"></i>Punya Kode Akses
                    </a>
                </div>

                <div>
                    <h3 class="why-title">Mengapa PAPI-Kostick?</h3>
                    <ul class="why-list">
                        <li>
                            <span class="icon-badge"><i class="fas fa-shield-alt"></i></span>
                            <div>
                                <span class="item-title">Instrumen Terstandarisasi</span>
                                <span class="item-desc">Diakui secara internasional dalam bidang psikologi industri dan organisasi.</span>
                            </div>
                        </li>
                        <li>
                            <span class="icon-badge"><i class="fas fa-bolt"></i></span>
                            <div>
                                <span class="item-title">Cepat & Mudah Dikerjakan</span>
                                <span class="item-desc">Diselesaikan dalam ±45 menit dengan 90 soal pilihan yang mudah dipahami.</span>
                            </div>
                        </li>
                        <li>
                            <span class="icon-badge"><i class="fas fa-chart-bar"></i></span>
                            <div>
                                <span class="item-title">20 Skala Kepribadian</span>
                                <span class="item-desc">Mengukur aspek leadership, followership, activity, work style, dan temperament.</span>
                            </div>
                        </li>
                        <li>
                            <span class="icon-badge"><i class="fas fa-lock"></i></span>
                            <div>
                                <span class="item-title">Akses Terverifikasi</span>
                                <span class="item-desc">Kode akses diberikan setelah persetujuan admin untuk menjaga validitas hasil.</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Kanan --}}
            <div class="col-12 col-md-6">
                <div class="hero-image-wrapper">
                    <div class="bg-abstract">
                        <img src="{{ asset('assets/background-abstrak.png') }}" alt=""
                             onerror="this.style.display='none'">
                    </div>
                    <div class="bg-abstract-1">
                        <img src="{{ asset('assets/background-abstrak1.png') }}" alt=""
                             onerror="this.style.display='none'">
                    </div>
                    <div class="phone-card-main">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop"
                             alt="Tim Digitama">
                    </div>
                    <div class="phone-card-secondary-wrapper">
                        <div class="phone-card-secondary">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=2070&auto=format&fit=crop"
                                 alt="Psikotes">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection