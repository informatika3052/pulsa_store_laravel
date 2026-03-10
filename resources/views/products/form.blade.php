@extends('layouts.app')

@section('title', isset($product) ? 'Edit Barang' : 'Tambah Barang')
@section('page-title', isset($product) ? 'Edit Barang' : 'Tambah Barang Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">{{ isset($product) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
            method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Dasar
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Supplier</label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="">-- Pilih Supplier (Opsional) --</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                                {{ old('supplier_id', $product->supplier_id ?? '') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $product->code ?? '') }}"
                            class="form-control @error('code') is-invalid @enderror"
                            placeholder="Cth: PLT-001">
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Cth: Voucher Telkomsel 50.000">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                        <select name="unit" class="form-select @error('unit') is-invalid @enderror">
                            @foreach(['pcs', 'kartu', 'voucher', 'lembar', 'paket', 'unit'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit', $product->unit ?? 'pcs') == $unit ? 'selected' : '' }}>
                                {{ ucfirst($unit) }}
                            </option>
                            @endforeach
                        </select>
                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" rows="2"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Deskripsi produk (opsional)">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Harga & Stok -->
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-currency-dollar me-2 text-success"></i>Harga & Stok
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="purchase_price"
                                value="{{ old('purchase_price', $product->purchase_price ?? '') }}"
                                class="form-control @error('purchase_price') is-invalid @enderror"
                                placeholder="0" min="0" step="100">
                            @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="selling_price"
                                value="{{ old('selling_price', $product->selling_price ?? '') }}"
                                class="form-control @error('selling_price') is-invalid @enderror"
                                placeholder="0" min="0" step="100" id="sellingPrice">
                            @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div id="profitBadge" class="mt-1"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Stok Minimum</label>
                        <input type="number" name="min_stock"
                            value="{{ old('min_stock', $product->min_stock ?? 5) }}"
                            class="form-control @error('min_stock') is-invalid @enderror"
                            placeholder="5" min="0">
                        <div class="form-text">Alert jika stok di bawah angka ini</div>
                        @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Gambar & Status -->
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-image me-2 text-info"></i>Gambar & Status
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gambar Produk</label>
                        <input type="file" name="image" id="imageInput"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp"
                            onchange="previewImage(this)">
                        <div class="form-text">Max 2MB. Format: JPG, PNG, WEBP</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div id="imagePreview" class="mt-2">
                            @if(isset($product) && $product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                style="height:80px;border-radius:8px;object-fit:cover">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status Produk</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                id="isActive" value="1"
                                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Produk Aktif (bisa dijual)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Barang' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.innerHTML = `<img src="${e.target.result}" style="height:80px;border-radius:8px;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Hitung profit realtime
const sellingPrice = document.getElementById('sellingPrice');
const purchaseInput = document.querySelector('[name="purchase_price"]');

function updateProfit() {
    const hj = parseFloat(sellingPrice.value) || 0;
    const hb = parseFloat(purchaseInput.value) || 0;
    const profit = hj - hb;
    const badge = document.getElementById('profitBadge');
    if (hj > 0 && hb > 0) {
        badge.innerHTML = profit >= 0
            ? `<span class="badge bg-success-subtle text-success small">Margin: Rp ${profit.toLocaleString('id-ID')}</span>`
            : `<span class="badge bg-danger-subtle text-danger small">Rugi: Rp ${Math.abs(profit).toLocaleString('id-ID')}</span>`;
    } else {
        badge.innerHTML = '';
    }
}

sellingPrice.addEventListener('input', updateProfit);
purchaseInput.addEventListener('input', updateProfit);
updateProfit();
</script>
@endpush
