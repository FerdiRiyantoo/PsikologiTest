@extends('layouts.admin')
@section('title', 'Manajemen Permintaan Akses')

@section('content')
<div class="container-fluid p-0">
    
    {{-- Header & Statistik Singkat --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Kandidat</h4>
            <p class="text-muted small mb-0">Kelola dan verifikasi permintaan akses peserta psikotes.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="bg-white px-3 py-2 rounded-3 shadow-sm border">
                <small class="text-muted d-block">Total Permintaan</small>
                <span class="fw-bold">{{ $requests->total() }}</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.requests.bulk') }}" id="bulkForm">
        @csrf
        
        {{-- Bulk Action Bar (Glassmorphism Effect) --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
            <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-3">
                        <i class="bi bi-check2-square me-2"></i>
                        <span class="fw-bold" id="selectedCount">0</span> <small>Terpilih</small>
                    </div>
                    <div class="vr mx-1 d-none d-md-block"></div>
                    <select name="action" class="form-select form-select-sm border-0 bg-light" style="width:200px; border-radius: 8px;" required>
                        <option value="">-- Pilih Aksi Massal --</option>
                        <option value="approve">Setujui & Kirim Kode</option>
                        <option value="reject">Tolak</option>
                        <option value="delete">Hapus Permanen</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary px-3 shadow-sm" style="border-radius: 8px;" onclick="return confirmBulk()">
                        Terapkan
                    </button>
                </div>
                
                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                    <button type="button" class="btn btn-sm btn-white border-end" onclick="selectAll()">Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-white" onclick="deselectAll()">Batal</button>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="50" class="ps-4">
                                <div class="form-check">
                                    <input type="checkbox" id="checkAll" class="form-check-input" onchange="toggleAll(this)">
                                </div>
                            </th>
                            <th class="text-muted small fw-bold text-uppercase">Kandidat</th>
                            <th class="text-muted small fw-bold text-uppercase">Posisi yang dilamar & Pendidikan</th>
                            <th class="text-muted small fw-bold text-uppercase">Jenis Tes</th> {{-- TAMBAHAN KOLOM HEADER --}}
                            <th class="text-muted small fw-bold text-uppercase">Status</th>
                            <th class="text-muted small fw-bold text-uppercase">Kode Akses</th>
                            <th class="text-muted small fw-bold text-uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                        <tr>
                            <td class="ps-4">
                                <div class="form-check">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $r->id }}" class="form-check-input row-check" onchange="updateCount()">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-light text-primary fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width:38px; height:38px; font-size: 14px;">
                                        {{ strtoupper(substr($r->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-0" style="font-size: 14px;">{{ $r->name }}</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $r->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium text-dark" style="font-size: 13px;">{{ $r->posisi_yang_dilamar ?? '-' }}</div>
                                <div class="text-muted" style="font-size: 11px;"><i class="bi bi-mortarboard me-1"></i>{{ $r->pendidikan_terakhir ?? '-' }} - {{ $r->jurusan ?? '-' }}</div>
                            </td>
                            {{-- TAMBAHAN DATA JENIS TES --}}
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill" style="font-size: 11px;">
                                    <i class="bi bi-journal-text me-1"></i> {{ $r->jenis_tes ?? 'Tidak Spesifik' }}
                                </span>
                            </td>
                            <td>
                                @if($r->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill" style="font-size: 11px;">
                                        <i class="bi bi-clock-history me-1"></i> Pending
                                    </span>
                                @elseif($r->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" style="font-size: 11px;">
                                        <i class="bi bi-check-circle me-1"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill" style="font-size: 11px;">
                                        <i class="bi bi-x-circle me-1"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($r->access_code)
                                    <span class="badge bg-light text-dark border px-2 py-2 font-monospace" style="font-size: 12px;">
                                        <i class="bi bi-key-fill text-success me-1"></i>{{ $r->access_code }}
                                    </span>
                                @else
                                    <span class="text-muted small">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($r->status === 'pending')
                                        <button type="submit" form="approve-form" formaction="{{ route('admin.requests.approve', $r->id) }}" class="btn btn-sm btn-outline-success border-0 rounded-3 p-2" title="Setujui">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                        <button type="submit" form="reject-form" formaction="{{ route('admin.requests.reject', $r->id) }}" class="btn btn-sm btn-outline-warning border-0 rounded-3 p-2" title="Tolak">
                                            <i class="bi bi-slash-circle-fill"></i>
                                        </button>
                                    @endif
                                    <button type="submit" form="delete-form" formaction="{{ route('admin.requests.destroy', $r->id) }}" class="btn btn-sm btn-outline-danger border-0 rounded-3 p-2" title="Hapus" onclick="return confirm('Hapus data {{ $r->name }}?')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5"> {{-- Ubah colspan dari 6 ke 7 --}}
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                <p class="text-muted mb-0">Tidak ada permintaan pendaftaran yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($requests->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </form>
</div>

{{-- Hidden forms for individual actions to solve nested form issue --}}
<form method="POST" id="approve-form" class="d-none">@csrf</form>
<form method="POST" id="reject-form" class="d-none">@csrf</form>
<form method="POST" id="delete-form" class="d-none">
    @csrf
    @method('DELETE')
</form>

<style>
    .avatar-sm { transition: all 0.3s ease; }
    tr:hover .avatar-sm { background: #38bdf8 !important; color: white !important; }
    .btn-white { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }
    .btn-white:hover { background: #f8fafc; color: #0f172a; }
    .table thead th { font-size: 11px; letter-spacing: 0.5px; }
    .form-check-input:checked { background-color: #38bdf8; border-color: #38bdf8; }
</style>
@endsection

@push('scripts')
<script>
    function toggleAll(source) {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = source.checked);
        updateCount();
    }
    function selectAll() {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = true);
        document.getElementById('checkAll').checked = true;
        updateCount();
    }
    function deselectAll() {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        document.getElementById('checkAll').checked = false;
        updateCount();
    }
    function updateCount() {
        const count = document.querySelectorAll('.row-check:checked').length;
        document.getElementById('selectedCount').textContent = count;
    }
    function confirmBulk() {
        const count  = document.querySelectorAll('.row-check:checked').length;
        const action = document.querySelector('select[name="action"]').value;
        if (!count) { alert('Pilih minimal 1 item terlebih dahulu.'); return false; }
        if (!action) { alert('Pilih aksi yang ingin diterapkan.'); return false; }
        return confirm(`Terapkan aksi massal ini pada ${count} kandidat?`);
    }
</script>
@endpush