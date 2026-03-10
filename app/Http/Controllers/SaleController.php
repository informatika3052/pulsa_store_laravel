<?php


// ==================== app/Http/Controllers/SaleController.php ====================
namespace App\Http\Controllers;

use App\Models\{Sale, SaleItem, Product, StockAdjustment};
use App\Http\Requests\SaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items'])->latest();

        if ($search = $request->search) {
            $query->where(fn($q) => $q
                ->where('invoice_number', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%"));
        }

        if ($dateFrom = $request->date_from) {
            $query->whereDate('sale_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->date_to) {
            $query->whereDate('sale_date', '<=', $dateTo);
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $sales = $query->paginate(15)->withQueryString();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::active()->with('category')->get();
        return view('sales.create', compact('products'));
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();
        try {
            $items      = $request->items;
            $totalAmount = collect($items)->sum(fn($i) => $i['quantity'] * $i['price']);
            $discount   = $request->discount ?? 0;
            $grandTotal = $totalAmount - $discount;

            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'user_id'        => auth()->id(),
                'customer_name'  => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'sale_date'      => $request->sale_date,
                'total_amount'   => $totalAmount,
                'discount'       => $discount,
                'grand_total'    => $grandTotal,
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => $request->paid_amount - $grandTotal,
                'payment_method' => $request->payment_method,
                'status'         => 'completed',
                'notes'          => $request->notes,
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Cek stok
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi! Stok tersisa: {$product->stock}");
                }

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);

                // Kurangi stok & catat perubahan
                $stockBefore = $product->stock;
                $product->decrement('stock', $item['quantity']);

                StockAdjustment::create([
                    'product_id'      => $product->id,
                    'user_id'         => auth()->id(),
                    'type'            => 'out',
                    'quantity_before' => $stockBefore,
                    'quantity_change' => $item['quantity'],
                    'quantity_after'  => $product->fresh()->stock,
                    'reference'       => $sale->invoice_number,
                    'reason'          => 'Penjualan',
                ]);
            }

            DB::commit();
            return redirect()->route('sales.show', $sale)
                ->with('success', "Transaksi {$sale->invoice_number} berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    public function printInvoice(Sale $sale)
    {
        $sale->load(['user', 'items.product']);
        $pdf = Pdf::loadView('sales.invoice-pdf', compact('sale'))
            ->setPaper([0, 0, 226.77, 800], 'portrait'); // 80mm thermal paper
        return $pdf->stream("nota-{$sale->invoice_number}.pdf");
    }

    public function destroy(Sale $sale)
    {
        // Kembalikan stok jika dibatalkan
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = $item->product;
                $stockBefore = $product->stock;
                $product->increment('stock', $item->quantity);

                StockAdjustment::create([
                    'product_id'      => $product->id,
                    'user_id'         => auth()->id(),
                    'type'            => 'in',
                    'quantity_before' => $stockBefore,
                    'quantity_change' => $item->quantity,
                    'quantity_after'  => $product->fresh()->stock,
                    'reference'       => $sale->invoice_number,
                    'reason'          => 'Pembatalan penjualan',
                ]);
            }
            $sale->update(['status' => 'cancelled']);
            $sale->delete();
        });

        return redirect()->route('sales.index')
            ->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan!');
    }
}
