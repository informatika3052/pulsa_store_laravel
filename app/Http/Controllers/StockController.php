<?php


// ==================== app/Http/Controllers/StockController.php ====================
namespace App\Http\Controllers;

use App\Models\{Product, StockAdjustment};
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockAdjustment::with(['product.category', 'user'])->latest();

        if ($search = $request->search) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }
        if ($type = $request->type) {
            $query->where('type', $type);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $adjustments = $query->paginate(20)->withQueryString();
        return view('stock.index', compact('adjustments'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out,adjustment',
            'quantity'   => 'required|integer|min:1',
            'reason'     => 'required|string|max:255',
        ]);

        $product     = Product::findOrFail($request->product_id);
        $stockBefore = $product->stock;

        if ($request->type === 'out' && $product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        if ($request->type === 'in' || $request->type === 'adjustment') {
            $product->increment('stock', $request->quantity);
        } else {
            $product->decrement('stock', $request->quantity);
        }

        StockAdjustment::create([
            'product_id'      => $product->id,
            'user_id'         => auth()->id(),
            'type'            => $request->type,
            'quantity_before' => $stockBefore,
            'quantity_change' => $request->quantity,
            'quantity_after'  => $product->fresh()->stock,
            'reason'          => $request->reason,
        ]);

        return back()->with('success', 'Stok berhasil disesuaikan!');
    }
}
