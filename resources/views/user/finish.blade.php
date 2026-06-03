@extends('layouts.app')
@section('title', 'Tes Selesai')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        @php
            $jenisTes    = strtolower($testSession->accessRequest->jenis_tes ?? 'papi');
            $isKraepelin = in_array($jenisTes, ['krempelin', 'kraepelin']);
        @endphp

        <div class="card border-0 shadow text-center overflow-hidden">

            {{-- Banner --}}
            <div class="py-5 px-4"
                 style="background:{{ $isKraepelin
                    ? 'linear-gradient(135deg,#6d28d9,#7c3aed)'
                    : 'linear-gradient(135deg,#16a34a,#15803d)' }}">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle"
                         style="width:90px; height:90px;">
                        <i class="bi bi-check-lg"
                           style="font-size:2.8rem; color:{{ $isKraepelin ? '#6d28d9' : '#16a34a' }}"></i>
                    </div>
                </div>
                <h3 class="text-white fw-bold mb-2">Tes Selesai!</h3>
                <p class="text-white mb-0" style="opacity:0.85; font-size:15px">
                    Anda telah menyelesaikan tes
                    <strong>{{ $isKraepelin ? 'Kraepelin' : 'PAPI-Kostick' }}</strong>
                </p>
            </div>

            <div class="card-body p-4">

                {{-- Info Peserta --}}
                <div class="bg-light rounded-3 p-3 mb-4 text-start">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px">Nama</div>
                            <div class="fw-semibold text-dark">{{ $testSession->accessRequest->name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px">Jenis Tes</div>
                            <div>
                                <span class="badge rounded-pill px-3 py-1"
                                      style="background:{{ $isKraepelin ? '#6d28d9' : '#2563eb' }}; color:white; font-size:12px">
                                    {{ $isKraepelin ? 'Kraepelin' : 'PAPI-Kostick' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px">Tanggal</div>
                            <div class="fw-semibold text-dark">
                                {{ $testSession->completed_at?->format('d F Y') ?? '-' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px">Durasi</div>
                            <div class="fw-semibold text-dark">
                                @if($testSession->started_at && $testSession->completed_at)
                                    @php $durasi = $testSession->started_at->diff($testSession->completed_at); @endphp
                                    @if($durasi->h > 0)
                                        {{ $durasi->h }} jam {{ $durasi->i }} menit
                                    @else
                                        {{ $durasi->i }} menit {{ $durasi->s }} detik
                                    @endif
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pesan Selesai --}}
                <div class="p-4 rounded-3 mb-4"
                     style="background:{{ $isKraepelin ? '#f5f3ff' : '#f0fdf4' }};
                            border:1px solid {{ $isKraepelin ? '#ddd6fe' : '#bbf7d0' }};">
                    <i class="bi bi-shield-check mb-2 d-block"
                       style="font-size:2rem; color:{{ $isKraepelin ? '#6d28d9' : '#16a34a' }}"></i>
                    <p class="fw-semibold mb-1"
                       style="color:{{ $isKraepelin ? '#6d28d9' : '#15803d' }}">
                        Jawaban Anda Telah Tersimpan
                    </p>
                    <p class="text-muted mb-0" style="font-size:13px; line-height:1.7">
                        Hasil tes Anda akan dianalisis oleh tim kami.
                        Harap tunggu informasi lebih lanjut dari pihak penyelenggara.
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="d-grid">
                    <a href="{{ route('home') }}" class="btn py-2 fw-semibold text-white"
                       style="background:{{ $isKraepelin
                            ? 'linear-gradient(135deg,#6d28d9,#7c3aed)'
                            : 'linear-gradient(135deg,#16a34a,#15803d)' }}">
                        <i class="bi bi-house me-1"></i>Kembali ke Halaman Utama
                    </a>
                </div>

            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light py-2">
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Diselesaikan pada {{ $testSession->completed_at?->format('d/m/Y H:i') ?? '-' }} WIB
                </small>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
    });
</script>
@endpush