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

        @php
            $totalRows = $config['rows_per_column']; // 26
            $halfRows  = (int) ceil($totalRows / 2); // 13 kiri, 13 kanan
        @endphp

        {{-- 
            Render dalam 2 kolom visual, tapi urutan input (tabindex)
            tetap berurutan 1-26 dari atas ke bawah (kiri dulu, lanjut kanan)
        --}}
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
const totalTime  = {{ $config['time_per_column'] }};
const totalRows  = {{ $config['rows_per_column'] }};
let   timeLeft   = totalTime;
let   timerActive = true;

const timerFill = document.getElementById('timerFill');
const timerText = document.getElementById('timerText');
const form      = document.getElementById('kraepelinForm');

// Ambil semua input diurutkan berdasarkan data-index (0, 1, 2, ... 25)
// Ini memastikan navigasi keyboard berurutan meskipun input dibagi 2 kolom visual
const inputs = Array.from(document.querySelectorAll('.digit-input'))
    .sort((a, b) => parseInt(a.dataset.index) - parseInt(b.dataset.index));

// Fokus ke input pertama
inputs[0]?.focus();

// Navigasi dan input handler
inputs.forEach((input, idx) => {

    // Saat user mengetik
    input.addEventListener('input', function () {
        // Ambil hanya 1 digit terakhir
        if (this.value.length > 1) {
            this.value = this.value.slice(-1);
        }

        const val = parseInt(this.value);
        if (isNaN(val) || val < 0 || val > 9) {
            this.value = '';
            return;
        }

        // Auto pindah ke input berikutnya
        if (idx < inputs.length - 1) {
            inputs[idx + 1].focus();
        }
    });

    // Navigasi keyboard
    input.addEventListener('keydown', function (e) {

        // Enter / Arrow Down → input berikutnya
        if ((e.key === 'Enter' || e.key === 'ArrowDown')) {
            e.preventDefault();
            if (idx < inputs.length - 1) {
                inputs[idx + 1].focus();
            } else {
                form.submit();
            }
        }

        // Arrow Up → input sebelumnya
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (idx > 0) inputs[idx - 1].focus();
        }

        // Backspace saat kosong → kembali ke atas
        if (e.key === 'Backspace' && this.value === '' && idx > 0) {
            e.preventDefault();
            inputs[idx - 1].focus();
        }
    });

    // Scroll ke input yang sedang aktif
    input.addEventListener('focus', function () {
        this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
});

// Timer countdown
const timer = setInterval(() => {
    if (!timerActive) return;
    timeLeft--;

    const pct = (timeLeft / totalTime) * 100;
    timerFill.style.width = pct + '%';
    timerText.textContent = timeLeft + 's';

    // Warna timer
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
</script>
@endpush