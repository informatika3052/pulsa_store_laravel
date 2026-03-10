@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan')
@section('page-title', 'Transaksi Penjualan Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
    <li class="breadcrumb-item active">Buat Transaksi</li>
@endsection

@section('content')
<form action="{{ route('sales.store') }}" method="POST" id="saleForm">
@csrf
<div class="row g-4">

    <!-- Kiri: Produk & Keranjang -->
    <div class="col-lg-8">
        <!-- Cari Produk -->
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-search me-2 text-primary"></i>Cari & Tambah Produk</h6>
            <div class="input-group mb-3">
                <span class="input-group-text bg-white"><i class="bi bi-upc-scan text-muted"></i></span>
                <input type="text" id="productSearch" class="form-control"
                    placeholder="Cari nama atau kode produk...">
            </div>
            <div id="productList" class="row g-2" style="max-height:280px;overflow-y:auto"></div>
        </div>

        <!-- Keranjang -->
        <div class="table-card">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart3 me-2 text-success"></i>Keranjang</h6>
                <span class="badge bg-primary" id="itemCount">0 item</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" id="cartTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center" width="120">Qty</th>
                            <th class="text-end" width="140">Harga</th>
                            <th class="text-end" width="140">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        <tr id="emptyCart">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-2 d-block mb-2"></i>
                                Keranjang masih kosong
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kanan: Info Transaksi -->
    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Info Pelanggan</h6>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Nama Pelanggan</label>
                <input type="text" name="customer_name" class="form-control"
                    placeholder="Pelanggan umum (opsional)">
            </div>
            <div>
                <label class="form-label small fw-semibold">No. HP Pelanggan</label>
                <input type="text" name="customer_phone" class="form-control" placeholder="08xxx">
            </div>
        </div>

        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Detail Pembayaran</h6>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="sale_date" class="form-control"
                    value="{{ date('Y-m-d') }}" required>
                @error('sale_date') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Metode Bayar <span class="text-danger">*</span></label>
                <select name="payment_method" class="form-select" required>
                    <option value="cash">💵 Cash / Tunai</option>
                    <option value="transfer">🏦 Transfer Bank</option>
                    <option value="qris">📱 QRIS</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Diskon (Rp)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="discount" id="discount" class="form-control"
                        value="0" min="0" step="500" onchange="recalculate()">
                </div>
            </div>

            <hr>

            <!-- Summary -->
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Subtotal:</span>
                <span class="fw-semibold" id="subtotalDisplay">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Diskon:</span>
                <span class="text-danger" id="discountDisplay">- Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-3 p-2 rounded" style="background:#f0fdf4">
                <span class="fw-bold text-success">TOTAL:</span>
                <span class="fw-bold text-success fs-5" id="grandTotalDisplay">Rp 0</span>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Bayar <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="paid_amount" id="paidAmount" class="form-control"
                        placeholder="0" min="0" required onchange="calcChange()">
                </div>
            </div>

            <div class="p-3 rounded mb-3" style="background:#eff6ff">
                <div class="d-flex justify-content-between">
                    <span class="small text-muted">Kembalian:</span>
                    <span class="fw-bold text-primary" id="changeDisplay">Rp 0</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Hidden inputs untuk items -->
            <div id="hiddenItems"></div>

            <button type="submit" class="btn btn-success w-100 btn-lg" id="submitBtn" disabled>
                <i class="bi bi-check-circle-fill me-2"></i>Simpan Transaksi
            </button>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')

@php
    
// Data produk dari server
$FormattedProducts = $products->map(function($p) {
    return [
        'id'       => $p->id,
        'name'     => $p->name,
        'code'     => $p->code,
        'price'    => $p->selling_price,
        'stock'    => $p->stock,
        'unit'     => $p->unit,
        'category' => $p->category->name,
        ];
        });
        
@endphp

<script>

const products = @json($FormattedProducts);
let cart = [];

// Search produk
document.getElementById('productSearch').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const filtered = query.length >= 1
        ? products.filter(p => p.name.toLowerCase().includes(query) || p.code.toLowerCase().includes(query))
        : products.slice(0, 12);
    renderProductList(filtered);
});

// Tampilkan produk awal
renderProductList(products.slice(0, 12));

