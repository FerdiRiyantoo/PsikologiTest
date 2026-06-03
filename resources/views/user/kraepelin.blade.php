@extends('layouts.app')
@section('title', 'Tes Kraepelin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kraepelin.css') }}">
@endpush

@section('content')

<div class="kraepelin-wrapper py-4">

    {{-- Progress --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
            <small class="text-muted fw-semibold">
                Kolom {{ $currentColumn }} / {{ $config['total_columns'] }}
            </small>
            <small class="text-muted">{{ $progress }}% selesai</small>
        </div>
        <div class="progress mb-1" style="height:6px; border-radius:4px">
            <div class="progress-bar bg-primary"
                 style="width:{{ $progress }}%; transition:width 0.4s ease"></div>
        </div>
    </div>

    {{-- Timer --}}
    <div class="mb-3">

        {{-- Timer Total --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted fw-semibold">
                <i class="bi bi-hourglass-split me-1"></i>Sisa Waktu Total
            </small>
            <span class="fw-bold" id="totalTimerText"
                style="font-size:15px; color:#1d4ed8; font-family:monospace">
                {{ gmdate('i:s', $timeLeft) }}
            </span>
        </div>
        <div class="progress mb-3" style="height:5px; border-radius:4px; background:#dbeafe">
            <div class="progress-bar"
                id="totalTimerBar"
                style="width:{{ ($timeLeft / $config['total_time']) * 100 }}%;
                        background:#2563eb; transition:width 1s linear">
            </div>
        </div>

        {{-- Timer Kolom --}}
        <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">
                <i class="bi bi-stopwatch me-1"></i>Waktu Kolom {{ $currentColumn }}
            </small>
            <small class="fw-bold" id="timerText">{{ $config['time_per_column'] }}s</small>
        </div>
        <div class="timer-bar">
            <div class="timer-fill" id="timerFill" style="width:100%"></div>
        </div>

    </div>

    {{-- Header kolom --}}
    <div class="column-header mb-3">
        <div style="font-size:12px; opacity:0.8; text-transform:uppercase; letter-spacing:1px">
            Tes Kraepelin
        </div>
        <div style="font-size:18px; font-weight:700; margin-top:2px">
            Kolom {{ $currentColumn }}
        </div>
        <div style="font-size:12px; opacity:0.75; margin-top:4px">
            Tulis satu digit terakhir dari penjumlahan dua angka
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('kraepelin.save') }}" id="kraepelinForm">
        @csrf
        <input type="hidden" name="column_number" value="{{ $currentColumn }}">

        @php
            $totalRows = $config['rows_per_column']; // 26
            $halfRows  = (int) ceil($totalRows / 2); // 13 kiri, 13 kanan
        @endphp

        <div class="row g-2">

            {{-- Kolom KIRI: soal 0 - (halfRows-1) --}}
            <div class="col-6">
                <div class="digit-column">
                    @for($i = 0; $i < $halfRows; $i++)
                    @php
                        $correct = ($columnDigits[$i] + $columnDigits[$i + 1]) % 10;
                    @endphp
                    <div class="digit-pair" data-row="{{ $i }}">
                        <div class="digit-num">{{ $columnDigits[$i] }}</div>
                        <div class="digit-sep">+</div>
                        <div class="digit-num">{{ $columnDigits[$i + 1] }}</div>
                        <div class="digit-sep">=</div>
                        <input type="number"
                               name="answers[{{ $i }}]"
                               class="digit-input"
                               min="0" max="9"
                               inputmode="numeric"
                               autocomplete="off"
                               data-index="{{ $i }}"
                               data-correct="{{ $correct }}"
                               id="input_{{ $i }}">
                    </div>
                    @endfor
                </div>
            </div>

            {{-- Kolom KANAN: soal halfRows - (totalRows-1) --}}
            <div class="col-6">
                <div class="digit-column">
                    @for($i = $halfRows; $i < $totalRows; $i++)
                    @php
                        $correct = ($columnDigits[$i] + $columnDigits[$i + 1]) % 10;
                    @endphp
                    <div class="digit-pair" data-row="{{ $i }}">
                        <div class="digit-num">{{ $columnDigits[$i] }}</div>
                        <div class="digit-sep">+</div>
                        <div class="digit-num">{{ $columnDigits[$i + 1] }}</div>
                        <div class="digit-sep">=</div>
                        <input type="number"
                               name="answers[{{ $i }}]"
                               class="digit-input"
                               min="0" max="9"
                               inputmode="numeric"
                               autocomplete="off"
                               data-index="{{ $i }}"
                               data-correct="{{ $correct }}"
                               id="input_{{ $i }}">
                    </div>
                    @endfor
                </div>
            </div>

        </div>

        <button type="submit" id="submitBtn"
                class="btn btn-primary w-100 py-2 mt-4 rounded-3 fw-semibold">
            <i class="bi bi-arrow-right-circle me-1"></i>
            @if($currentColumn < $config['total_columns'])
                Lanjut ke Kolom {{ $currentColumn + 1 }}
            @else
                Selesai Tes
            @endif
        </button>
    </form>

</div>
@endsection

@push('scripts')
<script>
const colTime     = {{ $config['time_per_column'] }};
const totalConfig = {{ $config['total_time'] }};
const form        = document.getElementById('kraepelinForm');

// Timer kolom
let colTimeLeft  = colTime;
const timerFill  = document.getElementById('timerFill');
const timerText  = document.getElementById('timerText');

// Timer total
let totalTimeLeft    = {{ $timeLeft }};
const totalBar       = document.getElementById('totalTimerBar');
const totalTimerText = document.getElementById('totalTimerText');

function formatTime(seconds) {
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    return `${m}:${s}`;
}

// Jalankan kedua timer bersamaan
const timer = setInterval(() => {

    // ===== Timer Kolom =====
    colTimeLeft--;
    const colPct = (colTimeLeft / colTime) * 100;
    timerFill.style.width = colPct + '%';
    timerText.textContent = colTimeLeft + 's';

    timerFill.className = 'timer-fill';
    if (colPct <= 20) {
        timerFill.classList.add('danger');
        timerText.style.color = '#ef4444';
    } else if (colPct <= 40) {
        timerFill.classList.add('warning');
        timerText.style.color = '#f59e0b';
    }

    // ===== Timer Total =====
    totalTimeLeft--;
    const totalPct = (totalTimeLeft / totalConfig) * 100;
    totalBar.style.width  = Math.max(0, totalPct) + '%';
    totalTimerText.textContent = formatTime(Math.max(0, totalTimeLeft));

    // Warna total timer saat menipis
    if (totalPct <= 10) {
        totalTimerText.style.color = '#ef4444';
        totalBar.style.background  = '#ef4444';
    } else if (totalPct <= 25) {
        totalTimerText.style.color = '#f59e0b';
        totalBar.style.background  = '#f59e0b';
    }

    // Timer kolom habis → pindah kolom
    if (colTimeLeft <= 0) {
        clearInterval(timer);
        timerText.textContent = 'Waktu kolom habis!';
        form.submit();
        return;
    }

    // Timer total habis → paksa selesai
    if (totalTimeLeft <= 0) {
        clearInterval(timer);
        totalTimerText.textContent = '00:00';
        timerText.textContent = 'Waktu habis!';
        form.submit();
    }

}, 1000);

// Hentikan timer saat submit manual
form.addEventListener('submit', () => clearInterval(timer));

// Input handlers
const inputs = Array.from(document.querySelectorAll('.digit-input'))
    .sort((a, b) => parseInt(a.dataset.index) - parseInt(b.dataset.index));

inputs[0]?.focus();

inputs.forEach((input, idx) => {
    input.addEventListener('input', function () {
        if (this.value.length > 1) this.value = this.value.slice(-1);
        const val = parseInt(this.value);
        if (isNaN(val) || val < 0 || val > 9) { this.value = ''; return; }
        if (idx < inputs.length - 1) inputs[idx + 1].focus();
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            idx < inputs.length - 1 ? inputs[idx + 1].focus() : form.submit();
        }
        if (e.key === 'ArrowUp' && idx > 0) {
            e.preventDefault(); inputs[idx - 1].focus();
        }
        if (e.key === 'Backspace' && this.value === '' && idx > 0) {
            e.preventDefault(); inputs[idx - 1].focus();
        }
    });

    input.addEventListener('focus', function () {
        this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
});
</script>
@endpush