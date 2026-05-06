@extends('layouts.admin')
@section('title', 'Analisis Hasil Tes')

@section('content')
<div class="container-fluid p-0">

    {{-- Stats Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4"
                 style="background: linear-gradient(45deg, #0f172a, #1e293b);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-white mb-1">Daftar Hasil Psikotes</h4>
                        <p class="text-white text-opacity-75 mb-0 small">
                            Menampilkan semua data peserta yang telah menyelesaikan ujian.
                        </p>
                    </div>
                    <div class="p-3 bg-white bg-opacity-10 rounded-3">
                        <i class="bi bi-file-earmark-bar-graph text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.results.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">

                    {{-- Search nama --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted text-uppercase"
                               style="letter-spacing:.5px">
                            <i class="bi bi-search me-1"></i>Cari Peserta
                        </label>
                        <input type="text"
                               name="search"
                               class="form-control rounded-3"
                               placeholder="Nama atau email..."
                               value="{{ request('search') }}">
                    </div>

                    {{-- Tanggal dari --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted text-uppercase"
                               style="letter-spacing:.5px">
                            <i class="bi bi-calendar3 me-1"></i>Dari Tanggal
                        </label>
                        <input type="date"
                               name="date_from"
                               class="form-control rounded-3"
                               value="{{ request('date_from') }}">
                    </div>

                    {{-- Tanggal sampai --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted text-uppercase"
                               style="letter-spacing:.5px">
                            <i class="bi bi-calendar3 me-1"></i>Sampai Tanggal
                        </label>
                        <input type="date"
                               name="date_to"
                               class="form-control rounded-3"
                               value="{{ request('date_to') }}">
                    </div>

                    {{-- Urutkan berdasarkan --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted text-uppercase"
                               style="letter-spacing:.5px">
                            <i class="bi bi-sort-down me-1"></i>Urutkan
                        </label>
                        <select name="sort_by" class="form-select rounded-3">
                            <option value="completed_at"
                                {{ request('sort_by', 'completed_at') === 'completed_at' ? 'selected' : '' }}>
                                Waktu Selesai
                            </option>
                            <option value="durasi"
                                {{ request('sort_by') === 'durasi' ? 'selected' : '' }}>
                                Durasi Pengerjaan
                            </option>
                        </select>
                    </div>

                    {{-- Arah urutan --}}
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted text-uppercase"
                               style="letter-spacing:.5px">
                            <i class="bi bi-arrow-down-up me-1"></i>Urutan
                        </label>
                        <select name="sort_order" class="form-select rounded-3">
                            <option value="desc"
                                {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>
                                Terbaru / Terlama
                            </option>
                            <option value="asc"
                                {{ request('sort_order') === 'asc' ? 'selected' : '' }}>
                                Terlama / Tercepat
                            </option>
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="col-md-1">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-3 w-100" title="Terapkan Filter">
                                <i class="bi bi-funnel-fill"></i>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Info filter aktif --}}
                @if(request()->hasAny(['search', 'date_from', 'date_to', 'sort_by', 'sort_order']))
                <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                    <small class="text-muted">Filter aktif:</small>

                    @if(request('search'))
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                        <i class="bi bi-search me-1"></i>{{ request('search') }}
                    </span>
                    @endif

                    @if(request('date_from'))
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                        <i class="bi bi-calendar me-1"></i>
                        Dari {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                    </span>
                    @endif

                    @if(request('date_to'))
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                        <i class="bi bi-calendar me-1"></i>
                        s/d {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                    </span>
                    @endif

                    <a href="{{ route('admin.results.index') }}"
                       class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i>Reset Filter
                    </a>
                </div>
                @endif

            </form>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white p-4 border-0">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">
                    Record Peserta
                    <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill">
                        {{ $sessions->total() }} data
                    </span>
                </h6>

                {{-- Tombol sort cepat di header tabel --}}
                <div class="d-flex gap-2 align-items-center">
                    <small class="text-muted">Urut:</small>
                    @php
                        $currentSort  = request('sort_by', 'completed_at');
                        $currentOrder = request('sort_order', 'desc');
                    @endphp

                    {{-- Toggle sort waktu selesai --}}
                    <a href="{{ route('admin.results.index', array_merge(request()->query(), [
                            'sort_by'    => 'completed_at',
                            'sort_order' => ($currentSort === 'completed_at' && $currentOrder === 'desc') ? 'asc' : 'desc'
                        ])) }}"
                       class="btn btn-sm rounded-3 {{ $currentSort === 'completed_at' ? 'btn-primary' : 'btn-light border' }}">
                        <i class="bi bi-calendar3 me-1"></i>Waktu
                        @if($currentSort === 'completed_at')
                            <i class="bi bi-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>

                    {{-- Toggle sort durasi --}}
                    <a href="{{ route('admin.results.index', array_merge(request()->query(), [
                            'sort_by'    => 'durasi',
                            'sort_order' => ($currentSort === 'durasi' && $currentOrder === 'desc') ? 'asc' : 'desc'
                        ])) }}"
                       class="btn btn-sm rounded-3 {{ $currentSort === 'durasi' ? 'btn-primary' : 'btn-light border' }}">
                        <i class="bi bi-stopwatch me-1"></i>Durasi
                        @if($currentSort === 'durasi')
                            <i class="bi bi-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i>
                        @endif
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-muted small fw-bold text-uppercase py-3">
                            Nama Peserta
                        </th>
                        <th class="text-muted small fw-bold text-uppercase py-3">
                            <a href="{{ route('admin.results.index', array_merge(request()->query(), [
                                    'sort_by'    => 'durasi',
                                    'sort_order' => ($currentSort === 'durasi' && $currentOrder === 'desc') ? 'asc' : 'desc'
                                ])) }}"
                               class="text-muted text-decoration-none d-flex align-items-center gap-1">
                                Durasi
                                @if($currentSort === 'durasi')
                                    <i class="bi bi-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="bi bi-arrow-down-up opacity-25"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-muted small fw-bold text-uppercase py-3">
                            <a href="{{ route('admin.results.index', array_merge(request()->query(), [
                                    'sort_by'    => 'completed_at',
                                    'sort_order' => ($currentSort === 'completed_at' && $currentOrder === 'desc') ? 'asc' : 'desc'
                                ])) }}"
                               class="text-muted text-decoration-none d-flex align-items-center gap-1">
                                Waktu Selesai
                                @if($currentSort === 'completed_at')
                                    <i class="bi bi-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                @else
                                    <i class="bi bi-arrow-down-up opacity-25"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center text-muted small fw-bold text-uppercase py-3">Status</th>
                        <th class="text-center text-muted small fw-bold text-uppercase py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sessions as $s)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-box me-3">
                                {{ strtoupper(substr($s->accessRequest->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark mb-0" style="font-size:14px">
                                    {{ $s->accessRequest->name }}
                                </div>
                                <div class="text-muted small">{{ $s->accessRequest->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        @if($s->started_at && $s->completed_at)
                        @php
                            $seconds        = $s->started_at->diffInSeconds($s->completed_at);
                            $menit          = floor($seconds / 60);
                            $detik          = $seconds % 60;
                            $decimalMinutes = number_format($seconds / 60, 1);
                        @endphp
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-stopwatch text-primary"></i>
                            <div>
                                <span class="small fw-semibold">{{ $decimalMinutes }}</span>
                                <span class="text-muted" style="font-size:10px"> menit</span>
                                <div class="text-muted" style="font-size:10px">
                                    ({{ $menit }}m {{ $detik }}s)
                                </div>
                            </div>
                        </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>

                    <td>
                        <div class="small fw-semibold text-dark">
                            {{ $s->completed_at?->format('d M Y') ?? '-' }}
                        </div>
                        <div class="text-muted" style="font-size:11px">
                            Pukul {{ $s->completed_at?->format('H:i') ?? '-' }} WIB
                        </div>
                    </td>

                    <td class="text-center">
                        @if($s->completed_at)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"
                                  style="font-size:11px">
                                <i class="bi bi-check-circle me-1"></i>Selesai
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2"
                                  style="font-size:11px">
                                <i class="bi bi-hourglass-split me-1"></i>Berlangsung
                            </span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                            <a href="{{ route('admin.results.show', $s->id) }}"
                               class="btn btn-sm btn-white border-end px-3"
                               title="Lihat Detail">
                                <i class="bi bi-eye-fill text-primary"></i>
                            </a>
                            <a href="{{ route('admin.results.pdf', $s->id) }}"
                               class="btn btn-sm btn-white px-3"
                               title="Unduh PDF"
                               target="_blank">
                                <i class="bi bi-file-pdf-fill text-danger"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                            <div class="fw-semibold">Tidak ada data ditemukan</div>
                            <small>Coba ubah filter pencarian Anda</small>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $sessions->firstItem() }}–{{ $sessions->lastItem() }}
                    dari {{ $sessions->total() }} data
                </small>
                {{ $sessions->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .avatar-box {
        width: 40px; height: 40px;
        background: #f1f5f9;
        color: #38bdf8;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px;
        font-weight: 800; font-size: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .btn-white {
        background: #ffffff;
        color: #64748b;
    }
    .btn-white:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .table thead th {
        letter-spacing: 0.5px;
        font-size: 11px;
    }
    tr:hover .avatar-box {
        background: #38bdf8;
        color: white;
        border-color: #38bdf8;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
</style>
@endsection