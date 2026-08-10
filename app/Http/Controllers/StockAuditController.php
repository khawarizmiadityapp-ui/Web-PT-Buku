<?php

namespace App\Http\Controllers;

use App\Models\StockAudit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockAuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('stockAudits');

        // Filter by status
        $filterBy = $request->get('filter_by', 'all');
        
        if ($filterBy === 'discrepancies') {
            $query->whereRaw('physical_stock != system_stock');
        } elseif ($filterBy === 'adjusted') {
            $query->whereHas('stockAudits', function($q) {
                $q->where('adjustment_status', 'Has Adjusted');
            });
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Stats
        $totalDiscrepancy = Product::whereRaw('physical_stock != system_stock')->count();
        $lastAuditDate = StockAudit::latest('audit_date')->first();
        $accuracyRate = $this->calculateAccuracyRate();

        $products = $query->paginate(10)->withQueryString();

        return view('warehouse.stock-audit.index', compact(
            'products',
            'totalDiscrepancy',
            'lastAuditDate',
            'accuracyRate'
        ));
    }

    public function processAdjustment(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $processed = 0;

        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            
            if ($product->system_stock != $product->physical_stock) {
                // Create audit record
                StockAudit::create([
                    'product_id' => $product->id,
                    'system_stock' => $product->system_stock,
                    'physical_stock' => $product->physical_stock,
                    'difference' => $product->physical_stock - $product->system_stock,
                    'adjustment_status' => 'Has Adjusted',
                    'audited_by' => Auth::user()->name,
                    'audit_date' => now(),
                ]);

                // Adjust system stock to match physical
                $product->update([
                    'system_stock' => $product->physical_stock
                ]);

                $processed++;
            }
        }

        return back()->with('success', "Successfully adjusted {$processed} items!");
    }

    public function startStockCount()
    {
        return view('warehouse.stock-audit.stock-count');
    }

    private function calculateAccuracyRate()
    {
        $totalProducts = Product::count();
        if ($totalProducts == 0) return 100;

        $accurateProducts = Product::whereRaw('physical_stock = system_stock')->count();
        return round(($accurateProducts / $totalProducts) * 100, 1);
    }

    /**
     * Export stock audit data
     */
    public function export(Request $request)
    {
        $query = Product::with('stockAudits');

        $filterBy = $request->get('filter_by', 'all');
        if ($filterBy === 'discrepancies') {
            $query->whereRaw('physical_stock != system_stock');
        } elseif ($filterBy === 'adjusted') {
            $query->whereHas('stockAudits', function($q) {
                $q->where('adjustment_status', 'Has Adjusted');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->orderBy('product_name')->get();

        $filename = 'stock_audit_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Kode Barang',
                'Nama Barang',
                'Kategori',
                'Satuan',
                'Stok Sistem',
                'Stok Fisik',
                'Selisih',
                'Status Audit'
            ]);

            foreach ($products as $p) {
                $diff = ($p->physical_stock ?? $p->system_stock) - $p->system_stock;
                $status = ($diff == 0) ? 'Match' : 'Discrepancy';

                fputcsv($file, [
                    $p->product_code,
                    $p->product_name,
                    $p->category,
                    $p->unit,
                    $p->system_stock,
                    $p->physical_stock ?? $p->system_stock,
                    $diff,
                    $status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
