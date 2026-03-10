@extends('layouts.app')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('page-title', isset($user) ? 'Edit User' : 'Tambah User Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
    <li class="breadcrumb-item active">{{ isset($user) ? 'Edit' : 'Tambah' }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}"
            method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-person-fill me-2 text-primary"></i>Informasi User
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama lengkap" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="email@example.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Password {{ isset($user) ? '(Kosongkan jika tidak diubah)' : '' }}
                            @if(!isset($user)) <span class="text-danger">*</span> @endif
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 karakter"
                                {{ !isset($user) ? 'required' : '' }}>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="form-control" placeholder="08xxx">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" rows="2" class="form-control"
                            placeholder="Alamat lengkap">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                id="isActive" value="1"
                                {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="isActive">
                                User Aktif (dapat login)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Role -->
            <div class="table-card p-4 mb-4" style="background:#fffbeb;border:1px solid #fde68a">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-warning"></i>Hak Akses per Role</h6>
                <div class="row g-2" style="font-size:.8rem">
                    <div class="col-md-4">
                        <span class="badge badge-role-admin me-1">Admin</span>
                        Akses penuh semua fitur
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-role-kasir me-1">Kasir</span>
                        Transaksi penjualan
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-role-gudang me-1">Gudang</span>
                        Pembelian & stok
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($user) ? 'Simpan Perubahan' : 'Tambah User' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
