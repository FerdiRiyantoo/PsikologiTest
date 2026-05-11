@extends('layouts.admin')
@section('title', 'Detail Analisis Psikotes')

@section('content')
{{-- Gunakan wrapper dengan min-width 0 untuk mencegah desakan pada flexbox sidebar --}}
<div class="container-fluid p-0" style="min-width: 0;">
    
    {{-- Header Action Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.results.index') }}" class="btn btn-white shadow-sm rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold mb-0 text-dark">Laporan Psikotes</h4>
        </div>
        <a href="{{ route('admin.results.pdf', $session->id) }}" class="btn btn-danger shadow-sm rounded-3 px-4" target="_blank">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
        </a>
    </div>

    {{-- Info Peserta Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-large">
                    {{ strtoupper(substr($session->accessRequest->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $session->accessRequest->name }}</h5>
                    <p class="text-muted mb-0 small">Kandidat ID: #{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Assessment Completed
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body bg-light bg-opacity-50 p-4 border-top">
            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Kontak & Posisi</small>
                    <div class="fw-semibold text-dark">{{ $session->accessRequest->email }}</div>
                    <div class="text-muted small">Posisi: {{ $session->accessRequest->posisi_yang_dilamar ?? '-' }}</div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Waktu Penyelesaian</small>
                    <div class="fw-semibold text-dark">{{ $session->completed_at?->isoFormat('D MMMM Y, HH:mm') ?? '-' }}</div>
                    <div class="text-muted small">Tes: <span class="fw-bold text-info">{{ $session->accessRequest->jenis_tes }}</span></div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Total Durasi</small>
                    <div class="fw-semibold text-primary fs-5">
                        <i class="bi bi-stopwatch me-1"></i>
                        @if($session->started_at && $session->completed_at)
                            @php
                                $totalSeconds = $session->started_at->diffInSeconds($session->completed_at);
                                $decimalDuration = number_format($totalSeconds / 60, 1);
                            @endphp
                            {{ $decimalDuration }} <span class="small text-muted">Menit</span>
                        @else 
                            - 
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KONDISI 1: JIKA TES KRAEPELIN --}}
    @if($session->accessRequest->jenis_tes === 'Kraepelin')
        @if($session->kraepelinResult)
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-primary border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-2"><i class="bi bi-speedometer2 fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Kecepatan (Pace)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->kraepelinResult->pace_score }}</h2>
                            <small class="text-muted">Rata-rata per lajur</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-success border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-success mb-2"><i class="bi bi-check2-all fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Ketelitian (Accuracy)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->kraepelinResult->accuracy_score }}%</h2>
                            <small class="text-muted">{{ $session->kraepelinResult->total_correct }} benar dari {{ $session->kraepelinResult->total_answered }} soal</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-warning border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-warning mb-2"><i class="bi bi-battery-half fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Ketahanan (Endurance)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->kraepelinResult->endurance_score }}</h2>
                            <small class="text-muted">Tren paruh kedua vs pertama</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-danger border-4">
                        <div class="card-body text-center p-4">
                            <div class="text-danger mb-2"><i class="bi bi-activity fs-1"></i></div>
                            <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 12px;">Kestabilan (Stability)</h6>
                            <h2 class="fw-bold mb-0">{{ $session->kraepelinResult->stability_score }}</h2>
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

    {{-- KONDISI 2: JIKA TES PAPI-KOSTICK (MENGGUNAKAN STRUKTUR VERSI AWAL) --}}
    @elseif($session->accessRequest->jenis_tes === 'PapiKostick')
        @if($session->papiResult)
            @php $categories = $session->papiResult->getCategorizedScales(); @endphp

            {{-- Grafik Utama --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4">
                    <h6 class="fw-bold mb-0 text-dark">Visualisasi Profil PAPI-Kostick</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div style="height: 320px; position: relative;">
                        <canvas id="papiChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Detail Skor per Kategori --}}
            <div class="row g-4 mb-4">
                @foreach($categories as $categoryName => $scales)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-header border-0 p-4 d-flex align-items-center gap-2" style="background: rgba(56, 189, 248, 0.05);">
                            <i class="bi bi-bookmark-star-fill text-primary"></i>
                            <h6 class="fw-bold mb-0 text-dark">{{ $categoryName }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50" class="text-center">Skala</th>
                                            <th width="180">Parameter</th>
                                            <th width="70" class="text-center">Skor</th>
                                            <th>Deskripsi</th>
                                            <th width="140">Bar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($scales as $key => $data)
                                    @php
                                        $scaleKey  = strlen($key) > 1 ? substr($key, 0, 1) : $key;
                                        $scoreVal  = $data['score'];
                                        // Disesuaikan menggunakan papiResult
                                        $desc      = $session->papiResult->getScoreInterpretation($scaleKey, $scoreVal);
                                        $barColor  = 'bg-blue-gradient';
                                    @endphp
                                    <tr>
                                        {{-- Skala --}}
                                        <td class="text-center">
                                            <div class="fw-bold fs-5 text-primary d-inline-flex align-items-center justify-content-center rounded"
                                                 style="width:36px; height:36px; background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                                                {{ $scaleKey }}
                                            </div>
                                        </td>

                                        {{-- Parameter --}}
                                        <td>
                                            <div class="fw-semibold small">{{ $data['label'] }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $data['desc'] }}</div>
                                        </td>

                                        {{-- Skor --}}
                                        <td class="text-center">
                                            <span class="badge fs-6 bg-blue-gradient text-black">
                                                {{ $scoreVal }}
                                            </span>
                                        </td>

                                        {{-- Deskripsi --}}
                                        <td>
                                            <p class="mb-0 small text-secondary" style="line-height:1.7">
                                                {{ $desc }}
                                            </p>
                                        </td>

                                        {{-- Bar --}}
                                        <td>
                                            <div class="progress mb-1" style="height:10px; border-radius:5px;">
                                                <div class="bg-blue-gradient progress-bar" style="width:{{ ($scoreVal / 9) * 100 }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $scoreVal }} / 9</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="alert alert-light border shadow-sm rounded-4 text-center p-5 mt-4">
                <i class="bi bi-exclamation-circle text-warning fs-1 mb-3 d-block"></i>
                <h5 class="fw-bold">Hasil Belum Tersedia</h5>
                <p class="text-muted mb-0">Peserta belum menyelesaikan seluruh soal tes PAPI-Kostick.</p>
            </div>
        @endif

    {{-- KONDISI 3: JIKA TES TIDAK DIKENALI --}}
    @else
        <div class="alert alert-secondary rounded-4 border-0 shadow-sm text-center py-4">
            Jenis tes tidak dikenali oleh sistem.
        </div>
    @endif
</div>

{{-- Styling Tambahan --}}
<style>
    .avatar-large {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: #38bdf8;
        display: flex; align-items: center; justify-content: center;
        border-radius: 18px;
        font-weight: 800; font-size: 24px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .btn-white { background: #fff; border: 1px solid #e2e8f0; color: #64748b; font-weight: 600; }
    .btn-white:hover { background: #f8fafc; color: #0f172a; }
    .table thead th { letter-spacing: 0.5px; }
    .progress { background-color: #f1f5f9; }
    .bg-blue-gradient { background: linear-gradient(135deg, #38bdf8, #0ea5e9); }
</style>

{{-- Script Chart.js Khusus PAPI-Kostick --}}
@if($session->accessRequest->jenis_tes === 'PapiKostick' && $session->papiResult)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['N','G','A','L','P','I','T','V','X','S','B','O','R','D','C','Z','E','K','F','W'];
    // Disesuaikan menggunakan papiResult
    const data   = [
        {{ $session->papiResult->scale_n }}, {{ $session->papiResult->scale_g }},
        {{ $session->papiResult->scale_a }}, {{ $session->papiResult->scale_l }},
        {{ $session->papiResult->scale_p }}, {{ $session->papiResult->scale_i }},
        {{ $session->papiResult->scale_t }}, {{ $session->papiResult->scale_v }},
        {{ $session->papiResult->scale_x }}, {{ $session->papiResult->scale_s }},
        {{ $session->papiResult->scale_b }}, {{ $session->papiResult->scale_o }},
        {{ $session->papiResult->scale_r }}, {{ $session->papiResult->scale_d }},
        {{ $session->papiResult->scale_c }}, {{ $session->papiResult->scale_z }},
        {{ $session->papiResult->scale_e }}, {{ $session->papiResult->scale_k }},
        {{ $session->papiResult->scale_f }}, {{ $session->papiResult->scale_w }},
    ];

    new Chart(document.getElementById('papiChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: 'rgba(56, 189, 248, 0.8)',
                borderColor: '#0ea5e9',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#0f172a', padding: 12, borderRadius: 10 }
            },
            scales: {
                y: { min: 0, max: 9, grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endif

@endsection