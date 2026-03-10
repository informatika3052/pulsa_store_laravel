<?php



// ==================== app/Http/Controllers/PurchaseController.php ====================
namespace App\Http\Controllers;

use App\Models\{Purchase, PurchaseItem, Product, Supplier, StockAdjustment};
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items'])->latest();

        if ($search = $request->search) {
            $query->where('invoice_number', 'like', "%{$search}%");
        }
        if ($request->date_from) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        $purchases = $query->paginate(15)->withQueryString();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products  = Product::active()->with('category')->get();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    public function store(PurchaseRequest $request)
    {
        DB::beginTransaction();
        try {
            $items       = $request->items;
            $totalAmount = collect($items)->sum(fn($i) => $i['quantity'] * $i['price']);
            $discount    = $request->discount ?? 0;

            $purchase = Purchase::create([
                'invoice_number' => Purchase::generateInvoiceNumber(),
                'supplier_id'    => $request->supplier_id,
                'user_id'        => auth()->id(),
                'purchase_date'  => $request->purchase_date,
                'total_amount'   => $totalAmount,
                'discount'       => $discount,
                'grand_total'    => $totalAmount - $discount,
                'status'         => 'confirmed',
                'notes'          => $request->notes,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'subtotal'    => $item['quantity'] * $item['price'],
                ]);

                $stockBefore = $product->stock;
                $product->increment('stock', $item['quantity']);

                StockAdjustment::create([
                    'product_id'      => $product->id,
                    'user_id'         => auth()->id(),
                    'type'            => 'in',
                    'quantity_before' => $stockBefore,
                    'quantity_change' => $item['quantity'],
                    'quantity_after'  => $product->fresh()->stock,
                    'reference'       => $purchase->invoice_number,
                    'reason'          => 'Pembelian',
                ]);
            }

            DB::commit();
            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Pembelian {$purchase->invoice_number} berhasil dicatat!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function printInvoice(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        $pdf = Pdf::loadView('purchases.invoice-pdf', compact('purchase'))
            ->setPaper('a5', 'portrait');
        return $pdf->stream("po-{$purchase->invoice_number}.pdf");
    }
}
