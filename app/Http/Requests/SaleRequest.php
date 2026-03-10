<?php

// ==================== app/Http/Requests/SaleRequest.php ====================
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_name'      => ['nullable', 'string', 'max:255'],
            'customer_phone'     => ['nullable', 'string', 'max:20'],
            'sale_date'          => ['required', 'date'],
            'discount'           => ['nullable', 'numeric', 'min:0'],
            'paid_amount'        => ['required', 'numeric', 'min:0'],
            'payment_method'     => ['required', 'in:cash,transfer,qris'],
            'notes'              => ['nullable', 'string'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'sale_date.required'          => 'Tanggal penjualan wajib diisi.',
            'paid_amount.required'        => 'Jumlah bayar wajib diisi.',
            'payment_method.required'     => 'Metode pembayaran wajib dipilih.',
            'items.required'              => 'Minimal satu item harus ditambahkan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.quantity.min'        => 'Jumlah minimal 1.',
        ];
    }
}
