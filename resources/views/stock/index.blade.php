@extends('layouts.app')

@section('title', 'Histori Stok')
@section('page-title', 'Keluar Masuk Stok')

@section('breadcrumb')
    <li class="breadcrumb-item active">Stok</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Form Penyesuaian Stok -->
    @if(auth()->user()->isAdmin())
    <div class="col-lg-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-4 pb-2 border-bottom">
                <i class="bi bi-sliders me-2 text-warning"></i>Penyesuaian Stok Manual
            </h6>
            <form action="{{ route('stock.adjust') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Produk <span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach(\App\Models\Product::active()->with('category')->orderBy('name')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jenis <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="in">📥 Stok Masuk</option>
                        <option value="out">📤 Stok Keluar</option>
                        <option value="adjustment">🔧 Penyesuaian (Opname)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" placeholder="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Alasan <span class="text-danger">*</span></label>
                    <input type="text" name="reason" class="form-control"
                        placeholder="Cth: Stok opname, retur, dll" required>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i>Simpan Penyesuaian
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Histori Stok -->
    <div class="col-lg-{{ auth()->user()->isAdmin() ? '8' : '12' }}">
        <div class="table-card">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Histori Keluar Masuk Stok</h6>
            </div>

            <!-- Filter -->
            <div class="p-3 border-bottom bg-light">
                <form action="{{ route('stock.index') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm" placeholder="Cari produk...">
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Semua Jenis</option>
                                <option value="in"         {{ request('type') == 'in'         ? 'selected' : '' }}>Masuk</option>
                                <option value="out"        {{ request('type') == 'out'        ? 'selected' : '' }}>Keluar</option>
                                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Stok Sebelum</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-center">Stok Sesudah</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adj)
                        <tr>
                            <td style="font-size:.8rem">
                                <div>{{ $adj->created_at->format('d M Y') }}</div>
                                <div class="text-muted">{{ $adj->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $adj->product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $adj->product->category->name }}</div>
                            </td>
                            <td class="text-center">
                                @if($adj->type == 'in')
                                    <span class="badge bg-success rounded-pill">📥 Masuk</span>
                                @elseif($adj->type == 'out')
                                    <span class="badge bg-danger rounded-pill">📤 Keluar</span>
                                @else
                                    <span class="badge bg-warning rounded-pill text-dark">🔧 Opname</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $adj->quantity_before }}</td>
                            <td class="text-center">
                                <span class="fw-bold {{ $adj->type == 'out' ? 'text-danger' : 'text-success' }}">
                                    {{ $adj->type == 'out' ? '-' : '+' }}{{ $adj->quantity_change }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold">{{ $adj->quantity_after }}</td>
                            <td style="font-size:.8rem">
                                <div>{{ $adj->reason }}</div>
                                @if($adj->reference)
                                <div class="text-muted"><code>{{ $adj->reference }}</code></div>
                                @endif
                            </td>
                            <td style="font-size:.8rem">{{ $adj->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-arrow-left-right fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">Belum ada histori stok</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($adjustments->hasPages())
            <div class="p-4 border-top d-flex align-items-center justify-content-between">
                <div class="text-muted small">
                    Menampilkan {{ $adjustments->firstItem() }}–{{ $adjustments->lastItem() }} dari {{ $adjustments->total() }} data
                </div>
                {{ $adjustments->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
