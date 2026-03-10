@extends('layouts.app')

@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page-title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Supplier</a></li>
    <li class="breadcrumb-item active">{{ isset($supplier) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <form action="{{ isset($supplier) ? route('suppliers.update', $supplier) : route('suppliers.store') }}"
            method="POST">
            @csrf
            @if(isset($supplier)) @method('PUT') @endif

            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-truck me-2 text-primary"></i>Informasi Supplier
                </h6>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Nama Supplier <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name"
                            value="{{ old('name', $supplier->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama perusahaan / toko supplier" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Person</label>
                        <input type="text" name="contact_person"
                            value="{{ old('contact_person', $supplier->contact_person ?? '') }}"
                            class="form-control @error('contact_person') is-invalid @enderror"
                            placeholder="Nama PIC / narahubung">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone"
                                value="{{ old('phone', $supplier->phone ?? '') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="08xxx / 021xxx">
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email"
                                value="{{ old('email', $supplier->email ?? '') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="email@supplier.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Alamat lengkap supplier...">{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('suppliers.index') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
