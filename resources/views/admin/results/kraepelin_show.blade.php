@extends('layouts.admin')
@section('title', 'Hasil Kraepelin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/showkraepelin.css') }}">
@endpush
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.results.index') }}" class="btn btn-white shadow-sm rounded-3 px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <h4 class="fw-bold mb-0 text-dark">Laporan Psikotes</h4>
        </div>
        <a href="{{ route('admin.results.pdf', $session->id) }}"
           class="btn btn-danger shadow-sm rounded-3 px-4" target="_blank">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
        </a>
    </div>

{{-- Info Peserta --}}
<div class="card mb-4 border-primary">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-person-circle me-2"></i>Data Peserta
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="180">Nama</th><td>{{ $session->accessRequest->name }}</td></tr>
                    <tr><th>Email</th><td>{{ $session->accessRequest->email }}</td></tr>
                    <tr><th>Jenis Tes</th><td><span class="badge rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1" style="background:#f5f3ff; color:#6d28d9; font-size:11px; font-weight:600;"> <i class="bi bi-calculator"></i>Kraepelin
                            </span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="180">Tanggal Tes</th>
                        <td>{{ $session->completed_at?->format('d F Y, H:i') ?? '-' }}</td></tr>
                    <tr><th>Durasi</th><td>
                        @if($session->started_at && $session->completed_at)
                            {{ number_format($session->started_at->diffInSeconds($session->completed_at) / 60, 1) }}
                            <span class="small text-muted">Menit</span>
                        @else - @endif
                    </td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($result = $session->kraepelinResult)

