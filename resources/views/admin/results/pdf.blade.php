<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil PAPI-Kostick - {{ $session->accessRequest->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #2d2d2d;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            background: #1a56db;
            color: white;
            padding: 18px 24px;
            margin-bottom: 16px;
        }
        .header h1 {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .header p { font-size: 10px; opacity: 0.85; }

        /* ===== INFO PESERTA ===== */
        .info-box {
            border: 1px solid #dde3ef;
            border-radius: 4px;
            padding: 12px 16px;
            margin: 0 24px 16px;
            background: #f8faff;
        }
        .info-box h3 {
            font-size: 11px;
            font-weight: bold;
            color: #1a56db;
            margin-bottom: 8px;
            border-bottom: 1px solid #dde3ef;
            padding-bottom: 5px;
        }
        .info-grid { width: 100%; }
        .info-grid td {
            padding: 3px 6px 3px 0;
            vertical-align: top;
            width: 25%;
        }
        .info-label { color: #6b7280; font-size: 9.5px; }
        .info-value { font-weight: bold; font-size: 10.5px; }

        /* ===== SECTION TABEL ===== */
        .section { margin: 0 24px 14px; }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: white;
            background: #1a56db;
            padding: 5px 10px;
        }
        table.score-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.score-table th {
            background: #e8f0fe;
            color: #1a3a6b;
            padding: 5px 8px;
            text-align: left;
            border: 1px solid #c9d8f5;
            font-size: 9.5px;
        }
        table.score-table td {
            padding: 5px 8px;
            border: 1px solid #dde3ef;
            vertical-align: middle;
        }
        table.score-table tr:nth-child(even) td {
            background: #f5f8ff;
        }
        .scale-letter {
            font-weight: bold;
            font-size: 13px;
            color: #1a56db;
            text-align: center;
        }
        .score-num {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }

        /* Warna skor — biru gradasi (solid untuk DomPDF) */
        .score-high { color: #1e3a8a; } /* biru sangat gelap */
        .score-mid  { color: #1d4ed8; } /* biru sedang */
        .score-low  { color: #60a5fa; } /* biru muda */

        /* ===== GRAFIK ===== */
        .chart-section { margin: 0 24px 14px; }
        .chart-title {
            font-size: 11px;
            font-weight: bold;
            color: white;
            background: #1a56db;
            padding: 5px 10px;
        }
        .chart-box {
            border: 1px solid #c9d8f5;
            border-top: none;
            padding: 10px 12px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin: 16px 24px 0;
            border-top: 1px solid #dde3ef;
            padding-top: 8px;
            font-size: 9px;
            color: #9ca3af;
        }
        .footer-left { float: left; }
        .footer-right { float: right; }
    </style>
</head>
<body>

{{-- ===== HEADER ===== --}}
<div class="header">
    <h1>HASIL PSIKOTES PAPI-KOSTICK</h1>
    <p>Laporan Kepribadian Kandidat &nbsp;·&nbsp; Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
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
                <div class="info-value">{{ $session->accessRequest->posisi_yang_dilamar ?? $session->accessRequest->posisi_jabatan_terakhir ?? '-' }}</div>
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
                        @php
                            // Menghitung selisih dalam detik
                            $totalSeconds = $session->started_at->diffInSeconds($session->completed_at);
                            // Mengonversi ke menit desimal (1 angka di belakang koma)
                            $decimalDuration = number_format($totalSeconds / 60, 1);
                        @endphp
                        {{ $decimalDuration }} <span class="small text-muted">Menit</span>
                    @else 
                        - 
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>

@if($session->result)
@php
$scales = $session->result->getScalesArray();
$categories = [
    'Leadership' => [
        'C' => ['label' => 'Leadership Role',          'desc' => 'Peran Pemimpin'],
        'P' => ['label' => 'Need to Control Others',   'desc' => 'Kebutuhan Mengatur Orang Lain'],
        'I' => ['label' => 'Ease in Decision Making',  'desc' => 'Kemudahan Membuat Keputusan'],
    ],
    'Followership' => [
        'F' => ['label' => 'Need to Support Authority',       'desc' => 'Kebutuhan Membantu Atasan'],
        'W' => ['label' => 'Need for Rules and Supervision',  'desc' => 'Kebutuhan Aturan & Pengawasan'],
    ],
    'Activity' => [
        'T' => ['label' => 'Pace (Work)',     'desc' => 'Kesiapan & Mental Bekerja'],
        'V' => ['label' => 'Vigorous Type',   'desc' => 'Peran Penuh Semangat – Fisik'],
    ],
    'Work Style' => [
        'R' => ['label' => 'Theoretical Type',                  'desc' => 'Peran Orang yang Teoritis'],
        'D' => ['label' => 'Interest in Working With Details',  'desc' => 'Bekerja dengan Hal Rinci'],
        'C' => ['label' => 'Organized Type',                    'desc' => 'Peran Mengatur'],
    ],
    'Social Nature' => [
        'X' => ['label' => 'Need to be Noticed',          'desc' => 'Kebutuhan Diperhatikan'],
        'B' => ['label' => 'Need to Belong to Groups',    'desc' => 'Diterima dalam Kelompok'],
        'O' => ['label' => 'Need for Closeness',          'desc' => 'Kedekatan & Kasih Sayang'],
        'S' => ['label' => 'Social Extension',            'desc' => 'Peran Hubungan Sosial'],
    ],
    'Work Direction' => [
        'N' => ['label' => 'Need to Finish Task',          'desc' => 'Menyelesaikan Tugas Mandiri'],
        'G' => ['label' => 'Role of Hard Intense Worker',  'desc' => 'Pekerja Keras'],
        'A' => ['label' => 'Need for Achievement',         'desc' => 'Kebutuhan Berprestasi'],
        'L' => ['label' => 'Need for Change',              'desc' => 'Kebutuhan untuk Berubah'],
    ],
    'Temperament' => [
        'E' => ['label' => 'Emotional Resistant',  'desc' => 'Pengendalian Emosi'],
        'K' => ['label' => 'Need to be Forceful',  'desc' => 'Kebutuhan untuk Agresif'],
        'Z' => ['label' => 'Need for Change',      'desc' => 'Kebutuhan untuk Berubah'],
    ],
];

// Fungsi warna solid untuk DomPDF (tidak support gradient)
// Tingkatan biru: rendah=muda, sedang=normal, tinggi=gelap
$getBarColor = function(int $score): string {
    if ($score >= 7) return '#1e3a8a'; // biru sangat gelap
    if ($score >= 4) return '#2563eb'; // biru sedang
    return '#93c5fd';                  // biru muda
};

$getScoreClass = function(int $score): string {
    if ($score >= 7) return 'score-high';
    if ($score >= 4) return 'score-mid';
    return 'score-low';
};
@endphp

{{-- ===== GRAFIK BATANG ===== --}}
<div class="chart-section">
    <div class="chart-title">Grafik Skor 20 Skala PAPI-Kostick</div>
    <div class="chart-box">
        <table style="width:100%; border-collapse:collapse;">
            @php $scaleKeys = array_keys($scales); @endphp
            @for($i = 0; $i < count($scaleKeys); $i += 2)
            <tr>
                @for($j = $i; $j < min($i + 2, count($scaleKeys)); $j++)
                @php
                    $key      = $scaleKeys[$j];
                    $score    = $scales[$key];
                    $pct      = round(($score / 9) * 100);
                    $barColor = $getBarColor($score);
                @endphp
                <td style="width:50%; padding:3px 10px 3px 0;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="width:16px; font-weight:bold; font-size:10px; color:#1a56db;">
                                {{ $key }}
                            </td>
                            <td style="padding:0 6px;">
                                <div style="background:#dbeafe; height:11px; border-radius:3px; width:100%;">
                                    <div style="background:{{ $barColor }}; height:11px; border-radius:3px; width:{{ $pct }}%;"></div>
                                </div>
                            </td>
                            <td style="width:20px; font-weight:bold; font-size:10px; text-align:right; color:#1e3a8a;">
                                {{ $score }}
                            </td>
                        </tr>
                    </table>
                </td>
                @endfor
            </tr>
            @endfor
        </table>
    </div>
</div>

{{-- ===== TABEL SKOR PER KATEGORI ===== --}}
@foreach($categories as $catName => $catScales)
<div class="section">
    <div class="section-title">{{ $catName }}</div>
    <table class="score-table">
        <thead>
            <tr>
                <th style="width:28px;">Skala</th>
                <th style="width:130px;">Parameter</th>
                <th style="width:100px;">Deskripsi</th>
                <th style="width:36px; text-align:center;">Skor</th>
                <th style="width:120px;">Bar</th>
            </tr>
        </thead>
        <tbody>
        @foreach($catScales as $scaleKey => $info)
        @php
            $scoreKey  = 'scale_' . strtolower($scaleKey);
            $scoreVal  = $session->result->$scoreKey ?? 0;
            $barWidth  = round(($scoreVal / 9) * 100);
            $barColor  = $getBarColor($scoreVal);
            $scoreClass = $getScoreClass($scoreVal);
        @endphp
        <tr>
            <td class="scale-letter">{{ $scaleKey }}</td>
            <td>{{ $info['label'] }}</td>
            <td style="color:#6b7280; font-size:9px;">{{ $info['desc'] }}</td>
            <td class="score-num {{ $scoreClass }}">{{ $scoreVal }}</td>
            <td>
                {{-- DomPDF: gunakan table untuk bar agar lebar bisa dikontrol --}}
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="padding:0;">
                            <div style="background:#dbeafe; height:8px; border-radius:2px; width:100%;">
                                <div style="background:{{ $barColor }}; height:8px; border-radius:2px; width:{{ $barWidth }}%;"></div>
                            </div>
                        </td>
                        <td style="width:24px; font-size:9px; color:#9ca3af; padding-left:4px; white-space:nowrap;">
                            {{ $scoreVal }}/9
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endforeach

@else
<div style="margin:24px; padding:12px; background:#fef9c3; border:1px solid #fde047; border-radius:4px; color:#854d0e;">
    Hasil tes belum tersedia.
</div>
@endif

{{-- ===== FOOTER ===== --}}
<div class="footer">
    <span class="footer-left">Dokumen ini digenerate secara otomatis oleh Sistem Psikotes PAPI-Kostick &nbsp;·&nbsp; Digitama Consulting</span>
    <span class="footer-right">{{ $session->accessRequest->name }} &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }}</span>
</div>

</body>
</html>