@extends('layouts.admin')
@section('title', 'Detail Hasil Tes Psikotes')

@section('content')
<div class="container-fluid p-0">
    
    {{-- Header & Tombol Kembali --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Hasil Ujian</h4>
            <p class="text-muted small mb-0">Detail performa kognitif dan kepribadian kandidat.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-white border shadow-sm rounded-3">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    {{-- Kartu Profil Kandidat --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-auto mb-3 mb-md-0">
                    <div class="avatar-lg bg-primary bg-opacity-10 text-primary fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width:80px; height:80px; font-size: 32px;">
                        {{ strtoupper(substr($session->accessRequest->name, 0, 1)) }}
                    </div>
                </div>
                <div class="col-md">
                    <h4 class="fw-bold mb-1">{{ $session->accessRequest->name }}</h4>
                    <p class="text-muted mb-2"><i class="bi bi-envelope me-2"></i>{{ $session->accessRequest->email }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2"><i class="bi bi-briefcase me-2"></i>Posisi: {{ $session->accessRequest->posisi_yang_dilamar ?? '-' }}</span>
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2"><i class="bi bi-journal-text me-2"></i>Tes: {{ $session->accessRequest->jenis_tes }}</span>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                            <i class="bi bi-calendar-check me-2"></i>Selesai: 
                            {{ $session->completed_at ? \Carbon\Carbon::parse($session->completed_at)->translatedFormat('d F Y, H:i') : 'Belum Selesai' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KONDISI 1: JIKA TES KRAEPELIN --}}
    @if($session->accessRequest->jenis_tes === 'Kraepelin')
        {{-- DISESUAIKAN: Menggunakan resultKreapelin --}}
        @if($session->resultKreapelin)
            <div class="row g-4 mb-4">
                <!-- Pace (Kecepatan) -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-primary border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-2"><i class="bi bi-speedometer2 fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Kecepatan (Pace)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->resultKreapelin->pace_score }}</h2>
                            <small class="text-muted">Rata-rata per lajur</small>
                        </div>
                    </div>
                </div>
                <!-- Accuracy (Ketelitian) -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-success border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-success mb-2"><i class="bi bi-check2-all fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Ketelitian (Accuracy)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->resultKreapelin->accuracy_score }}%</h2>
                            <small class="text-muted">{{ $session->resultKreapelin->total_correct }} benar dari {{ $session->resultKreapelin->total_answered }} soal</small>
                        </div>
                    </div>
                </div>
                <!-- Endurance (Ketahanan) -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-warning border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-warning mb-2"><i class="bi bi-battery-half fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Ketahanan (Endurance)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->resultKreapelin->endurance_score }}</h2>
                            <small class="text-muted">Tren paruh kedua vs pertama</small>
                        </div>
                    </div>
                </div>
                <!-- Stability (Kestabilan) -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-danger border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-danger mb-2"><i class="bi bi-activity fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Kestabilan (Stability)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->resultKreapelin->stability_score }}</h2>
                            <small class="text-muted">Simpangan baku (Makin kecil = Stabil)</small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning rounded-4 border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Hasil perhitungan Kraepelin untuk kandidat ini belum tersedia atau terjadi kesalahan saat kalkulasi.
            </div>
        @endif

    {{-- KONDISI 2: JIKA TES PAPI-KOSTICK --}}
    @elseif($session->accessRequest->jenis_tes === 'PapiKostick')
        {{-- DISESUAIKAN: Menggunakan resultPapi --}}
        @if($session->resultPapi)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Rincian Skor Skala Kepribadian</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        {{-- Kolom Kiri: Peran Pekerja (Roles) --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Peran (Roles)</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">G - Peran Pekerja Keras <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_g }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">L - Peran Kepemimpinan <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_l }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">I - Peran Membuat Keputusan <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_i }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">T - Peran Sibuk <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_t }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">V - Peran Penuh Semangat <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_v }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">S - Peran Hubungan Sosial <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_s }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">R - Peran Teoritis <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_r }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">D - Peran Bekerja dengan Hal Detail <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_d }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">C - Peran Mengatur <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_c }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">E - Peran Pengendalian Emosi <span class="badge bg-primary rounded-pill">{{ $session->resultPapi->scale_e }}</span></li>
                            </ul>
                        </div>
                        {{-- Kolom Kanan: Kebutuhan (Needs) --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3 border-bottom pb-2">Kebutuhan (Needs)</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">N - Kebutuhan Menyelesaikan Tugas <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_n }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">A - Kebutuhan Berprestasi <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_a }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">P - Kebutuhan Mengatur Orang Lain <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_p }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">X - Kebutuhan Diperhatikan <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_x }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">B - Kebutuhan Diterima dalam Kelompok <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_b }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">O - Kebutuhan Kedekatan & Kasih Sayang <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_o }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">Z - Kebutuhan Berubah (Fleksibilitas) <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_z }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">K - Kebutuhan Menjadi Agresif <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_k }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">F - Kebutuhan Membantu Atasan <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_f }}</span></li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">W - Kebutuhan Aturan & Pengarahan <span class="badge bg-success rounded-pill">{{ $session->resultPapi->scale_w }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning rounded-4 border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Hasil perhitungan PAPI-Kostick untuk kandidat ini belum tersedia.
            </div>
        @endif
    @else
        <div class="alert alert-secondary rounded-4 border-0 shadow-sm text-center py-4">
            Jenis tes tidak dikenali oleh sistem.
        </div>
    @endif
</div>
@endsection