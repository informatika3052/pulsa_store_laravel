<?php



// ==================== app/Http/Requests/EmployeeRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name'        => ['required', 'string', 'max:255'],
            'position'    => ['required', 'string', 'max:100'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string'],
            'join_date'   => ['required', 'date'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'salary_type' => ['required', 'in:monthly,daily,weekly'],
            'status'      => ['required', 'in:active,inactive,resigned'],
        ];
    }
}
