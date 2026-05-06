@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
<div class="container-fluid p-0">
    
    {{-- Header Welcome Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white p-4 rounded-4 shadow-sm border-0 d-flex align-items-center justify-content-between" 
                 style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);">
                <div>
                    <h4 class="fw-800 mb-1" style="color: #0f172a;">Halo, {{ session('admin_name') }}! 👋</h4>
                    <p class="text-muted mb-0 small">Berikut adalah ringkasan data pendaftaran psikotes hari ini.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <div class="fw-bold text-primary mb-0" style="font-size: 1.5rem;">{{ now()->format('H:i') }}</div>
                    <div class="small text-muted">{{ now()->isoFormat('D MMMM Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards Section --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 rounded-3 bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-send-fill fs-4"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success small">Total</span>
                    </div>
                    <h2 class="fw-800 mb-1">{{ number_format(data_get($stats, 'total_requests', 0)) }}</h2>
                    <p class="text-muted small mb-0 fw-medium">Permintaan Masuk</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 rounded-3 bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning small">Waiting</span>
                    </div>
                    <h2 class="fw-800 mb-1">{{ number_format(data_get($stats, 'pending', 0)) }}</h2>
                    <p class="text-muted small mb-0 fw-medium">Menunggu Review</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 rounded-3 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-check-all fs-4"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary small">Selesai</span>
                    </div>
                    <h2 class="fw-800 mb-1">{{ number_format(data_get($stats, 'completed_tests', 0)) }}</h2>
                    <p class="text-muted small mb-0 fw-medium">Tes Selesai</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="p-3 rounded-3 bg-info bg-opacity-10 text-info">
                            <i class="bi bi-lightning-charge-fill fs-4"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info small">Aktif</span>
                    </div>
                    <h2 class="fw-800 mb-1">{{ number_format(data_get($stats, 'in_progress', 0)) }}</h2>
                    <p class="text-muted small mb-0 fw-medium">Sedang Berlangsung</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Requests Table Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-0 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0">Permintaan Terbaru</h5>
                        <p class="text-muted small mb-0">Data 5 pendaftaran terakhir yang masuk ke sistem.</p>
                    </div>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 py-3 small text-uppercase text-muted fw-bold">Nama Kandidat</th>
                                <th class="border-0 py-3 small text-uppercase text-muted fw-bold">Status Akses</th>
                                <th class="border-0 py-3 small text-uppercase text-muted fw-bold">Waktu Daftar</th>
                                <th class="border-0 py-3 small text-uppercase text-muted fw-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $r)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="width:35px; height:35px; border-radius:10px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-weight:700; color:#38bdf8;">
                                                {{ strtoupper(substr($r->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold mb-0" style="font-size:0.9rem;">{{ $r->name }}</div>
                                                <div class="text-muted small">{{ $r->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($r->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill" style="font-size:0.75rem;">
                                                <i class="bi bi-clock me-1"></i> Pending
                                            </span>
                                        @elseif($r->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" style="font-size:0.75rem;">
                                                <i class="bi bi-check-circle me-1"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill" style="font-size:0.75rem;">
                                                <i class="bi bi-x-circle me-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $r->created_at->diffForHumans() }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-light rounded-3">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted small">Belum ada data permintaan terbaru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .card { transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); }
    .table thead th { letter-spacing: 0.5px; }
</style>
@endsection