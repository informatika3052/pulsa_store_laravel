<?php


// ==================== app/Http/Requests/SalaryRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'employee_id'  => ['required', 'exists:employees,id'],
            'month'        => ['required', 'integer', 'between:1,12'],
            'year'         => ['required', 'integer', 'min:2020', 'max:' . (date('Y') + 1)],
            'base_salary'  => ['required', 'numeric', 'min:0'],
            'allowance'    => ['nullable', 'numeric', 'min:0'],
            'bonus'        => ['nullable', 'numeric', 'min:0'],
            'deduction'    => ['nullable', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'status'       => ['required', 'in:pending,paid'],
            'notes'        => ['nullable', 'string'],
        ];
    }
}
