@extends('layouts.app')
@section('title', 'Panduan Tes Psikotes')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="container py-5 animate_animated animate_fadeIn" style="max-width:900px">

    {{-- Hero Header --}}
    <div class="text-center mb-5">
        <span class="badge bg-opacity-10 px-4 py-2 rounded-pill mb-3 fw-bold shadow-sm" style="letter-spacing: 1px; color: #11c6bd; background-color: rgba(17, 198, 189, 0.1);"> INSTRUCTIONAL GUIDE</span>
        <h1 class="fw-800 display-5 mb-2" style="color: #0f172a;">Panduan Pelaksanaan <span style="color: #11c6bd;">Psikotes</span></h1>
        <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">Pahami instruksi pengerjaan setiap modul tes untuk mendapatkan hasil yang optimal.</p>
    </div>

    {{-- Navigasi Tab --}}
    <ul class="nav nav-pills justify-content-center mb-5 gap-2" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-bold shadow-sm" id="pills-papi-tab" data-bs-toggle="pill" data-bs-target="#pills-papi" type="button" role="tab">PAPI-Kostick</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold shadow-sm" id="pills-kraepelin-tab" data-bs-toggle="pill" data-bs-target="#pills-kraepelin" type="button" role="tab">Tes Kraepelin</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        {{-- MODUL PAPI-KOSTICK --}}
        <div class="tab-pane fade show active" id="pills-papi" role="tabpanel">
            {{-- Section 1: Pengenalan --}}
            <div class="card border-0 shadow-sm mb-4 overflow-hidden rounded-4 transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number bg-primary text-white shadow-sm">1</div>
                        <h4 class="fw-bold mb-0 text-dark">Apa itu PAPI-Kostick?</h4>
                    </div>
                    <p class="text-muted mb-0 fs-6" style="line-height:1.8; text-align: justify;">
                        <strong>PAPI-Kostick</strong> (Personality and Preference Inventory) merupakan instrumen psikologi mutakhir yang dirancang untuk mengukur <strong>20 aspek kepribadian</strong> dasar dalam lingkungan kerja. Hasil dari tes ini membantu perusahaan memahami gaya kepemimpinan, cara bersosialisasi, hingga tingkat ketelitian Anda dalam menyelesaikan tugas profesional.
                    </p>
                </div>
            </div>

            {{-- Section 2: Instruksi Pengerjaan --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                        <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:32px;height:32px;font-size:14px">2</span>
                        Cara Mengerjakan Tes
                    </h5>
                    <div class="row g-3">
                        @foreach([
                            ['bi-1-circle','Dalam Lembar ini terdapat 90 pertanyaan. (Tidak ada batasan waktu)'],
                            ['bi-2-circle','Semua pilihan dalam lembar ini bukanlah bersifat <strong>BENAR</strong> atau <strong>SALAH</strong>, jadi <strong>TIDAK ADA JAWABAN YANG SALAH</strong>'],
                            ['bi-3-circle','Anda harus memilih dengan memberi jawaban a atau b dari dua pernyataan yang terdapat dalam 90 pernyataan tersebut.'],
                            ['bi-4-circle','Pernyataan yang dimaksud adalah pernyataan paling dominant atau paling mencerminkan diri anda atau menggambarkan perasaan anda saat ini.'],
                            ['bi-5-circle','Terkadang anda akan menemukan pernyataan yang keduanya tidak mencerminkan atau anda tidak sesuai dengan kedua pernyataan tersebut, dalam hal ini <strong>anda tetap harus memilih salah satu pernyataan tersebut yang paling mencermikan diri anda</strong>.'],
                            ['bi-6-circle','Dalam kasus yang lain anda akan menemukan pernyataan yang keduanya mencerminkan diri anda, dalam <strong>hal ini anda harus tetap memilih salah satu dari kedua pernytaan tersebut yang paling mencerminkan diri anda.</strong>'],
                            ['bi-7-circle','Cara menjawabnya anda harus memberikan pernyataan a atau b dari setiap nomor yang terdapat pada pernyataan.'],
                        ] as $item)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 p-3 bg-light bg-opacity-50 rounded-4 border border-white shadow-sm transition-all">
                                <i class="bi {{ $item[0] }} text-primary fs-5 flex-shrink-0 mt-1"></i>
                                <span style="font-size:14px; color:#334155;">{!! $item[1] !!}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section 3: Tips Optimal (Dipindahkan ke sini) --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4 transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number bg-warning text-dark shadow-sm">3</div>
                        <h4 class="fw-bold mb-0 text-dark">Tips Hasil Maksimal</h4>
                    </div>
                    <div class="row g-3">
                        @foreach([
                            ['bi-lightning-charge','Koneksi Stabil','Pastikan internet Anda lancar.'],
                            ['bi-volume-mute','Lingkungan Tenang','Cari tempat yang minim distraksi.'],
                            ['bi-laptop','Gunakan Desktop','PC/Laptop memberikan visual lebih luas.'],
                            ['bi-cup-hot','Kondisi Prima','Pastikan fisik dan mental Anda siap.'],
                        ] as $tip)
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="width:50px; height:50px;">
                                    <i class="bi {{ $tip[0] }} text-warning fs-4"></i>
                                </div>
                                <div class="fw-bold text-dark small">{{ $tip[1] }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $tip[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section 4: 20 Skala --}}
            <div class="card border-0 shadow-sm mb-5 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pt-5 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="step-number bg-success text-white shadow-sm">4</div>
                        <h4 class="fw-bold mb-0 text-dark">Indikator yang Dievaluasi</h4>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2">
                        @foreach([
                            ['N','Need to Finish Task','Work Direction'], ['G','Hard Intense Worker','Work Direction'],
                            ['A','Need for Achievement','Work Direction'], ['L','Need for Change','Work Direction'],
                            ['P','Need to Control Others','Leadership'], ['I','Ease in Decision Making','Leadership'],
                            ['C','Leadership Role','Leadership'], ['T','Work Pace','Activity'],
                            ['V','Vigorous Type','Activity'], ['X','Need to be Noticed','Social Nature'],
                            ['S','Social Extension','Social Nature'], ['B','Need to Belong','Social Nature'],
                            ['O','Need for Closeness','Social Nature'], ['R','Theoretical Type','Work Style'],
                            ['D','Interest in Details','Work Style'], ['F','Support Authority','Followership'],
                            ['W','Need for Rules','Followership'], ['E','Emotional Resistant','Temperament'],
                            ['K','Need to be Forceful','Temperament'], ['Z','Need for Change','Temperament'],
                        ] as $s)
                        <div class="col-6 col-md-3">
                            <div class="scale-card p-2 border rounded-3 d-flex align-items-center gap-2 bg-white transition-all shadow-hover">
                                <div class="scale-badge">{{ $s[0] }}</div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size:10px;">{{ $s[1] }}</div>
                                    <div class="text-muted" style="font-size:9px; letter-spacing: 0.5px;">{{ $s[2] }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- MODUL KRAEPELIN --}}
        <div class="tab-pane fade" id="pills-kraepelin" role="tabpanel">
            {{-- Section 1: Pengenalan Kraepelin --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4 transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number text-white shadow-sm" style="background-color: #11c6bd;">1</div>
                        <h4 class="fw-bold mb-0 text-dark">Apa itu Tes Kraepelin?</h4>
                    </div>
                    <p class="text-muted mb-0 fs-6" style="line-height:1.8; text-align: justify;">
                        <strong>Tes Kraepelin</strong> digunakan untuk mengukur <strong>kecepatan, ketelitian, keajegan, dan ketahanan</strong> kerja melalui penjumlahan angka.
                    </p>
                </div>
            </div>

            {{-- Section 2: Instruksi Pengerjaan Kraepelin --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4 transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2 text-dark">
                        <span class="text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width:32px;height:32px;font-size:14px; background-color: #11c6bd;">2</span>
                        Cara Mengerjakan Tes Kraepelin
                    </h5>
                    <div class="row g-3">
                        @foreach([
                            ['bi-plus-circle','Jumlahkan angka dari <strong>BAWAH ke ATAS</strong>.'],
                            ['bi-input-cursor','Tulis angka terakhir saja (Contoh: 9+7=16, tulis <strong>6</strong>).'],
                            ['bi-alarm','Pindah ke kolom baru saat waktu habis atau ada instruksi.'],
                        ] as $item)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 p-3 bg-light bg-opacity-50 rounded-4 border border-white shadow-sm transition-all">
                                <i class="bi {{ $item[0] }} fs-5 mt-1" style="color: #11c6bd;"></i>
                                <span style="font-size:14px; color:#334155;">{!! $item[1] !!}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section 3: Tips Optimal (Dipindahkan ke sini) --}}
            <div class="card border-0 shadow-sm mb-4 rounded-4 transition-all hover-lift">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="step-number bg-warning text-dark shadow-sm">3</div>
                        <h4 class="fw-bold mb-0 text-dark">Tips Hasil Maksimal</h4>
                    </div>
                    <div class="row g-3">
                        @foreach([
                            ['bi-lightning-charge','Koneksi Stabil','Pastikan internet Anda lancar.'],
                            ['bi-volume-mute','Lingkungan Tenang','Cari tempat yang minim distraksi.'],
                            ['bi-laptop','Gunakan Desktop','PC/Laptop memberikan visual lebih luas.'],
                            ['bi-cup-hot','Kondisi Prima','Pastikan fisik dan mental Anda siap.'],
                        ] as $tip)
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="width:50px; height:50px;">
                                    <i class="bi {{ $tip[0] }} text-warning fs-4"></i>
                                </div>
                                <div class="fw-bold text-dark small">{{ $tip[1] }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $tip[2] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- CTA Actions --}}
    <div class="row g-3 justify-content-center">
        <div class="col-md-5">
            @if(isset($testSession))
                {{-- Tombol ini muncul jika user sudah memasukkan kode (melalui TestController) --}}
                @php
                    $jenis = strtolower($testSession->accessRequest->jenis_tes);
                    $route = ($jenis === 'kraepelin') ? route('kraepelin.index') : route('test.index');
                @endphp
                <a href="{{ $route }}" class="btn btn-primary btn-lg w-100 py-3 rounded-4 fw-bold shadow-primary hover-lift">
                    <i class="bi bi-play-fill me-2"></i> Konfirmasi & Mulai Tes
                </a>
            @else
                {{-- Tombol ini muncul jika diakses dari Navbar (Panduan Umum) --}}
                <a href="{{ route('enter.code') }}" class="btn-submit-modern">
                    <i class="bi bi-key-fill me-2"></i> Masukkan Kode Untuk Memulai
                </a>
            @endif
        </div>
        <div class="col-md-5">
            <a href="{{ route('register') }}" class="btn-submit-modern">
                <i class="bi bi-pencil-square me-2"></i> Daftar Tes Sekarang
            </a>
        </div>
    </div>
</div>
@endsection