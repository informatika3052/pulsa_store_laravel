<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\EmployeeRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('user')->latest();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $employees = $query->paginate(15)->withQueryString();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(EmployeeRequest $request)
    {
        $data                  = $request->validated();
        $data['employee_code'] = Employee::generateCode();

        Employee::create($data);

        return redirect()->route('employees.index')
            ->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function show(Employee $employee)
    {
        $employee->load(['salaries' => function ($q) {
            $q->latest()->limit(12);
        }]);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(
            new EmployeesExport,
            'karyawan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        Excel::import(new EmployeesImport, $request->file('file'));

        return back()->with('success', 'Data karyawan berhasil diimport!');
    }
}
