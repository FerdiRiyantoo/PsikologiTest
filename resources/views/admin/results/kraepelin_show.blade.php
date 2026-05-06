@extends('layouts.admin')
@section('title', 'Hasil Kraepelin')
@section('content')

<div class="mb-3 d-flex gap-2">
    <a href="{{ route('admin.results.index') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>
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
                    <tr><th>Jenis Tes</th><td><span class="badge bg-purple text-white">Kraepelin</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="180">Tanggal Tes</th>
                        <td>{{ $session->completed_at?->format('d F Y, H:i') ?? '-' }}</td></tr>
                    <tr><th>Durasi</th><td>
                        @if($session->started_at && $session->completed_at)
                            {{ $session->started_at->diffInMinutes($session->completed_at) }} menit
                        @else - @endif
                    </td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($result = $session->kraepelinResult)

{{-- 4 Skor Utama --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Kecepatan (Pace)', $result->pace_score, 'bi-lightning-charge', 'primary',
         'Rata-rata jumlah soal dijawab per kolom'],
        ['Ketelitian (Accuracy)', $result->accuracy_score, 'bi-bullseye', 'success',
         'Persentase jawaban benar dari total dijawab'],
        ['Ketahanan (Endurance)', $result->endurance_score, 'bi-battery-charging', 'warning',
         'Konsistensi kecepatan dari awal hingga akhir'],
        ['Keajegan (Stability)', $result->stability_score, 'bi-graph-up', 'info',
         'Kestabilan kecepatan antar kolom (0-100)'],
    ] as [$label, $score, $icon, $color, $desc])
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
                <div class="mb-2">
                    <i class="bi {{ $icon }} text-{{ $color }}" style="font-size:1.8rem"></i>
                </div>
                <div class="fw-bold fs-2 text-{{ $color }}">
                    {{ number_format($score, 1) }}
                    @if(in_array($label, ['Ketelitian (Accuracy)', 'Ketahanan (Endurance)', 'Keajegan (Stability)']))
                        <span style="font-size:14px">%</span>
                    @endif
                </div>
                <div class="fw-semibold small">{{ $label }}</div>
                <div class="text-muted" style="font-size:11px; margin-top:4px">{{ $desc }}</div>
            </div>
        </div>
    </div>
    @endforeach
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