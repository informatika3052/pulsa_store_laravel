<?php

// ==================== app/Http/Controllers/DashboardController.php ====================
namespace App\Http\Controllers;

use App\Models\{Product, Sale, Purchase, Employee, SaleItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Data umum
        $totalProducts   = Product::active()->count();
        $lowStockCount   = Product::lowStock()->count();
        $totalEmployees  = Employee::where('status', 'active')->count();

        // Penjualan bulan ini
        $salesThisMonth = Sale::where('status', 'completed')
            ->whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->sum('grand_total');

        // Pembelian bulan ini
        $purchasesThisMonth = Purchase::where('status', 'confirmed')
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('grand_total');

        // Data grafik 6 bulan terakhir (untuk Chart.js)
        $chartData = $this->getChartData();

        // Transaksi terbaru
        $recentSales = Sale::with(['user', 'items'])
            ->where('status', 'completed')
            ->latest()->limit(5)->get();

        // Produk low stock
        $lowStockProducts = Product::with('category')
            ->lowStock()->active()->limit(5)->get();

        // Top produk terjual bulan ini
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->with('product')
            ->whereHas('sale', fn($q) => $q->where('status', 'completed')
                ->whereMonth('sale_date', now()->month))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)->get();

        return view('dashboard.index', compact(
            'user', 'totalProducts', 'lowStockCount', 'totalEmployees',
            'salesThisMonth', 'purchasesThisMonth', 'chartData',
            'recentSales', 'lowStockProducts', 'topProducts'
        ));
    }

    private function getChartData(): array
    {
        $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));

        $salesData = $months->map(fn($month) => Sale::where('status', 'completed')
            ->whereMonth('sale_date', $month->month)
            ->whereYear('sale_date', $month->year)
            ->sum('grand_total'));

        $purchaseData = $months->map(fn($month) => Purchase::where('status', 'confirmed')
            ->whereMonth('purchase_date', $month->month)
            ->whereYear('purchase_date', $month->year)
            ->sum('grand_total'));

        return [
            'labels'    => $months->map(fn($m) => $m->format('M Y'))->toArray(),
            'sales'     => $salesData->toArray(),
            'purchases' => $purchaseData->toArray(),
        ];
    }
}
