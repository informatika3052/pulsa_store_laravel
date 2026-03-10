<?php


// ==================== app/Http/Requests/ProductRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'category_id'    => ['required', 'exists:categories,id'],
            'supplier_id'    => ['nullable', 'exists:suppliers,id'],
            'code'           => ['required', 'string', 'max:50', Rule::unique('products')->ignore($productId)->whereNull('deleted_at')],
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'unit'           => ['required', 'string', 'max:20'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0', 'gte:purchase_price'],
            'min_stock'      => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'    => 'Kategori wajib dipilih.',
            'code.required'           => 'Kode barang wajib diisi.',
            'code.unique'             => 'Kode barang sudah digunakan.',
            'name.required'           => 'Nama barang wajib diisi.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'selling_price.required'  => 'Harga jual wajib diisi.',
            'selling_price.gte'       => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'image.image'             => 'File harus berupa gambar.',
            'image.max'               => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
