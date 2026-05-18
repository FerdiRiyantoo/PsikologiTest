@extends('layouts.app')
@section('title', 'Tes Selesai')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        @php
            $jenisTes = strtolower($testSession->accessRequest->jenis_tes ?? 'papi');
            $isKraepelin = in_array($jenisTes, ['krempelin', 'kraepelin']);
        @endphp

        {{-- Card Utama --}}
        <div class="card border-0 shadow text-center overflow-hidden">

            {{-- Banner --}}
            <div class="py-5 px-4" style="background:{{ $isKraepelin ? 'linear-gradient(135deg,#6d28d9,#7c3aed)' : 'linear-gradient(135deg,#16a34a,#15803d)' }}">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle"
                         style="width:80px; height:80px;">
                        <i class="bi bi-check-lg fs-1"
                           style="color:{{ $isKraepelin ? '#6d28d9' : '#16a34a' }}"></i>
                    </div>
                </div>
                <h3 class="text-white fw-bold mb-1">Tes Selesai!</h3>
                <p class="text-white mb-0" style="opacity:0.85">
                    Anda telah menyelesaikan tes
                    <strong>{{ $isKraepelin ? 'Kraepelin' : 'PAPI-Kostick' }}</strong>
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
                            <div class="text-muted" style="font-size:11px">JENIS TES</div>
                            <div class="fw-semibold">
                                <span class="badge rounded-pill px-3"
                                      style="background:{{ $isKraepelin ? '#6d28d9' : '#2563eb' }}; color:white">
                                    {{ $isKraepelin ? 'Kraepelin' : 'PAPI-Kostick' }}
                                </span>
                            </div>
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
                                    @php $durasi = $testSession->started_at->diff($testSession->completed_at); @endphp
                                    @if($durasi->h > 0)
                                        {{ $durasi->h }} jam {{ $durasi->i }} menit
                                    @else
                                        {{ $durasi->i }} menit {{ $durasi->s }} detik
                                    @endif
                                @else - @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Hasil --}}
                @if($isKraepelin && $testSession->kraepelinResult)
                @php $kr = $testSession->kraepelinResult; @endphp
                <div class="mb-4">
                    <p class="text-muted mb-2" style="font-size:13px">
                        <i class="bi bi-bar-chart me-1"></i>
                        <strong>Ringkasan Hasil Kraepelin:</strong>
                    </p>
                    <div class="row g-2">
                        <div class="col-3">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-purple" style="font-size:1.4rem; color:#6d28d9">
                                    {{ number_format($kr->pace_score, 1) }}
                                </div>
                                <div style="font-size:11px" class="text-muted">Kecepatan</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-success" style="font-size:1.4rem">
                                    {{ number_format($kr->accuracy_score, 1) }}%
                                </div>
                                <div style="font-size:11px" class="text-muted">Ketelitian</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-warning" style="font-size:1.4rem">
                                    {{ number_format($kr->endurance_score, 1) }}
                                </div>
                                <div style="font-size:11px" class="text-muted">Ketahanan</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-danger" style="font-size:1.4rem">
                                    {{ number_format($kr->stability_score, 1) }}
                                </div>
                                <div style="font-size:11px" class="text-muted">Keajegan</div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif(!$isKraepelin && $testSession->result)
                @php
                    $scales  = $testSession->result->getScalesArray();
                    $highest = collect($scales)->sortDesc()->take(3);
                @endphp
                <div class="mb-4">
                    <p class="text-muted mb-2" style="font-size:13px">
                        <i class="bi bi-bar-chart me-1"></i>
                        <strong>3 Skala Tertinggi PAPI-Kostick:</strong>
                    </p>
                    <div class="row g-2 justify-content-center">
                        @foreach($highest as $scale => $score)
                        <div class="col-4">
                            <div class="border rounded p-2 text-center">
                                <div class="fw-bold text-primary" style="font-size:1.8rem; line-height:1">
                                    {{ $score }}
                                </div>
                                <div class="fw-semibold" style="font-size:13px">Skala {{ $scale }}</div>
                                <div class="progress mt-1" style="height:4px">
                                    <div class="progress-bar bg-primary"
                                         style="width:{{ ($score/9)*100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Pesan --}}
                <div class="alert alert-info py-2 text-start mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Hasil lengkap tes Anda akan dianalisis oleh tim kami.
                    Terima kasih telah mengikuti psikotes ini.
                </div>

                {{-- Tombol --}}
                <div class="d-grid">
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
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
    });
</script>
@endpush