{{-- 4 Skor Utama --}}
{{-- 4 Skor Utama (Panker, Tianker, Hanker, Janker) --}}
<div class="row g-3 mb-4">
    @php
        // 1. PANKER (Kecepatan Kerja)
        $pankerScore = $result->pace_score;
        if ($pankerScore >= 16.044)      { $pankerStatus = 'Baik Sekali'; $pankerColor = 'success'; }
        elseif ($pankerScore >= 13.362)  { $pankerStatus = 'Baik';        $pankerColor = 'primary'; }
        elseif ($pankerScore >= 10.963)  { $pankerStatus = 'Sedang';      $pankerColor = 'warning text-dark'; }
        elseif ($pankerScore >= 8.116)   { $pankerStatus = 'Kurang';      $pankerColor = 'danger'; }
        else                             { $pankerStatus = 'Kurang Sekali'; $pankerColor = 'dark'; }

        // 2. TIANKER (Ketelitian Kerja / Error & Skip)
        $totalErrors = $result->total_errors ?? ($result->total_answered - $result->total_correct);
        if ($totalErrors == 0)           { $tiankerStatus = 'Baik Sekali'; $tiankerColor = 'success'; }
        elseif ($totalErrors <= 2)       { $tiankerStatus = 'Baik';        $tiankerColor = 'primary'; }
        elseif ($totalErrors <= 14)      { $tiankerStatus = 'Sedang';      $tiankerColor = 'warning text-dark'; }
        elseif ($totalErrors <= 21)      { $tiankerStatus = 'Kurang';      $tiankerColor = 'danger'; }
        else                             { $tiankerStatus = 'Kurang Sekali'; $tiankerColor = 'dark'; }

        // 3. HANKER (Ketahanan Kerja)
        $hankerScore = $result->endurance_score;
        if ($hankerScore >= 2.497)       { $hankerStatus = 'Baik Sekali'; $hankerColor = 'success'; }
        elseif ($hankerScore >= 1.015)   { $hankerStatus = 'Baik';        $hankerColor = 'primary'; }
        elseif ($hankerScore >= -0.468)  { $hankerStatus = 'Sedang';      $hankerColor = 'warning text-dark'; }
        elseif ($hankerScore >= -1.195)  { $hankerStatus = 'Kurang';      $hankerColor = 'danger'; }
        else                             { $hankerStatus = 'Kurang Sekali'; $hankerColor = 'dark'; }

        // 4. JANKER (Keajegan Kerja)
        $jankerScore = $result->stability_score;
        if ($jankerScore <= 3)           { $jankerStatus = 'Baik Sekali'; $jankerColor = 'success'; }
        elseif ($jankerScore <= 7)       { $jankerStatus = 'Baik';        $jankerColor = 'primary'; }
        elseif ($jankerScore <= 10)      { $jankerStatus = 'Sedang';      $jankerColor = 'warning text-dark'; }
        elseif ($jankerScore <= 14)      { $jankerStatus = 'Kurang';      $jankerColor = 'danger'; }
        else                             { $jankerStatus = 'Kurang Sekali'; $jankerColor = 'dark'; }
    @endphp

    {{-- 1. PANKER (Kecepatan) --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-{{ $pankerColor }} border-4">
            <div class="card-body text-center p-3">
                <div class="mb-2"><i class="bi bi-speedometer2 text-{{ $pankerColor }}" style="font-size:1.8rem"></i></div>
                <div class="fw-bold fs-2 text-{{ $pankerColor }}">{{ number_format($pankerScore, 1) }}</div>
                <div class="fw-bold small mb-1">PANKER <br><span class="text-muted fw-normal">(Kecepatan)</span></div>
                <div class="text-muted" style="font-size:10px; margin-top:4px">Rata-rata jawaban benar per kolom</div>
                <div class="badge bg-{{ $pankerColor }} mt-1">{{ $pankerStatus }}</div>
            </div>
        </div>
    </div>

    {{-- 2. TIANKER (Ketelitian) --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-{{ $tiankerColor }} border-4">
            <div class="card-body text-center p-3">
                <div class="mb-2"><i class="bi bi-x-circle text-{{ $tiankerColor }}" style="font-size:1.8rem"></i></div>
                <div class="fw-bold fs-2 text-{{ $tiankerColor }}">{{ $totalErrors }}</div>
                <div class="fw-bold small mb-1">TIANKER <br><span class="text-muted fw-normal">(Ketelitian)</span></div>
                <div class="text-muted" style="font-size:10px; margin-top:4px">Total kesalahan & soal dilewati</div>
                <div class="badge bg-{{ $tiankerColor }} mt-1">{{ $tiankerStatus }}</div>
            </div>
        </div>
    </div>

    {{-- 3. HANKER (Ketahanan) --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-{{ $hankerColor }} border-4">
            <div class="card-body text-center p-3">
                <div class="mb-2"><i class="bi bi-battery-half text-{{ $hankerColor }}" style="font-size:1.8rem"></i></div>
                <div class="fw-bold fs-2 text-{{ $hankerColor }}">{{ number_format($hankerScore, 2) }}</div>
                <div class="fw-bold small mb-1">HANKER <br><span class="text-muted fw-normal">(Ketahanan)</span></div>
                <div class="text-muted" style="font-size:10px; margin-top:4px">Tren stabilitas energi mental</div>
                <div class="badge bg-{{ $hankerColor }} mt-1">{{ $hankerStatus }}</div>
            </div>
        </div>
    </div>

    {{-- 4. JANKER (Keajegan) --}}
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-bottom border-{{ $jankerColor }} border-4">
            <div class="card-body text-center p-3">
                <div class="mb-2"><i class="bi bi-activity text-{{ $jankerColor }}" style="font-size:1.8rem"></i></div>
                <div class="fw-bold fs-2 text-{{ $jankerColor }}">{{ number_format($jankerScore, 2) }}</div>
                <div class="fw-bold small mb-1">JANKER <br><span class="text-muted fw-normal">(Keajegan)</span></div>
                <div class="text-muted" style="font-size:10px; margin-top:4px">Fluktuasi konsistensi antar kolom</div>
                <div class="badge bg-{{ $jankerColor }} mt-1">{{ $jankerStatus }}</div>
            </div>
        </div>
    </div>

</div>

{{-- Ringkasan Total --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-4">
                <div class="fw-bold fs-3 text-dark">{{ $result->total_answered }}</div>
                <div class="text-muted small">Total Dijawab</div>
            </div>
            <div class="col-4">
                <div class="fw-bold fs-3 text-success">{{ $result->total_correct }}</div>
                <div class="text-muted small">Jawaban Benar</div>
            </div>
            <div class="col-4">
                <div class="fw-bold fs-3 text-danger">
                    {{ $result->total_answered - $result->total_correct }}
                </div>
                <div class="text-muted small">Jawaban Salah</div>
            </div>
        </div>
    </div>
</div>

{{-- Grafik per Kolom --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header fw-semibold">
        Grafik Jumlah Jawaban per Kolom
    </div>
    <div class="card-body">
        <canvas id="kraepelinChart" height="80"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@php
    $rawData    = $result->raw_data ?? [];
    $colLabels  = array_column($rawData, 'column');
    $answered   = array_column($rawData, 'answered');
    $correct    = array_column($rawData, 'correct');
@endphp

const labels   = {!! json_encode($colLabels) !!};
const answered = {!! json_encode($answered) !!};
const correct  = {!! json_encode($correct) !!};

new Chart(document.getElementById('kraepelinChart'), {
    type: 'bar',
    data: {
        labels: labels.map(l => 'K'+l),
        datasets: [
            {
                label: 'Dijawab',
                data: answered,
                backgroundColor: 'rgba(37,99,235,0.6)',
                borderColor: 'rgba(37,99,235,1)',
                borderWidth: 1,
                borderRadius: 3,
            },
            {
                label: 'Benar',
                data: correct,
                backgroundColor: 'rgba(22,163,74,0.6)',
                borderColor: 'rgba(22,163,74,1)',
                borderWidth: 1,
                borderRadius: 3,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.raw} soal`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 5 },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

@else
<div class="alert alert-warning">Hasil tes Kraepelin belum tersedia.</div>
@endif

@endsection