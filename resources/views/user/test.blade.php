@extends('layouts.app')
@section('title', 'Tes Papikostick')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-9">

        {{-- Progress Bar --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">
                    Bagian {{ $page }} dari {{ $totalPages }}
                    &nbsp;·&nbsp;
                    {{ count($answered) }} / {{ $total }} soal terjawab
                </small>
                <small class="text-muted fw-semibold">{{ round($progress) }}%</small>
            </div>
            <div class="progress" style="height:10px; border-radius:8px">
                <div class="progress-bar bg-primary" style="width: {{ $progress }}%; transition: width 0.4s ease;"></div>
            </div>
        </div>

        {{-- Header Bagian --}}
        <div class="alert alert-primary py-2 px-3 mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check fs-5"></i>
            <span>Pilih <strong>satu pernyataan</strong> yang paling menggambarkan diri Anda pada setiap soal.</span>
        </div>

        {{-- Form 10 Soal --}}
        <form method="POST" action="{{ route('test.answer') }}" id="testForm">
            @csrf
            <input type="hidden" name="page" value="{{ $page }}">

            @foreach($pageQuestions as $no => $question)
            <div class="card shadow-sm mb-3 soal-card" id="soal-{{ $no }}">
                <div class="card-header d-flex justify-content-between align-items-center py-2
                    {{ in_array($no, $answered) ? 'bg-success text-white' : 'bg-light' }}">
                    <span class="fw-semibold">Soal {{ $no }}</span>
                    @if(in_array($no, $answered))
                        <i class="bi bi-check-circle-fill"></i>
                    @else
                        <span class="badge bg-warning text-dark">Belum dijawab</span>
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        {{-- Pilihan A --}}
                        <label class="option-label border rounded p-3 d-flex align-items-start gap-3"
                               style="cursor:pointer" data-soal="{{ $no }}">
                            <input type="radio"
                                   name="answers[{{ $no }}]"
                                   value="A"
                                   class="form-check-input mt-1 flex-shrink-0"
                                   required
                                   onchange="markAnswered({{ $no }}, this.closest('.soal-card'))">
                            <span>{{ $question['A'] }}</span>
                        </label>

                        {{-- Pilihan B --}}
                        <label class="option-label border rounded p-3 d-flex align-items-start gap-3"
                               style="cursor:pointer" data-soal="{{ $no }}">
                            <input type="radio"
                                   name="answers[{{ $no }}]"
                                   value="B"
                                   class="form-check-input mt-1 flex-shrink-0"
                                   onchange="markAnswered({{ $no }}, this.closest('.soal-card'))">
                            <span>{{ $question['B'] }}</span>
                        </label>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Tombol Navigasi --}}
            <div class="d-flex justify-content-between align-items-center mt-3 mb-5">
                @if($page > 1)
                    <a href="{{ route('test.index', ['page' => $page - 1]) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                    </a>
                @else
                    <div></div>
                @endif

                <button type="submit" class="btn btn-primary px-5 py-2" id="submitBtn">
                    @if($page < $totalPages)
                        Simpan & Lanjut <i class="bi bi-arrow-right ms-1"></i>
                    @else
                        <i class="bi bi-check-lg me-1"></i> Selesai & Lihat Hasil
                    @endif
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Highlight pilihan yang dipilih
document.querySelectorAll('.option-label').forEach(label => {
    label.addEventListener('click', function () {
        const soalNo = this.dataset.soal;
        // Reset semua label dalam soal yang sama
        document.querySelectorAll(`.option-label[data-soal="${soalNo}"]`).forEach(l => {
            l.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
        });
        // Highlight label ini
        this.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
    });
});

// Update header soal jadi hijau saat dijawab
function markAnswered(soalNo, card) {
    const header = card.querySelector('.card-header');
    header.classList.remove('bg-light', 'bg-warning');
    header.classList.add('bg-success', 'text-white');
    header.querySelector('.badge')?.remove();

    // Tambah centang jika belum ada
    if (!header.querySelector('.bi-check-circle-fill')) {
        const icon = document.createElement('i');
        icon.className = 'bi bi-check-circle-fill';
        header.appendChild(icon);
    }
}

// Validasi: semua soal harus dijawab sebelum submit
document.getElementById('testForm').addEventListener('submit', function(e) {
    const soalCards = document.querySelectorAll('.soal-card');
    let allAnswered = true;
    let firstUnanswered = null;

    soalCards.forEach(card => {
        const radios = card.querySelectorAll('input[type="radio"]');
        const isAnswered = Array.from(radios).some(r => r.checked);
        if (!isAnswered) {
            allAnswered = false;
            card.querySelector('.card-header').classList.add('border', 'border-danger');
            if (!firstUnanswered) firstUnanswered = card;
        }
    });

    if (!allAnswered) {
        e.preventDefault();
        firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
        alert('Harap jawab semua soal sebelum melanjutkan.');
    }
});
</script>
@endpush