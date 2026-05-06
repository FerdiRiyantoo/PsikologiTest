@extends('layouts.app')
@section('title', 'Tes Selesai')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        {{-- Card Utama --}}
        <div class="card border-0 shadow text-center overflow-hidden">

            {{-- Banner Atas --}}
            <div class="bg-success py-5 px-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle"
                         style="width:80px; height:80px;">
                        <i class="bi bi-check-lg text-success" style="font-size:2.5rem"></i>
                    </div>
                </div>
                <h3 class="text-white fw-bold mb-1">Tes Selesai!</h3>
                <p class="text-white mb-0 opacity-75">
                    Anda telah menyelesaikan seluruh soal PAPI-Kostick
                </p>
            </div>

            <div class="card-body p-4">

                {{-- Info Peserta --}}
                <div class="bg-light rounded p-3 mb-4 text-start">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px">NAMA</div>
                            <div class="fw-semibold">{{ $testSession->accessRequest->name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px">EMAIL</div>
                            <div class="fw-semibold">{{ $testSession->accessRequest->email }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px">TANGGAL TES</div>
                            <div class="fw-semibold">
                                {{ $testSession->completed_at?->format('d F Y') ?? '-' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted" style="font-size:11px">DURASI</div>
                            <div class="fw-semibold">
                                @if($testSession->started_at && $testSession->completed_at)
                                    @php
                                        $durasi = $testSession->started_at->diff($testSession->completed_at);
                                    @endphp
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

                {{-- Pesan --}}
                <div class="alert alert-info py-2 text-start mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Hasil lengkap tes Anda akan dianalisis oleh tim kami.
                    Terima kasih telah mengikuti psikotes ini.
                </div>

                {{-- Tombol --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('home') }}" class="btn btn-primary py-2">
                        <i class="bi bi-house me-1"></i>Kembali ke Halaman Utama
                    </a>
                </div>

            </div>

            {{-- Footer Card --}}
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
    // Cegah user kembali ke halaman tes setelah selesai
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
    });
</script>
@endpush