@extends('layouts.admin')
@section('title', 'Detail Analisis Psikotes')

@section('content')
<div class="container-fluid p-0" style="min-width:0;">

    {{-- Header Action Bar --}}
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
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-large">
                    {{ strtoupper(substr($session->accessRequest->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $session->accessRequest->name }}</h5>
                    <p class="text-muted mb-0 small">
                        Kandidat ID: #{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Assessment Completed
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body bg-light bg-opacity-50 p-4 border-top">
            <div class="row g-4">
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                           style="font-size:10px; letter-spacing:1px;">Kontak & Posisi</small>
                    <div class="fw-semibold text-dark">{{ $session->accessRequest->email }}</div>
                    <div class="text-muted small">
                        Posisi: {{ $session->accessRequest->posisi_yang_dilamar ?? '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                           style="font-size:10px; letter-spacing:1px;">Waktu Penyelesaian</small>
                    <div class="fw-semibold text-dark">
                        {{ $session->completed_at?->isoFormat('D MMMM Y, HH:mm') ?? '-' }}
                    </div>
                    <div class="text-muted small">
                        Tes:
                        @php $jenisTes = strtolower($session->accessRequest->jenis_tes ?? 'PapiKostick'); @endphp
                        <span class="fw-bold {{ in_array($jenisTes, ['krempelin','kraepelin']) ? 'text-purple' : 'text-primary' }}">
                            {{ in_array($jenisTes, ['krempelin','kraepelin']) ? 'Kraepelin' : 'PapiKostick' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block mb-1 text-uppercase fw-bold"
                           style="font-size:10px; letter-spacing:1px;">Total Durasi</small>
                    <div class="fw-semibold text-primary fs-5">
                        <i class="bi bi-stopwatch me-1"></i>
                        @if($session->started_at && $session->completed_at)
                            {{ number_format($session->started_at->diffInSeconds($session->completed_at) / 60, 1) }}
                            <span class="small text-muted">Menit</span>
                        @else - @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== KRAEPELIN ===== --}}
    @if(in_array($jenisTes, ['krempelin', 'kraepelin']))

        @if($session->kraepelinResult)
        <div class="row g-4 mb-4">
            @foreach([
                ['Kecepatan (Pace)',      $session->kraepelinResult->pace_score,      'bi-speedometer2',  'primary', 'Rata-rata soal dijawab per kolom'],
                ['Ketelitian (Accuracy)', $session->kraepelinResult->accuracy_score,  'bi-check2-all',    'success', $session->kraepelinResult->total_correct.' benar dari '.$session->kraepelinResult->total_answered.' soal'],
                ['Ketahanan (Endurance)', $session->kraepelinResult->endurance_score, 'bi-battery-half',  'warning', 'Tren paruh kedua vs pertama'],
                ['Keajegan (Stability)',  $session->kraepelinResult->stability_score, 'bi-activity',      'danger',  'Kestabilan antar kolom'],
            ] as [$label, $score, $icon, $color, $desc])
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 border-bottom border-{{ $color }} border-4">
                    <div class="card-body text-center p-4">
                        <div class="text-{{ $color }} mb-2">
                            <i class="bi {{ $icon }} fs-1"></i>
                        </div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size:12px">
                            {{ $label }}
                        </h6>
                        <h2 class="fw-bold mb-0 text-{{ $color }}">
                            {{ number_format($score, 1) }}
                            @if(!str_contains($label, 'Pace')) <span class="fs-5">%</span> @endif
                        </h2>
                        <small class="text-muted">{{ $desc }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                    <div class="fw-bold fs-2 text-dark">{{ $session->kraepelinResult->total_answered }}</div>
                    <small class="text-muted">Total Dijawab</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                    <div class="fw-bold fs-2 text-success">{{ $session->kraepelinResult->total_correct }}</div>
                    <small class="text-muted">Jawaban Benar</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-3">
                    <div class="fw-bold fs-2 text-danger">
                        {{ $session->kraepelinResult->total_answered - $session->kraepelinResult->total_correct }}
                    </div>
                    <small class="text-muted">Jawaban Salah</small>
                </div>
            </div>
        </div>

        {{-- Grafik per kolom --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h6 class="fw-bold mb-0">Grafik Jawaban per Kolom</h6>
            </div>
            <div class="card-body">
                <canvas id="kraepelinChart" height="80"></canvas>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        @php
            $rawData  = $session->kraepelinResult->raw_data ?? [];
            $colLabels = array_column($rawData, 'column');
            $answered  = array_column($rawData, 'answered');
            $correct   = array_column($rawData, 'correct');
        @endphp
        new Chart(document.getElementById('kraepelinChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_map(fn($l) => 'K'.$l, $colLabels)) !!},
                datasets: [
                    {
                        label: 'Dijawab',
                        data: {!! json_encode($answered) !!},
                        backgroundColor: 'rgba(109,40,217,0.6)',
                        borderColor: 'rgba(109,40,217,1)',
                        borderWidth: 1, borderRadius: 3,
                    },
                    {
                        label: 'Benar',
                        data: {!! json_encode($correct) !!},
                        backgroundColor: 'rgba(22,163,74,0.6)',
                        borderColor: 'rgba(22,163,74,1)',
                        borderWidth: 1, borderRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 5 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });
        </script>
        @endpush

        @else
        <div class="alert alert-warning rounded-4 border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Hasil Kraepelin belum tersedia.
        </div>
        @endif

    {{-- ===== PAPI-KOSTICK ===== --}}
    @else

        @if($session->result)
        @php $categories = $session->result->getCategorizedScales(); @endphp

        {{-- Grafik --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 p-4">
                <h6 class="fw-bold mb-0">Visualisasi Profil PAPI-Kostick</h6>
            </div>
            <div class="card-body p-4 pt-0">
                <div style="height:320px; position:relative;">
                    <canvas id="papiChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Tabel per Kategori --}}
        @foreach($categories as $categoryName => $scales)
        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
            <div class="card-header border-0 p-4 d-flex align-items-center gap-2"
                 style="background:rgba(56,189,248,0.05);">
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
                            $scaleKey = strlen($key) > 1 ? substr($key, 0, 1) : $key;
                            $scoreVal = $data['score'];
                            $desc     = $session->result->getScoreInterpretation($scaleKey, $scoreVal);
                            $barColor = $scoreVal >= 6 ? '#0635b8' : ($scoreVal >= 3 ? '#3d62ca' : '#7595ed');
                        @endphp
                        <tr>
                            <td class="text-center">
                                <div class="fw-bold fs-5 text-primary d-inline-flex
                                            align-items-center justify-content-center rounded"
                                     style="width:36px; height:36px;
                                            background:linear-gradient(135deg,#dbeafe,#bfdbfe);">
                                    {{ $scaleKey }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $data['label'] }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $data['desc'] }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge fs-6"
                                      style="background:{{ $barColor }}; color:white;">
                                    {{ $scoreVal }}
                                </span>
                            </td>
                            <td>
                                <p class="mb-0 small text-secondary" style="line-height:1.7">
                                    {{ $desc }}
                                </p>
                            </td>
                            <td>
                                <div class="progress mb-1" style="height:10px; border-radius:5px;">
                                    <div class="progress-bar"
                                         style="width:{{ ($scoreVal/9)*100 }}%;
                                                background:{{ $barColor }};">
                                    </div>
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
        @endforeach

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        const labels = ['N','G','A','L','P','I','T','V','X','S','B','O','R','D','C','Z','E','K','F','W'];
        const data   = [
            {{ $session->result->scale_n }}, {{ $session->result->scale_g }},
            {{ $session->result->scale_a }}, {{ $session->result->scale_l }},
            {{ $session->result->scale_p }}, {{ $session->result->scale_i }},
            {{ $session->result->scale_t }}, {{ $session->result->scale_v }},
            {{ $session->result->scale_x }}, {{ $session->result->scale_s }},
            {{ $session->result->scale_b }}, {{ $session->result->scale_o }},
            {{ $session->result->scale_r }}, {{ $session->result->scale_d }},
            {{ $session->result->scale_c }}, {{ $session->result->scale_z }},
            {{ $session->result->scale_e }}, {{ $session->result->scale_k }},
            {{ $session->result->scale_f }}, {{ $session->result->scale_w }},
        ];
        const colors = data.map(v => {
            if (v >= 6) return 'rgba(6,53,184,0.8)';
            if (v >= 3) return 'rgba(61,98,202,0.8)';
            return 'rgba(117,149,237,0.8)';
        });
        new Chart(document.getElementById('papiChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data, backgroundColor: colors,
                    borderWidth: 0, borderRadius: 8, barThickness: 20
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ` Skor: ${ctx.raw} / 9` }
                    }
                },
                scales: {
                    y: { min:0, max:9, ticks:{ stepSize:1 }, grid:{ color:'rgba(0,0,0,0.03)' } },
                    x: { grid:{ display:false } }
                }
            }
        });
        </script>
        @endpush

        @else
        <div class="alert alert-light border shadow-sm rounded-4 text-center p-5 mt-4">
            <i class="bi bi-exclamation-circle text-warning fs-1 mb-3 d-block"></i>
            <h5 class="fw-bold">Hasil Belum Tersedia</h5>
            <p class="text-muted mb-0">Peserta belum menyelesaikan seluruh soal tes PAPI-Kostick.</p>
        </div>
        @endif

    @endif

</div>

<style>
    .avatar-large {
        width:60px; height:60px;
        background:linear-gradient(135deg,#0f172a,#1e293b);
        color:#38bdf8;
        display:flex; align-items:center; justify-content:center;
        border-radius:18px;
        font-weight:800; font-size:24px;
        box-shadow:0 10px 20px rgba(0,0,0,0.1);
    }
    .btn-white { background:#fff; border:1px solid #e2e8f0; color:#64748b; font-weight:600; }
    .btn-white:hover { background:#f8fafc; color:#0f172a; }
    .table thead th { letter-spacing:0.5px; }
    .progress { background-color:#f1f5f9; }
    .text-purple { color:#7c3aed; }
</style>
@endsection