@extends('layouts.app')
@section('title', 'Panduan Tes PAPI-Kostick')
@section('content')
<div class="container py-5" style="max-width:860px">

    <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Panduan Lengkap</span>
        <h2 class="fw-bold">Panduan Tes PAPI-Kostick</h2>
        <p class="text-muted">Baca panduan berikut sebelum mengerjakan tes untuk hasil yang optimal</p>
    </div>

    {{-- Apa itu PAPI --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:14px">1</span>
                Apa itu PAPI-Kostick?
            </h5>
            <p class="text-muted mb-0" style="line-height:1.8">
                <strong>PAPI-Kostick</strong> (Personality and Preference Inventory) adalah instrumen psikologi
                yang mengukur <strong>20 skala kepribadian</strong> yang berkaitan dengan perilaku kerja seseorang.
                Tes ini digunakan secara luas dalam proses seleksi karyawan, pengembangan SDM, dan pemetaan potensi individu
                di berbagai perusahaan di seluruh dunia.
            </p>
        </div>
    </div>

    {{-- Cara Mengerjakan --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:14px">2</span>
                Cara Mengerjakan Tes
            </h5>
            <div class="row g-3">
                @foreach([
                    ['bi-1-circle','Dalam Lembar ini terdapat 90 pertanyaan. (Tidak ada batasan waktu)'],
                    ['bi-2-circle','Semua pilihan dalam lembar ini bukanlah bersifat <strong>BENAR</strong> atau <strong>SALAH</strong>, jadi <strong>TIDAK ADA JAWABAN YANG SALAH</strong>'],
                    ['bi-3-circle','Anda harus memilih dengan milingkari memberi jawaban a atau b dari dua pernyataan yang terdapat dalam 90 pernyataan tersebut.'],
                    ['bi-4-circle','Pernyataan yang dimaksud adalah pernyataan paling dominant atau paling mencerminkan diri anda atau menggambarkan perasaan anda saat ini.'],
                    ['bi-5-circle','Terkadang anda akan menemukan pernyataan yang keduanya tidak mencerminkan atau anda tidak sesuai dengan kedua pernyataan tersebut, dalam hal ini <strong>anda tetap harus memilih salah satu pernyataan tersebut yang paling mencermikan diri anda</strong>.'],
                    ['bi-6-circle','Dalam kasus yang lain anda akan menemukan pernyataan yang keduanya mencerminkan diri anda, dalam <strong>hal ini anda harus tetap memilih salah satu dari kedua pernytaan tersebut yang paling mencerminkan diri anda.</strong>'],
                    ['bi-7-circle','Cara menjawabnya anda harus memberikan pernyataan a atau b dari setiap nomor yang terdapat pada pernyataan.'],
                    
                ] as $item)
                <div class="col-12">
                    <div class="d-flex align-items-start gap-3 p-3 bg-light rounded">
                        <i class="bi {{ $item[0] }} text-primary fs-5 flex-shrink-0 mt-1"></i>
                        <span style="font-size:14px; color:#374151">{!! $item[1] !!}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tips --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:14px">3</span>
                Tips untuk Hasil Optimal
            </h5>
            <div class="row g-3">
                @foreach([
                    ['bi-wifi','Pastikan koneksi internet stabil selama mengerjakan tes'],
                    ['bi-clock','Pilih waktu yang tenang, hindari gangguan dari luar'],
                    ['bi-phone-landscape','Gunakan laptop/komputer untuk pengalaman terbaik'],
                    ['bi-heart-pulse','Kerjakan dalam kondisi fisik dan mental yang baik'],
                    ['bi-stopwatch','Tidak ada batas waktu, namun usahakan diselesaikan dalam satu sesi'],
                ] as $tip)
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 p-3 border rounded">
                        <i class="bi {{ $tip[0] }} text-success"></i>
                        <span style="font-size:13px">{{ $tip[1] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 20 Skala --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:14px">4</span>
                20 Skala yang Diukur
            </h5>
            <div class="row g-2">
                @foreach([
                    ['N','Need to Finish Task','Work Direction'],
                    ['G','Hard Intense Worker','Work Direction'],
                    ['A','Need for Achievement','Work Direction'],
                    ['L','Need for Change','Work Direction'],
                    ['P','Need to Control Others','Leadership'],
                    ['I','Ease in Decision Making','Leadership'],
                    ['C','Leadership Role','Leadership'],
                    ['T','Work Pace','Activity'],
                    ['V','Vigorous Type','Activity'],
                    ['X','Need to be Noticed','Social Nature'],
                    ['S','Social Extension','Social Nature'],
                    ['B','Need to Belong','Social Nature'],
                    ['O','Need for Closeness','Social Nature'],
                    ['R','Theoretical Type','Work Style'],
                    ['D','Interest in Details','Work Style'],
                    ['F','Need to Support Authority','Followership'],
                    ['W','Need for Rules','Followership'],
                    ['E','Emotional Resistant','Temperament'],
                    ['K','Need to be Forceful','Temperament'],
                    ['Z','Need for Change','Temperament'],
                ] as $s)
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-2 p-2 border rounded">
                        <div style="width:28px;height:28px;border-radius:6px;background:linear-gradient(135deg,#0d6efd,#06b6d4);color:white;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            {{ $s[0] }}
                        </div>
                        <div>
                            <div style="font-size:11px;font-weight:600;line-height:1.2">{{ $s[1] }}</div>
                            <div style="font-size:10px;color:#9ca3af">{{ $s[2] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center mt-4">
        <a href="{{ route('enter.code') }}" class="btn btn-primary btn-lg px-5 me-2">
            <i class="bi bi-key me-1"></i> Masukkan Kode Akses
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5">
            <i class="bi bi-pencil-square me-1"></i> Daftar Tes
        </a>
    </div>

</div>
@endsection