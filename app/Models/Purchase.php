<?php



// ==================== app/Models/Purchase.php ====================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'supplier_id', 'user_id', 'purchase_date',
        'total_amount', 'discount', 'grand_total', 'status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'total_amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'grand_total' => 'decimal:2',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'PO-' . date('Ymd') . '-';
        $last = self::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('id')->first();
        $number = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
