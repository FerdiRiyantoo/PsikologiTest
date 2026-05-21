@extends('layouts.app')
@section('title', 'Instruksi Tes Kraepelin')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7">
        <div class="card border-0 shadow rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="p-4 text-white text-center"
                 style="background:linear-gradient(135deg,#6d28d9,#7c3aed)">
                <i class="bi bi-calculator" style="font-size:2.5rem"></i>
                <h4 class="fw-bold mt-2 mb-1">Instruksi Tes Kraepelin</h4>
                <p class="mb-0" style="opacity:0.85; font-size:14px">
                    Baca dan pahami sebelum memulai tes
                </p>
            </div>

            <div class="card-body p-4">

                {{-- Sapa peserta --}}
                <div class="alert border-0 mb-4"
                     style="background:#f5f3ff; border-left:4px solid #7c3aed !important; border-radius:8px;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle fs-5" style="color:#6d28d9"></i>
                        <div>
                            <div class="fw-semibold">
                                Halo, {{ $testSession->accessRequest->name }}!
                            </div>
                            <small class="text-muted">
                                Anda akan mengerjakan Tes Kraepelin ({{ $config['total_columns'] }} kolom).
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Gambar instruksi --}}
                @if(file_exists(public_path('assets/instruksi-kraepelin.png')))
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/instruksi-kraepelin.png') }}"
                         alt="Instruksi Kraepelin"
                         style="width:100%; height:auto; max-height:320px;
                                object-fit:contain; border-radius:10px;
                                border:1px solid #e2e8f0; background:#faf5ff;">
                </div>
                @endif

                {{-- Contoh visual --}}
                <div class="p-3 rounded-3 mb-4"
                     style="background:#f5f3ff; border:1px solid #ddd6fe;">
                    <p class="fw-bold mb-3" style="color:#6d28d9; font-size:13px">
                        <i class="bi bi-lightbulb me-1"></i>Contoh Pengerjaan:
                    </p>
                    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
                        <div class="text-center">
                            <div style="font-size:32px; font-weight:900; color:#1e293b; line-height:1">7</div>
                            <div style="font-size:11px; color:#94a3b8">angka atas</div>
                        </div>
                        <div style="font-size:22px; color:#94a3b8; font-weight:300">+</div>
                        <div class="text-center">
                            <div style="font-size:32px; font-weight:900; color:#1e293b; line-height:1">8</div>
                            <div style="font-size:11px; color:#94a3b8">angka bawah</div>
                        </div>
                        <div style="font-size:22px; color:#94a3b8; font-weight:300">=</div>
                        <div class="text-center">
                            <div style="font-size:32px; font-weight:900; line-height:1">
                                <span style="color:#cbd5e1">1</span><span style="color:#6d28d9; text-decoration:underline">5</span>
                            </div>
                            <div style="font-size:11px; color:#94a3b8">hasil (15)</div>
                        </div>
                        <div style="font-size:22px; color:#94a3b8; font-weight:300">→</div>
                        <div class="text-center">
                            <div style="width:44px; height:40px; border:2px solid #6d28d9;
                                        border-radius:8px; background:#ede9fe;
                                        display:flex; align-items:center; justify-content:center;
                                        font-size:24px; font-weight:900; color:#6d28d9;">
                                5
                            </div>
                            <div style="font-size:11px; color:#94a3b8">tulis ini</div>
                        </div>
                    </div>
                    <p class="text-center mt-3 mb-0 text-muted" style="font-size:12px">
                        <strong>7 + 8 = 15</strong> → tulis digit terakhir yaitu <strong style="color:#6d28d9">5</strong>
                    </p>
                </div>

                {{-- Instruksi --}}
                <ul style="list-style:none; padding:0; margin:0 0 24px 0;">
                    @foreach([
                        ["Jumlahkan dua angka yang berdekatan, tulis <strong>digit terakhir</strong> dari hasilnya."],
                        ["Setiap kolom dikerjakan selama <strong>{$config['time_per_column']} detik</strong>. Timer berjalan otomatis."],
                        ["Gunakan <kbd>Enter</kbd> atau <kbd>↓</kbd> untuk pindah ke baris berikutnya."],
                        ["Jika waktu habis, sistem <strong>otomatis lanjut</strong> ke kolom berikutnya."],
                        ["Kerjakan <strong>secepat dan seteliti mungkin</strong>."],
                        ["Total <strong>{$config['total_columns']} kolom</strong> yang harus diselesaikan."],
                    ] as $i => $item)
                    <li style="display:flex; align-items:flex-start; gap:10px;
                                padding:10px 0; border-bottom:1px solid #f1f5f9;
                                font-size:14px; color:#374151;">
                        <span style="width:26px; height:26px; flex-shrink:0;
                                     background:linear-gradient(135deg,#6d28d9,#7c3aed);
                                     color:white; border-radius:50%; font-size:12px;
                                     font-weight:700; display:flex; align-items:center;
                                     justify-content:center; margin-top:1px;">
                            {{ $i + 1 }}
                        </span>
                        <span>{!! $item[0] !!}</span>
                    </li>
                    @endforeach
                </ul>

                {{-- Countdown & Tombol --}}
                <div class="text-center">
                    <div class="mb-3" id="countdownBox">
                        <div class="text-muted small mb-1">Tombol aktif dalam</div>
                        <div class="fw-bold fs-3" style="color:#6d28d9" id="countdownNum">10</div>
                        <div class="text-muted small">detik</div>
                    </div>

                    <form method="POST" action="{{ route('kraepelin.start') }}">
                        @csrf
                        <button type="submit"
                                id="btnMulai"
                                class="btn btn-lg px-5 py-3 rounded-3 fw-bold w-100"
                                style="background:linear-gradient(135deg,#6d28d9,#7c3aed);
                                       color:white; border:none;"
                                disabled>
                            <i class="bi bi-play-fill me-2"></i>
                            <span id="btnText">Mulai Tes (10s...)</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let sisa = 10;
const num = document.getElementById('countdownNum');
const btn = document.getElementById('btnMulai');
const txt = document.getElementById('btnText');
const box = document.getElementById('countdownBox');

const timer = setInterval(() => {
    sisa--;
    num.textContent = sisa;
    txt.textContent = `Mulai Tes (${sisa}s...)`;

    if (sisa <= 3) num.style.color = '#ef4444';

    if (sisa <= 0) {
        clearInterval(timer);
        btn.disabled    = false;
        txt.textContent = 'Mulai Tes Sekarang!';
        box.innerHTML   = '<div class="badge px-3 py-2 rounded-pill" ' +
                          'style="background:#ede9fe; color:#6d28d9; font-size:13px">' +
                          '<i class="bi bi-check-circle me-1"></i>Siap dimulai!</div>';
    }
}, 1000);
</script>
@endpush