<?php



// ==================== app/Http/Controllers/ReportController.php ====================
namespace App\Http\Controllers;

use App\Models\{Sale, Purchase, Product, SaleItem};
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
use App\Exports\PurchaseReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to ?? now()->format('Y-m-d');

        $sales = Sale::with(['user', 'items.product'])
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->latest()->get();

        $totalRevenue = $sales->sum('grand_total');
        $totalItems   = $sales->sum(fn($s) => $s->items->sum('quantity'));

        return view('reports.sales', compact('sales', 'dateFrom', 'dateTo', 'totalRevenue', 'totalItems'));
    }

    public function purchaseReport(Request $request)
    {
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to ?? now()->format('Y-m-d');

        $purchases = Purchase::with(['supplier', 'user', 'items.product'])
            ->where('status', 'confirmed')
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->latest()->get();

        $totalExpense = $purchases->sum('grand_total');
        return view('reports.purchases', compact('purchases', 'dateFrom', 'dateTo', 'totalExpense'));
    }

    public function stockReport(Request $request)
    {
        $products = Product::with(['category', 'supplier'])
            ->when($request->category_id, fn($q, $v) => $q->where('category_id', $v))
            ->when($request->low_stock, fn($q) => $q->lowStock())
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('reports.stock', compact('products'));
    }

    // Export PDF
    public function exportSalesPdf(Request $request)
    {
        $dateFrom  = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo    = $request->date_to ?? now()->format('Y-m-d');
        $sales     = Sale::with(['items.product'])->where('status', 'completed')
            ->whereBetween('sale_date', [$dateFrom, $dateTo])->latest()->get();
        $pdf = Pdf::loadView('reports.sales-pdf', compact('sales', 'dateFrom', 'dateTo'))
            ->setPaper('a4', 'landscape');
        return $pdf->download("laporan-penjualan-{$dateFrom}-{$dateTo}.pdf");
    }

    // Export Excel
    public function exportSalesExcel(Request $request)
    {
        return Excel::download(
            new SalesReportExport($request->date_from, $request->date_to),
            "laporan-penjualan.xlsx"
        );
    }

    public function exportPurchaseExcel(Request $request)
    {
        return Excel::download(
            new PurchaseReportExport($request->date_from, $request->date_to),
            "laporan-pembelian.xlsx"
        );
    }
}
