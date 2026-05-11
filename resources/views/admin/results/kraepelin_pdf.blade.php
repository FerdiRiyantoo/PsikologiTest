<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Kraepelin - {{ $session->accessRequest->name }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #2d2d2d;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: #6d28d9;
            color: white;
            padding: 18px 24px;
            margin-bottom: 16px;
        }
        .header h1 { font-size:15px; font-weight:bold; letter-spacing:0.5px; margin-bottom:2px; }
        .header p  { font-size:10px; opacity:0.85; }

        /* ===== INFO PESERTA ===== */
        .info-box {
            border: 1px solid #dde3ef;
            border-radius: 4px;
            padding: 12px 16px;
            margin: 0 24px 16px;
            background: #faf5ff;
        }
        .info-box h3 {
            font-size:11px; font-weight:bold; color:#6d28d9;
            margin-bottom:8px; border-bottom:1px solid #dde3ef; padding-bottom:5px;
        }
        .info-grid { width:100%; }
        .info-grid td { padding:3px 6px 3px 0; vertical-align:top; width:25%; }
        .info-label { color:#6b7280; font-size:9.5px; }
        .info-value { font-weight:bold; font-size:10.5px; }

        /* ===== 4 SKOR UTAMA ===== */
        .scores-section { margin:0 24px 16px; }
        .scores-title {
            font-size:11px; font-weight:bold; color:white;
            background:#6d28d9; padding:5px 10px;
        }
        .scores-grid {
            width:100%; border-collapse:collapse;
            border:1px solid #dde3ef;
        }
        .score-card {
            width:25%; text-align:center;
            padding:14px 8px;
            border-right:1px solid #dde3ef;
            vertical-align:top;
        }
        .score-card:last-child { border-right:none; }
        .score-num {
            font-size:22px; font-weight:900;
            color:#6d28d9; line-height:1;
            margin-bottom:4px;
        }
        .score-unit  { font-size:9px; color:#9ca3af; }
        .score-label { font-size:10px; font-weight:bold; color:#374151; margin:4px 0 2px; }
        .score-desc  { font-size:9px; color:#9ca3af; }

        /* ===== RINGKASAN ===== */
        .summary-section { margin:0 24px 16px; }
        .summary-title {
            font-size:11px; font-weight:bold; color:white;
            background:#6d28d9; padding:5px 10px;
        }
        .summary-grid { width:100%; border-collapse:collapse; border:1px solid #dde3ef; }
        .summary-cell {
            width:33.33%; text-align:center; padding:12px 8px;
            border-right:1px solid #dde3ef; vertical-align:middle;
        }
        .summary-cell:last-child { border-right:none; }
        .summary-num  { font-size:20px; font-weight:900; line-height:1; margin-bottom:3px; }
        .summary-label{ font-size:10px; color:#6b7280; }

        /* ===== GRAFIK BATANG PER KOLOM ===== */
        .chart-section { margin:0 24px 16px; }
        .chart-title {
            font-size:11px; font-weight:bold; color:white;
            background:#6d28d9; padding:5px 10px;
        }
        .chart-box {
            border:1px solid #dde3ef; border-top:none;
            padding:10px 12px;
        }
        .chart-col-table { width:100%; border-collapse:collapse; }
        .chart-col-table td { padding:1px 4px 1px 0; vertical-align:middle; }
        .col-label {
            width:20px; font-weight:bold; font-size:9px;
            color:#6d28d9; text-align:center;
        }
        .bar-bg {
            background:#ede9fe; height:10px;
            border-radius:2px; width:100%;
        }
        .bar-fill-ans  { background:#6d28d9; height:10px; border-radius:2px; }
        .bar-fill-corr { background:#16a34a; height:10px; border-radius:2px; }
        .bar-val {
            width:28px; font-size:9px; font-weight:bold;
            color:#374151; text-align:right;
        }

        /* ===== TABEL DETAIL PER KOLOM ===== */
        .detail-section { margin:0 24px 14px; }
        .detail-title {
            font-size:11px; font-weight:bold; color:white;
            background:#6d28d9; padding:5px 10px;
        }
        table.detail-table {
            width:100%; border-collapse:collapse; font-size:9.5px;
        }
        table.detail-table th {
            background:#ede9fe; color:#4c1d95;
            padding:4px 6px; text-align:left;
            border:1px solid #ddd6fe; font-size:9px;
        }
        table.detail-table td {
            padding:4px 6px; border:1px solid #e5e7eb;
            vertical-align:middle;
        }
        table.detail-table tr:nth-child(even) td { background:#faf5ff; }
        .col-num { font-weight:bold; color:#6d28d9; text-align:center; }
        .acc-high { color:#15803d; font-weight:bold; }
        .acc-mid  { color:#d97706; font-weight:bold; }
        .acc-low  { color:#dc2626; font-weight:bold; }

        /* ===== MINI BAR DI TABEL ===== */
        .mini-bar-bg {
            background:#e5e7eb; height:7px;
            border-radius:2px; width:100%;
        }
        .mini-bar-fill { height:7px; border-radius:2px; }

        /* ===== FOOTER ===== */
        .footer {
            margin:16px 24px 0;
            border-top:1px solid #dde3ef;
            padding-top:8px; font-size:9px; color:#9ca3af;
        }
        .footer-left  { float:left; }
        .footer-right { float:right; }
    </style>
</head>
<body>

{{-- ===== HEADER ===== --}}
<div class="header">
    <h1>HASIL TES KRAEPELIN</h1>
    <p>Laporan Psikotes Kraepelin &nbsp;·&nbsp; Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
</div>

{{-- ===== INFO PESERTA ===== --}}
<div class="info-box">
    <h3>Data Peserta</h3>
    <table class="info-grid">
        <tr>
            <td>
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $session->accessRequest->name }}</div>
            </td>
            <td>
                <div class="info-label">Email</div>
                <div class="info-value">{{ $session->accessRequest->email }}</div>
            </td>
            <td>
                <div class="info-label">No. HP</div>
                <div class="info-value">{{ $session->accessRequest->phone ?? '-' }}</div>
            </td>
            <td>
                <div class="info-label">Posisi Dilamar</div>
                <div class="info-value">{{ $session->accessRequest->posisi_yang_dilamar?? '-' }}
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="info-label">Pendidikan</div>
                <div class="info-value">{{ $session->accessRequest->pendidikan_terakhir ?? '-' }}</div>
            </td>
            <td>
                <div class="info-label">Jurusan</div>
                <div class="info-value">{{ $session->accessRequest->jurusan ?? '-' }}</div>
            </td>
            <td>
                <div class="info-label">Tanggal Tes</div>
                <div class="info-value">{{ $session->completed_at?->format('d/m/Y') ?? '-' }}</div>
            </td>
            <td>
                <div class="info-label">Durasi</div>
                <div class="info-value">
                    @if($session->started_at && $session->completed_at)
                        {{ $session->started_at->diffInMinutes($session->completed_at) }} menit
                    @else - @endif
                </div>
            </td>
        </tr>
    </table>
</div>

@if($result)

{{-- ===== 4 SKOR UTAMA ===== --}}
<div class="scores-section">
    <div class="scores-title">Skor Utama Tes Kraepelin</div>
    <table class="scores-grid">
        <tr>
            <td class="score-card">
                <div class="score-num">{{ number_format($result->pace_score, 1) }}</div>
                <div class="score-unit">soal/kolom</div>
                <div class="score-label">Kecepatan (Pace)</div>
                <div class="score-desc">Rata-rata soal dijawab per kolom</div>
            </td>
            <td class="score-card">
                <div class="score-num">{{ number_format($result->accuracy_score, 1) }}<span style="font-size:14px">%</span></div>
                <div class="score-unit">dari yang dijawab</div>
                <div class="score-label">Ketelitian (Accuracy)</div>
                <div class="score-desc">Persentase jawaban benar</div>
            </td>
            <td class="score-card">
                <div class="score-num">{{ number_format($result->endurance_score, 1) }}<span style="font-size:14px">%</span></div>
                <div class="score-unit">konsistensi</div>
                <div class="score-label">Ketahanan (Endurance)</div>
                <div class="score-desc">Konsistensi awal vs akhir</div>
            </td>
            <td class="score-card">
                <div class="score-num">{{ number_format($result->stability_score, 1) }}<span style="font-size:14px">%</span></div>
                <div class="score-unit">kestabilan</div>
                <div class="score-label">Keajegan (Stability)</div>
                <div class="score-desc">Kestabilan antar kolom</div>
            </td>
        </tr>
    </table>
</div>

{{-- ===== RINGKASAN TOTAL ===== --}}
<div class="summary-section">
    <div class="summary-title">Ringkasan Total</div>
    <table class="summary-grid">
        <tr>
            <td class="summary-cell">
                <div class="summary-num" style="color:#6d28d9">{{ $result->total_answered }}</div>
                <div class="summary-label">Total Soal Dijawab</div>
            </td>
            <td class="summary-cell">
                <div class="summary-num" style="color:#15803d">{{ $result->total_correct }}</div>
                <div class="summary-label">Jawaban Benar</div>
            </td>
            <td class="summary-cell">
                <div class="summary-num" style="color:#dc2626">
                    {{ $result->total_answered - $result->total_correct }}
                </div>
                <div class="summary-label">Jawaban Salah</div>
            </td>
        </tr>
    </table>
</div>

@php $rawData = $result->raw_data ?? []; @endphp

{{-- ===== GRAFIK BATANG PER KOLOM ===== --}}
@if(count($rawData) > 0)
<div class="chart-section">
    <div class="chart-title">Grafik Jawaban per Kolom (Biru = Dijawab, Hijau = Benar)</div>
    <div class="chart-box">
        @php
            $maxAnswered = max(array_column($rawData, 'answered') ?: [1]);
            // Tampilkan 2 kolom side by side agar muat di halaman
            $chunks = array_chunk($rawData, 25);
        @endphp

        <table style="width:100%; border-collapse:collapse;">
            <tr>
            @foreach($chunks as $chunk)
            <td style="width:50%; vertical-align:top; padding-right:{{ !$loop->last ? '12px' : '0' }};">
                <table class="chart-col-table">
                    @foreach($chunk as $col)
                    @php
                        $pctAns  = $maxAnswered > 0 ? round(($col['answered'] / $maxAnswered) * 100) : 0;
                        $pctCorr = $maxAnswered > 0 ? round(($col['correct']  / $maxAnswered) * 100) : 0;
                    @endphp
                    <tr>
                        <td class="col-label">{{ $col['column'] }}</td>
                        <td style="padding:1px 4px;">
                            {{-- Bar dijawab --}}
                            <div style="background:#ede9fe; height:5px; border-radius:2px; width:100%; margin-bottom:1px;">
                                <div style="background:#6d28d9; height:5px; border-radius:2px; width:{{ $pctAns }}%;"></div>
                            </div>
                            {{-- Bar benar --}}
                            <div style="background:#dcfce7; height:5px; border-radius:2px; width:100%;">
                                <div style="background:#16a34a; height:5px; border-radius:2px; width:{{ $pctCorr }}%;"></div>
                            </div>
                        </td>
                        <td class="bar-val">{{ $col['answered'] }}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
            @endforeach
            </tr>
        </table>
    </div>
</div>
@endif

{{-- ===== TABEL DETAIL PER KOLOM ===== --}}
@if(count($rawData) > 0)
<div class="detail-section">
    <div class="detail-title">Detail Hasil per Kolom</div>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width:40px; text-align:center;">Kolom</th>
                <th style="width:70px; text-align:center;">Dijawab</th>
                <th style="width:60px; text-align:center;">Benar</th>
                <th style="width:60px; text-align:center;">Salah</th>
                <th style="width:70px; text-align:center;">Akurasi</th>
                <th>Bar Akurasi</th>
            </tr>
        </thead>
        <tbody>
        @php
            // Bagi tabel menjadi 2 kolom agar lebih ringkas
            $half    = ceil(count($rawData) / 2);
            $leftData  = array_slice($rawData, 0, $half);
            $rightData = array_slice($rawData, $half);
        @endphp

        {{-- Render sebagai tabel biasa --}}
        @foreach($rawData as $col)
        @php
            $salah   = $col['answered'] - $col['correct'];
            $acc     = $col['accuracy_pct'] ?? 0;
            $accClass = $acc >= 80 ? 'acc-high' : ($acc >= 60 ? 'acc-mid' : 'acc-low');
            $barColor = $acc >= 80 ? '#16a34a' : ($acc >= 60 ? '#d97706' : '#dc2626');
            $barW     = round($acc);
        @endphp
        <tr>
            <td class="col-num">{{ $col['column'] }}</td>
            <td style="text-align:center;">{{ $col['answered'] }}</td>
            <td style="text-align:center; color:#15803d; font-weight:bold;">{{ $col['correct'] }}</td>
            <td style="text-align:center; color:#dc2626; font-weight:bold;">{{ $salah }}</td>
            <td style="text-align:center;" class="{{ $accClass }}">{{ $acc }}%</td>
            <td>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0;">
                            <div style="background:#e5e7eb; height:7px; border-radius:2px; width:100%;">
                                <div style="background:{{ $barColor }}; height:7px; border-radius:2px; width:{{ $barW }}%;"></div>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

@else
<div style="margin:24px; padding:12px; background:#fef9c3; border:1px solid #fde047; border-radius:4px; color:#854d0e;">
    Hasil tes Kraepelin belum tersedia.
</div>
@endif

{{-- ===== FOOTER ===== --}}
<div class="footer">
    <span class="footer-left">
        Dokumen ini digenerate otomatis oleh Sistem Psikotes &nbsp;·&nbsp; Digitama Consulting
    </span>
    <span class="footer-right">
        {{ $session->accessRequest->name }} &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }}
    </span>
</div>

</body>
</html>