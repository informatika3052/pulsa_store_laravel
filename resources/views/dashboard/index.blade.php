@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon" style="background:#ede9fe">
                    <i class="bi bi-box-seam-fill text-purple" style="color:#7c3aed"></i>
                </div>
                <span class="badge bg-success-subtle text-success rounded-pill small">Aktif</span>
            </div>
            <h3 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h3>
            <p class="text-muted small mb-0">Total Produk</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon" style="background:#dbeafe">
                    <i class="bi bi-graph-up-arrow" style="color:#1d4ed8"></i>
                </div>
                <span class="badge bg-info-subtle text-info rounded-pill small">Bulan ini</span>
            </div>
            <h3 class="fw-bold mb-0" style="font-size:1.1rem">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</h3>
            <p class="text-muted small mb-0">Total Penjualan</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon" style="background:#fef3c7">
                    <i class="bi bi-graph-down-arrow" style="color:#d97706"></i>
                </div>
                <span class="badge bg-warning-subtle text-warning rounded-pill small">Bulan ini</span>
            </div>
            <h3 class="fw-bold mb-0" style="font-size:1.1rem">Rp {{ number_format($purchasesThisMonth, 0, ',', '.') }}</h3>
            <p class="text-muted small mb-0">Total Pembelian</p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon" style="background:#{{ $lowStockCount > 0 ? 'fee2e2' : 'd1fae5' }}">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#{{ $lowStockCount > 0 ? 'dc2626' : '059669' }}"></i>
                </div>
                @if($lowStockCount > 0)
                <span class="badge bg-danger rounded-pill small">Perhatian!</span>
                @endif
            </div>
            <h3 class="fw-bold mb-0">{{ $lowStockCount }}</h3>
            <p class="text-muted small mb-0">Produk Stok Rendah</p>
        </div>
    </div>
</div>

<!-- Charts + Tables Row -->
<div class="row g-4 mb-4">
    <!-- Chart Penjualan & Pembelian -->
    <div class="col-lg-8">
        <div class="table-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Grafik Penjualan & Pembelian</h6>
                <span class="badge bg-light text-muted">6 Bulan Terakhir</span>
            </div>
            <canvas id="salesChart" height="80"></canvas>
        </div>
    </div>

    <!-- Top Produk -->
    <div class="col-lg-4">
        <div class="table-card p-4 h-100">
            <h6 class="fw-bold mb-4">Top Produk Bulan Ini</h6>
            @forelse($topProducts as $index => $item)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width:32px;height:32px;font-size:.75rem;font-weight:700;flex-shrink:0">
                    {{ $index + 1 }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate" style="font-size:.875rem">{{ $item->product->name ?? '-' }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ number_format($item->total_qty) }} terjual</div>
                </div>
                <div class="text-end">
                    <div style="font-size:.8rem;font-weight:600;color:#059669">
                        Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @empty
            <p class="text-muted text-center small">Belum ada data penjualan</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Low Stock & Recent Transactions -->
<div class="row g-4">
    <!-- Stok Rendah -->
    @if($lowStockProducts->count() > 0)
    <div class="col-lg-5">
        <div class="table-card">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Stok Rendah
                </h6>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('reports.stock') }}?low_stock=1" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>Produk</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min</th>
                    </tr></thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $product->category->name }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $product->stock }}</span>
                            </td>
                            <td class="text-center text-muted">{{ $product->min_stock }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Transaksi Terakhir -->
    <div class="col-lg-{{ $lowStockProducts->count() > 0 ? '7' : '12' }}">
        <div class="table-card">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
                @if(auth()->user()->isAdmin() || auth()->user()->isKasir())
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th>No. Nota</th>
                        <th>Pelanggan</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                    </tr></thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-decoration-none" style="font-size:.875rem">
                                    {{ $sale->invoice_number }}
                                </a>
                                <div class="text-muted" style="font-size:.75rem">{{ $sale->sale_date->format('d M Y') }}</div>
                            </td>
                            <td style="font-size:.875rem">{{ $sale->customer_name ?? 'Umum' }}</td>
                            <td style="font-size:.875rem">{{ $sale->user->name }}</td>
                            <td class="text-end fw-semibold" style="font-size:.875rem">
                                Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success">Selesai</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Penjualan',
                data: {!! json_encode($chartData['sales']) !!},
                backgroundColor: 'rgba(79, 70, 229, 0.8)',
                borderRadius: 6,
                borderSkipped: false,
            },
            {
                label: 'Pembelian',
                data: {!! json_encode($chartData['purchases']) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderRadius: 6,
                borderSkipped: false,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: (context) => {
                        const val = context.raw;
                        return ` Rp ${new Intl.NumberFormat('id-ID').format(val)}`;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val)
                },
                grid: { color: '#f1f5f9' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
