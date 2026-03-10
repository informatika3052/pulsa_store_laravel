@extends('layouts.app')

@section('title', 'Pembelian Baru')
@section('page-title', 'Input Pembelian Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
    <li class="breadcrumb-item active">Baru</li>
@endsection

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
@csrf
<div class="row g-4">
    <!-- Kiri: Pilih Produk -->
    <div class="col-lg-8">
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-search me-2 text-primary"></i>Cari & Tambah Produk</h6>
            <input type="text" id="productSearch" class="form-control mb-3" placeholder="Cari nama atau kode produk...">
            <div id="productList" class="row g-2" style="max-height:260px;overflow-y:auto"></div>
        </div>

        <!-- Keranjang Pembelian -->
        <div class="table-card">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-bag me-2 text-warning"></i>Daftar Barang Dibeli</h6>
                <span class="badge bg-warning text-dark" id="itemCount">0 item</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center" width="130">Qty</th>
                            <th class="text-end" width="160">Harga Beli</th>
                            <th class="text-end" width="140">Subtotal</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        <tr id="emptyCart">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-bag-x fs-2 d-block mb-2"></i>Belum ada produk dipilih
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kanan: Info Pembelian -->
    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Info Pembelian</h6>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Tanggal Pembelian <span class="text-danger">*</span></label>
                <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Diskon (Rp)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="discount" id="discount" class="form-control"
                        value="0" min="0" onchange="recalculate()">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Catatan</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan pembelian..."></textarea>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Subtotal:</span>
                <span class="fw-semibold" id="subtotalDisplay">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-3 p-2 rounded" style="background:#eff6ff">
                <span class="fw-bold text-primary">TOTAL:</span>
                <span class="fw-bold text-primary fs-5" id="grandTotalDisplay">Rp 0</span>
            </div>

            <div id="hiddenItems"></div>

            <button type="submit" class="btn btn-warning w-100 btn-lg fw-bold" id="submitBtn" disabled>
                <i class="bi bi-check-circle-fill me-2"></i>Simpan Pembelian
            </button>
        </div>
    </div>
</div>
</form>
@endsection



@push('scripts')
@php
$formattedProducts = $products->map(function($p) {
    return [
        'id'       => $p->id,
        'name'     => $p->name,
        'code'     => $p->code,
        'price'    => $p->selling_price, // Ubah dari purchase_price ke selling_price
        'stock'    => $p->stock,
        'unit'     => $p->unit,
        'category' => $p->category->name,
    ];
});
@endphp

<script>
const products = @json($formattedProducts);

let cart = [];

document.getElementById('productSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    const filtered = q.length >= 1
        ? products.filter(p => p.name.toLowerCase().includes(q) || p.code.toLowerCase().includes(q))
        : products.slice(0, 12);
    renderProductList(filtered);
});

renderProductList(products.slice(0, 12));

function renderProductList(list) {
    const c = document.getElementById('productList');
    if (!list.length) { 
        c.innerHTML = '<div class="col-12 text-center text-muted py-3">Tidak ditemukan</div>'; 
        return; 
    }
    c.innerHTML = list.map(p => `
        <div class="col-6 col-md-4">
            <div class="card border h-100" onclick="addToCart(${p.id})" style="cursor:pointer;border-radius:10px">
                <div class="card-body p-2">
                    <div class="fw-semibold" style="font-size:.78rem">${p.name}</div>
                    <div class="text-muted" style="font-size:.7rem">${p.code} · ${p.category}</div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-primary fw-bold" style="font-size:.8rem">Rp ${fmt(p.price)}</span>
                        <span class="badge bg-light text-muted border" style="font-size:.65rem">Stok: ${p.stock}</span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function addToCart(id) {
    const p = products.find(x => x.id === id);
    const ex = cart.find(x => x.id === id);
    if (ex) { 
        ex.qty++; 
        ex.subtotal = ex.qty * ex.price; 
    } else { 
        cart.push({ 
            id: p.id, 
            name: p.name, 
            price: p.price, // Gunakan p.price, bukan p.purchase_price
            qty: 1, 
            subtotal: p.price, 
            unit: p.unit 
        }); 
    }
    renderCart();
}

function removeFromCart(id) { 
    cart = cart.filter(x => x.id !== id); 
    renderCart(); 
}

function updateQty(id, val) {
    const item = cart.find(x => x.id === id);
    const qty = parseInt(val);
    if (!item || qty <= 0) { 
        removeFromCart(id); 
        return; 
    }
    item.qty = qty; 
    item.subtotal = qty * item.price;
    renderCart();
}

function updatePrice(id, val) {
    const item = cart.find(x => x.id === id);
    if (!item) return;
    item.price = parseFloat(val) || 0;
    item.subtotal = item.qty * item.price;
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cartBody');
    if (!cart.length) {
        tbody.innerHTML = `<tr id="emptyCart"><td colspan="5" class="text-center py-5 text-muted">
            <i class="bi bi-bag-x fs-2 d-block mb-2"></i>Belum ada produk dipilih</td></tr>`;
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('itemCount').textContent = '0 item';
    } else {
        tbody.innerHTML = cart.map(item => `
            <tr>
                <td><div class="fw-semibold" style="font-size:.875rem">${item.name}</div></td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width:95px">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${item.id}, ${item.qty-1})">−</button>
                        <input type="number" class="form-control text-center p-0" value="${item.qty}" onchange="updateQty(${item.id}, this.value)" min="1">
                        <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${item.id}, ${item.qty+1})">+</button>
                    </div>
                </td>
                <td class="text-end">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control text-end" value="${item.price}" onchange="updatePrice(${item.id}, this.value)" min="0">
                    </div>
                </td>
                <td class="text-end fw-semibold" style="color:#1d4ed8">Rp ${fmt(item.subtotal)}</td>
                <td><button type="button" class="btn btn-sm btn-light" onclick="removeFromCart(${item.id})">
                    <i class="bi bi-x text-danger"></i></button></td>
            </tr>
        `).join('');
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('itemCount').textContent = `${cart.length} item`;
    }

    document.getElementById('hiddenItems').innerHTML = cart.map((item, i) => `
        <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
        <input type="hidden" name="items[${i}][quantity]" value="${item.qty}">
        <input type="hidden" name="items[${i}][price]" value="${item.price}">
    `).join('');

    recalculate();
}

function recalculate() {
    const sub = cart.reduce((s, i) => s + i.subtotal, 0);
    const disc = parseFloat(document.getElementById('discount').value) || 0;
    document.getElementById('subtotalDisplay').textContent = 'Rp ' + fmt(sub);
    document.getElementById('grandTotalDisplay').textContent = 'Rp ' + fmt(Math.max(0, sub - disc));
}

function fmt(n) { 
    return new Intl.NumberFormat('id-ID').format(n); 
}
</script>
@endpush