function renderProductList(list) {
    const container = document.getElementById('productList');
    if (list.length === 0) {
        container.innerHTML = '<div class="col-12 text-center text-muted py-3">Produk tidak ditemukan</div>';
        return;
    }
    container.innerHTML = list.map(p => `
        <div class="col-6 col-md-4">
            <div class="card border h-100 product-item" onclick="addToCart(${p.id})" style="cursor:pointer;border-radius:10px;transition:.2s">
                <div class="card-body p-2">
                    <div class="fw-semibold" style="font-size:.78rem;line-height:1.3">${p.name}</div>
                    <div class="text-muted" style="font-size:.7rem">${p.code} · ${p.category}</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="text-success fw-bold" style="font-size:.8rem">Rp ${formatNumber(p.price)}</span>
                        <span class="badge ${p.stock <= 5 ? 'bg-danger' : 'bg-success-subtle text-success'}" style="font-size:.65rem">
                            Stok: ${p.stock}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    if (product.stock <= 0) {
        alert('Stok produk habis!');
        return;
    }
    const existing = cart.find(i => i.id === productId);
    if (existing) {
        if (existing.qty >= product.stock) {
            alert(`Stok tidak cukup! Stok tersisa: ${product.stock}`);
            return;
        }
        existing.qty++;
        existing.subtotal = existing.qty * existing.price;
    } else {
        cart.push({ id: product.id, name: product.name, price: product.price, qty: 1, subtotal: product.price, stock: product.stock });
    }
    renderCart();
}

function removeFromCart(productId) {
    cart = cart.filter(i => i.id !== productId);
    renderCart();
}

function updateQty(productId, newQty) {
    const item = cart.find(i => i.id === productId);
    if (!item) return;
    const qty = parseInt(newQty);
    if (qty <= 0) { removeFromCart(productId); return; }
    if (qty > item.stock) { alert(`Stok tidak cukup! Stok tersisa: ${item.stock}`); return; }
    item.qty = qty;
    item.subtotal = qty * item.price;
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartBody');
    const empty = document.getElementById('emptyCart');

    if (cart.length === 0) {
        tbody.innerHTML = `<tr id="emptyCart">
            <td colspan="5" class="text-center py-5 text-muted">
                <i class="bi bi-cart-x fs-2 d-block mb-2"></i>Keranjang masih kosong
            </td>
        </tr>`;
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('itemCount').textContent = '0 item';
    } else {
        tbody.innerHTML = cart.map(item => `
            <tr>
                <td>
                    <div class="fw-semibold" style="font-size:.875rem">${item.name}</div>
                    <div class="text-muted" style="font-size:.75rem">@ Rp ${formatNumber(item.price)}</div>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width:90px">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQty(${item.id}, ${item.qty - 1})">−</button>
                        <input type="number" class="form-control text-center p-0" value="${item.qty}"
                            onchange="updateQty(${item.id}, this.value)" min="1" max="${item.stock}">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQty(${item.id}, ${item.qty + 1})">+</button>
                    </div>
                </td>
                <td class="text-end" style="font-size:.875rem">Rp ${formatNumber(item.price)}</td>
                <td class="text-end fw-semibold" style="font-size:.875rem;color:#059669">Rp ${formatNumber(item.subtotal)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-light" onclick="removeFromCart(${item.id})">
                        <i class="bi bi-x text-danger"></i>
                    </button>
                </td>
            </tr>
        `).join('');
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('itemCount').textContent = `${cart.length} item`;
    }

    // Update hidden inputs
    const hiddenContainer = document.getElementById('hiddenItems');
    hiddenContainer.innerHTML = cart.map((item, idx) => `
        <input type="hidden" name="items[${idx}][product_id]" value="${item.id}">
        <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">
        <input type="hidden" name="items[${idx}][price]" value="${item.price}">
    `).join('');

    recalculate();
}

function recalculate() {
    const subtotal = cart.reduce((sum, i) => sum + i.subtotal, 0);
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const grandTotal = Math.max(0, subtotal - discount);

    document.getElementById('subtotalDisplay').textContent = 'Rp ' + formatNumber(subtotal);
    document.getElementById('discountDisplay').textContent = '- Rp ' + formatNumber(discount);
    document.getElementById('grandTotalDisplay').textContent = 'Rp ' + formatNumber(grandTotal);

    calcChange();
}

function calcChange() {
    const grandTotal = parseFloat(document.getElementById('grandTotalDisplay').textContent.replace(/[^0-9]/g, '')) || 0;
    const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
    const change = paid - grandTotal;
    document.getElementById('changeDisplay').textContent = 'Rp ' + formatNumber(Math.max(0, change));
    document.getElementById('changeDisplay').style.color = change < 0 ? '#dc2626' : '#1d4ed8';
}

function formatNumber(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}
</script>
@endpush