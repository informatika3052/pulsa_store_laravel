<?php


// ==================== app/Http/Requests/UserRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;
        $passwordRules = $this->isMethod('POST')
            ? ['required', Password::min(8)->letters()->numbers()]
            : ['nullable', Password::min(8)->letters()->numbers()];

        return [
            'role_id'  => ['required', 'exists:roles,id'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($userId)->whereNull('deleted_at')],
            'phone'    => ['nullable', 'string', 'max:20'],
            'address'  => ['nullable', 'string'],
            'password' => $passwordRules,
            'is_active' => ['boolean'],
        ];
    }
}
