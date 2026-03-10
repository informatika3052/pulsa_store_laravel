@extends('layouts.app')

@section('title', 'Input Gaji')
@section('page-title', 'Input Data Gaji')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('salary.index') }}">Penggajian</a></li>
    <li class="breadcrumb-item active">Input Gaji</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <form action="{{ route('salary.store') }}" method="POST">
            @csrf
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold mb-4 pb-2 border-bottom">
                    <i class="bi bi-cash-stack me-2 text-success"></i>Data Penggajian
                </h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Karyawan <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employeeSelect"
                            class="form-select @error('employee_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}"
                                data-salary="{{ $emp->base_salary }}"
                                {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} - {{ $emp->position }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
                        <select name="month" class="form-select @error('month') is-invalid @enderror" required>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                            <option value="{{ $i+1 }}" {{ old('month', date('n')) == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                        @error('month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                        <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gaji Pokok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="base_salary" id="baseSalary"
                                value="{{ old('base_salary', 0) }}"
                                class="form-control @error('base_salary') is-invalid @enderror"
                                min="0" required onchange="calcTotal()">
                        </div>
                        @error('base_salary') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tunjangan</label>
                        <div class="input-group">
                            <span class="input-group-text text-success">+Rp</span>
                            <input type="number" name="allowance" id="allowance"
                                value="{{ old('allowance', 0) }}"
                                class="form-control" min="0" onchange="calcTotal()">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text text-info">+Rp</span>
                            <input type="number" name="bonus" id="bonus"
                                value="{{ old('bonus', 0) }}"
                                class="form-control" min="0" onchange="calcTotal()">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Potongan</label>
                        <div class="input-group">
                            <span class="input-group-text text-danger">-Rp</span>
                            <input type="number" name="deduction" id="deduction"
                                value="{{ old('deduction', 0) }}"
                                class="form-control" min="0" onchange="calcTotal()">
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="col-12">
                        <div class="p-3 rounded" style="background:#f0fdf4;border:2px solid #bbf7d0">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success fs-6">TOTAL GAJI:</span>
                                <span class="fw-bold text-success fs-4" id="totalDisplay">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date"
                            value="{{ old('payment_date') }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="paid"    {{ old('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"
                            placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('salary.index') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-success px-5">
                    <i class="bi bi-check-lg me-1"></i>Simpan Data Gaji
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-fill gaji pokok saat pilih karyawan
document.getElementById('employeeSelect').addEventListener('change', function() {
    const salary = this.options[this.selectedIndex].dataset.salary || 0;
    document.getElementById('baseSalary').value = salary;
    calcTotal();
});

function calcTotal() {
    const base    = parseFloat(document.getElementById('baseSalary').value)  || 0;
    const allow   = parseFloat(document.getElementById('allowance').value)   || 0;
    const bonus   = parseFloat(document.getElementById('bonus').value)       || 0;
    const deduct  = parseFloat(document.getElementById('deduction').value)   || 0;
    const total   = base + allow + bonus - deduct;
    document.getElementById('totalDisplay').textContent =
        'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, total));
}

calcTotal();
</script>
@endpush
