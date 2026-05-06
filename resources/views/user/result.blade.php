@extends('layouts.app')
@section('title', 'Hasil Tes Anda')
@section('content')
<div class="row justify-content-center">
<div class="col-md-10">

    <div class="card border-success mb-4">
        <div class="card-body text-center py-4">
            <i class="bi bi-check-circle-fill text-success fs-1"></i>
            <h4 class="fw-bold mt-2">Tes Selesai!</h4>
            <p class="text-muted mb-0">Terima kasih, <strong>{{ $testSession->accessRequest->name }}</strong>. Berikut hasil tes Anda.</p>
        </div>
    </div>

    @if($testSession->result)
    <div class="card mb-4">
        <div class="card-header fw-semibold">Grafik Profil Kepribadian PAPI-Kostick</div>
        <div class="card-body">
            <canvas id="papiChart" height="90"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header fw-semibold">Skor per Skala</div>
        <div class="card-body">
            <div class="row g-2">
                @foreach($testSession->result->getScalesArray() as $scale => $score)
                <div class="col-md-2 col-4">
                    <div class="border rounded p-2 text-center">
                        <div class="fw-bold fs-3 text-primary">{{ $score }}</div>
                        <div class="fw-semibold">{{ $scale }}</div>
                        <div class="progress mt-1" style="height:5px">
                            <div class="progress-bar" style="width:{{ ($score/9)*100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('papiChart'), {
    type: 'bar',
    data: {
        labels: ['N','G','A','L','P','I','T','V','X','S','B','O','R','D','C','Z','E','K','F','W'],
        datasets: [{
            label: 'Skor',
            data: [
                {{ $testSession->result->scale_n ?? 0 }},
                {{ $testSession->result->scale_g ?? 0 }},
                {{ $testSession->result->scale_a ?? 0 }},
                {{ $testSession->result->scale_l ?? 0 }},
                {{ $testSession->result->scale_p ?? 0 }},
                {{ $testSession->result->scale_i ?? 0 }},
                {{ $testSession->result->scale_t ?? 0 }},
                {{ $testSession->result->scale_v ?? 0 }},
                {{ $testSession->result->scale_x ?? 0 }},
                {{ $testSession->result->scale_s ?? 0 }},
                {{ $testSession->result->scale_b ?? 0 }},
                {{ $testSession->result->scale_o ?? 0 }},
                {{ $testSession->result->scale_r ?? 0 }},
                {{ $testSession->result->scale_d ?? 0 }},
                {{ $testSession->result->scale_c ?? 0 }},
                {{ $testSession->result->scale_z ?? 0 }},
                {{ $testSession->result->scale_e ?? 0 }},
                {{ $testSession->result->scale_k ?? 0 }},
                {{ $testSession->result->scale_f ?? 0 }},
                {{ $testSession->result->scale_w ?? 0 }},
            ],
            backgroundColor: 'rgba(13,110,253,0.7)',
        }]
    },
    options: { scales: { y: { min: 0, max: 9, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
@endsection