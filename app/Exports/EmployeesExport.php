<?php



// ==================== app/Exports/EmployeesExport.php ====================
namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employee::all();
    }

    public function headings(): array
    {
        return ['Kode', 'Nama', 'Jabatan', 'Telepon', 'Alamat', 'Tgl Bergabung', 'Gaji Pokok', 'Tipe Gaji', 'Status'];
    }

    public function map($emp): array
    {
        return [
            $emp->employee_code,
            $emp->name,
            $emp->position,
            $emp->phone,
            $emp->address,
            $emp->join_date->format('d/m/Y'),
            $emp->base_salary,
            $emp->salary_type,
            $emp->status,
        ];
    }
}
