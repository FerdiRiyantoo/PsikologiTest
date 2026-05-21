@extends('layouts.app')
@section('title', 'Instruksi Tes PAPI-Kostick')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-7">
        <div class="card border-0 shadow rounded-4 overflow-hidden">

            {{-- Header --}}
            <div class="p-4 text-white text-center"
                 style="background:linear-gradient(135deg,#1d4ed8,#2563eb)">
                <i class="bi bi-clipboard-check" style="font-size:2.5rem"></i>
                <h4 class="fw-bold mt-2 mb-1">Instruksi Tes PAPI-Kostick</h4>
                <p class="mb-0" style="opacity:0.85; font-size:14px">
                    Baca dan pahami sebelum memulai tes
                </p>
            </div>

            <div class="card-body p-4">

                {{-- Sapa peserta --}}
                <div class="alert border-0 mb-4"
                     style="background:#eff6ff; border-left:4px solid #2563eb !important; border-radius:8px;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-primary fs-5"></i>
                        <div>
                            <div class="fw-semibold">
                                Halo, {{ $testSession->accessRequest->name }}!
                            </div>
                            <small class="text-muted">
                                Anda akan mengerjakan Tes Kepribadian PAPI-Kostick.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Gambar instruksi --}}
                @if(file_exists(public_path('assets/instruksi-papi.png')))
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/instruksi-papi.png') }}"
                         alt="Instruksi PAPI-Kostick"
                         style="width:100%; height:auto; max-height:320px;
                                object-fit:contain; border-radius:10px;
                                border:1px solid #e2e8f0; background:#f8faff;">
                </div>
                @endif

                {{-- Cara menjawab --}}
                <div class="p-3 rounded-3 mb-4"
                     style="background:#eff6ff; border:1px solid #bfdbfe;">
                    <p class="fw-bold mb-2" style="color:#1d4ed8; font-size:13px">
                        <i class="bi bi-lightbulb me-1"></i>Cara Menjawab:
                    </p>
                    <p class="mb-0 text-muted" style="font-size:13px; line-height:1.7">
                        Dari dua pernyataan yang tersedia, pilih
                        <strong>satu pernyataan</strong>
                        yang paling menggambarkan diri Anda.
                        <strong>Tidak ada jawaban benar atau salah.</strong>
                    </p>
                </div>

                {{-- Daftar instruksi --}}
                <ul style="list-style:none; padding:0; margin:0 0 24px 0;">
                    @foreach([
                        ['Setiap halaman berisi <strong>10 soal</strong> pilihan ganda berpasangan.'],
                        ['Pilih pernyataan yang <strong>paling mencerminkan</strong> kepribadian Anda.'],
                        ['Semua soal <strong>harus dijawab</strong> sebelum bisa melanjutkan ke halaman berikutnya.'],
                        ['Total terdapat <strong>90 soal</strong> yang dibagi dalam 9 halaman.'],
                        ['Jawab dengan <strong>jujur dan spontan</strong>, tidak perlu berpikir terlalu lama.'],
                        ['Tes hanya dapat dikerjakan <strong>satu kali</strong> dan tidak dapat diulang.'],
                    ] as $i => $item)
                    <li style="display:flex; align-items:flex-start; gap:10px;
                                padding:10px 0; border-bottom:1px solid #f1f5f9;
                                font-size:14px; color:#374151;">
                        <span style="width:26px; height:26px; flex-shrink:0;
                                     background:linear-gradient(135deg,#1d4ed8,#2563eb);
                                     color:white; border-radius:50%; font-size:12px;
                                     font-weight:700; display:flex; align-items:center;
                                     justify-content:center; margin-top:1px;">
                            {{ $i + 1 }}
                        </span>
                        <span>{!! $item[0] !!}</span>
                    </li>
                    @endforeach
                </ul>

                {{-- Countdown & Tombol Mulai --}}
                <div class="text-center">
                    <div class="mb-3" id="countdownBox">
                        <div class="text-muted small mb-1">Tombol aktif dalam</div>
                        <div class="fw-bold fs-3 text-primary" id="countdownNum">10</div>
                        <div class="text-muted small">detik</div>
                    </div>

                    <form method="POST" action="{{ route('test.start') }}">
                        @csrf
                        <button type="submit"
                                id="btnMulai"
                                class="btn btn-primary btn-lg px-5 py-3 rounded-3 fw-bold w-100"
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
const num    = document.getElementById('countdownNum');
const btn    = document.getElementById('btnMulai');
const txt    = document.getElementById('btnText');
const box    = document.getElementById('countdownBox');

const timer = setInterval(() => {
    sisa--;
    num.textContent = sisa;
    txt.textContent = `Mulai Tes (${sisa}s...)`;

    if (sisa <= 3) num.style.color = '#ef4444';

    if (sisa <= 0) {
        clearInterval(timer);
        btn.disabled        = false;
        txt.textContent     = 'Mulai Tes Sekarang!';
        box.innerHTML       = '<div class="badge bg-success px-3 py-2 rounded-pill">' +
                              '<i class="bi bi-check-circle me-1"></i>Siap dimulai!</div>';
        btn.style.animation = 'pulse 1s infinite';
    }
}, 1000);
</script>
@endpush