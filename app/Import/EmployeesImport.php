<?php


// ==================== app/Imports/EmployeesImport.php ====================
namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): Employee
    {
        return new Employee([
            'employee_code' => Employee::generateCode(),
            'name'          => $row['nama'],
            'position'      => $row['jabatan'],
            'phone'         => $row['telepon'] ?? null,
            'address'       => $row['alamat'] ?? null,
            'join_date'     => $row['tgl_bergabung'],
            'base_salary'   => $row['gaji_pokok'],
            'salary_type'   => $row['tipe_gaji'] ?? 'monthly',
            'status'        => 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string',
            'jabatan'  => 'required|string',
            'gaji_pokok' => 'required|numeric',
        ];
    }
}
