@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Data Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Karyawan</a></li>
    <li class="breadcrumb-item active">Edit: {{ $employee->name }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Data Pribadi --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-person-fill me-2 text-primary"></i>Data Pribadi
                </h6>
                <div class="row g-3">

                    <!-- Kode Karyawan (readonly) -->
                    <div class="col-12">
                        <div class="p-3 rounded d-flex align-items-center gap-3"
                            style="background:#f8fafc;border:1px dashed #cbd5e1">
                            <i class="bi bi-info-circle text-muted"></i>
                            <span class="small text-muted">
                                Kode Karyawan: <strong class="text-primary">{{ $employee->employee_code }}</strong>
                                (tidak dapat diubah)
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name"
                            value="{{ old('name', $employee->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama lengkap karyawan" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Jabatan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="position"
                            value="{{ old('position', $employee->position) }}"
                            class="form-control @error('position') is-invalid @enderror"
                            placeholder="Cth: Kasir, Staff Gudang" required>
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-telephone"></i>
                            </span>
                            <input type="text" name="phone"
                                value="{{ old('phone', $employee->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="08xxx">
                        </div>
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Tanggal Bergabung <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="join_date"
                            value="{{ old('join_date', $employee->join_date->format('Y-m-d')) }}"
                            class="form-control @error('join_date') is-invalid @enderror"
                            required>
                        @error('join_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat</label>
                        <textarea name="address" rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Alamat lengkap karyawan...">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Data Gaji & Status --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-cash-stack me-2 text-success"></i>Data Gaji & Status
                </h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Gaji Pokok <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="base_salary"
                                value="{{ old('base_salary', $employee->base_salary) }}"
                                class="form-control @error('base_salary') is-invalid @enderror"
                                placeholder="0" min="0" required>
                        </div>
                        @error('base_salary')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tipe Gaji <span class="text-danger">*</span>
                        </label>
                        <select name="salary_type"
                            class="form-select @error('salary_type') is-invalid @enderror" required>
                            <option value="monthly"
                                {{ old('salary_type', $employee->salary_type) == 'monthly' ? 'selected' : '' }}>
                                Bulanan
                            </option>
                            <option value="weekly"
                                {{ old('salary_type', $employee->salary_type) == 'weekly' ? 'selected' : '' }}>
                                Mingguan
                            </option>
                            <option value="daily"
                                {{ old('salary_type', $employee->salary_type) == 'daily' ? 'selected' : '' }}>
                                Harian
                            </option>
                        </select>
                        @error('salary_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active"
                                {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="inactive"
                                {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>
                            <option value="resigned"
                                {{ old('status', $employee->status) == 'resigned' ? 'selected' : '' }}>
                                Resign
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('employees.index') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-warning px-5">
                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
