@extends('layouts.app')
@section('title', 'Tes Kraepelin')

@push('styles')
<style>
    .kraepelin-wrapper {
        max-width: 480px;
        margin: 0 auto;
        padding: 0 16px;
    }
    .timer-bar {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 6px;
    }
    .timer-fill {
        height: 100%;
        background: #2563eb;
        border-radius: 4px;
        transition: width 1s linear, background 0.5s;
    }
    .timer-fill.warning { background: #f59e0b; }
    .timer-fill.danger  { background: #ef4444; }
    .digit-column {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 24px;
        width: 100%;
    }
    .digit-pair {
        display: flex;
        align-items: center;
        width: 100%;
        border-bottom: 1px solid #f1f5f9;
        min-height: 44px;
    }
    .digit-pair:last-child { border-bottom: none; }
    .digit-num {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        width: 36px;
        text-align: center;
        flex-shrink: 0;
    }
    .digit-sep {
        font-size: 14px;
        color: #94a3b8;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }
    .digit-input {
        width: 52px;
        height: 36px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 700;
        text-align: center;
        color: #1e3a8a;
        background: #f8faff;
        outline: none;
        transition: border-color 0.15s;
        flex-shrink: 0;
    }
    .digit-input:focus {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .digit-input.correct { border-color: #16a34a; background: #f0fdf4; color: #15803d; }
    .digit-input.wrong   { border-color: #dc2626; background: #fef2f2; color: #b91c1c; }
    .digit-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
    .column-header {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white;
        border-radius: 10px;
        padding: 12px 20px;
        text-align: center;
        margin-bottom: 16px;
    }
    .hint-text {
        font-size: 12px;
        color: #94a3b8;
        width: 60px;
        text-align: right;
        flex: 1;
    }
</style>
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
        <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">
                <i class="bi bi-stopwatch me-1"></i>Waktu tersisa
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

        <div class="digit-column">
            @for($i = 0; $i < $config['rows_per_column']; $i++)
            <div class="digit-pair" data-row="{{ $i }}">
                <div class="digit-num">{{ $columnDigits[$i] }}</div>
                <div class="digit-sep">+</div>
                <div class="digit-num">{{ $columnDigits[$i + 1] }}</div>
                <div class="digit-sep">=</div>
                <input type="number"
                       name="answers[{{ $i }}]"
                       class="digit-input"
                       min="0" max="9"
                       maxlength="1"
                       inputmode="numeric"
                       autocomplete="off"
                       data-correct="{{ ($columnDigits[$i] + $columnDigits[$i + 1]) % 10 }}"
                       tabindex="{{ $i + 1 }}"
                       id="input_{{ $i }}">
                <div class="hint-text" id="hint_{{ $i }}"></div>
            </div>
            @endfor
        </div>

        <button type="submit" id="submitBtn"
                class="btn btn-primary w-100 py-2 mt-3 rounded-3 fw-semibold">
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
const totalTime   = {{ $config['time_per_column'] }};
const totalCols   = {{ $config['total_columns'] }};
const currentCol  = {{ $currentColumn }};
let   timeLeft    = totalTime;
let   timerActive = true;

const timerFill = document.getElementById('timerFill');
const timerText = document.getElementById('timerText');
const form      = document.getElementById('kraepelinForm');
const inputs    = document.querySelectorAll('.digit-input');

// Auto fokus ke input pertama
inputs[0]?.focus();

// Auto pindah ke input berikutnya saat input terisi
inputs.forEach((input, idx) => {
    input.addEventListener('input', function () {
        // Hanya ambil 1 digit
        if (this.value.length > 1) {
            this.value = this.value.slice(-1);
        }

        // Validasi hanya angka 0-9
        const val = parseInt(this.value);
        if (isNaN(val) || val < 0 || val > 9) {
            this.value = '';
            return;
        }

        // Pindah ke input berikutnya
        if (idx < inputs.length - 1) {
            inputs[idx + 1].focus();
        }
    });

    // Navigasi dengan keyboard arrow
    input.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' && idx < inputs.length - 1) {
            inputs[idx + 1].focus();
            e.preventDefault();
        }
        if (e.key === 'ArrowUp' && idx > 0) {
            inputs[idx - 1].focus();
            e.preventDefault();
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            } else {
                form.submit();
            }
        }
        // Backspace: jika kosong, kembali ke atas
        if (e.key === 'Backspace' && this.value === '' && idx > 0) {
            inputs[idx - 1].focus();
        }
    });
});

// Timer countdown
const timer = setInterval(() => {
    if (!timerActive) return;
    timeLeft--;

    const pct = (timeLeft / totalTime) * 100;
    timerFill.style.width = pct + '%';
    timerText.textContent = timeLeft + 's';

    // Ubah warna timer
    timerFill.className = 'timer-fill';
    if (pct <= 20) {
        timerFill.classList.add('danger');
        timerText.style.color = '#ef4444';
    } else if (pct <= 40) {
        timerFill.classList.add('warning');
        timerText.style.color = '#f59e0b';
    }

    // Auto submit saat waktu habis
    if (timeLeft <= 0) {
        clearInterval(timer);
        timerActive = false;
        timerText.textContent = 'Waktu habis!';
        form.submit();
    }
}, 1000);

// Hentikan timer saat form disubmit manual
form.addEventListener('submit', () => {
    timerActive = false;
    clearInterval(timer);
});

// Scroll otomatis mengikuti input aktif
inputs.forEach(input => {
    input.addEventListener('focus', function () {
        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});
</script>
@endpush