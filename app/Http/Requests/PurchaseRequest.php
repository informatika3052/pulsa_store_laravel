<?php



// ==================== app/Http/Requests/PurchaseRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isGudang();
    }

    public function rules(): array
    {
        return [
            'supplier_id'        => ['nullable', 'exists:suppliers,id'],
            'purchase_date'      => ['required', 'date'],
            'discount'           => ['nullable', 'numeric', 'min:0'],
            'notes'              => ['nullable', 'string'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
        ];
    }
}
