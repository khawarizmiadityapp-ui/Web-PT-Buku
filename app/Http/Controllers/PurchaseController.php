<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchase orders
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('po_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('po_date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Stats summary
        $stats = [
            'total_po' => Purchase::count(),
            'pending_approval' => Purchase::where('status', 'Pending Approval')->count(),
            'approved' => Purchase::where('status', 'Approved')->count(),
            'total_amount' => Purchase::whereNotIn('status', ['Canceled'])->sum('total_amount'),
        ];

        $purchases = $query->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases', 'stats'));
    }

    /**
     * Show the form for creating a new purchase order
     */
    public function create()
    {
        $suppliers = Supplier::where('status', 'Active')->get();
        $products = Product::where('status', 'Active')->get();
        $autoPoNumber = 'PO-' . date('Ymd') . '-' . sprintf('%03d', Purchase::count() + 1);

        return view('purchases.create', compact('suppliers', 'products', 'autoPoNumber'));
    }

    /**
     * Store a newly created purchase order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'po_number' => 'required|string|unique:purchases,po_number',
            'po_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:Draft,Pending Approval,Approved,Received,Canceled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ($item['quantity'] * $item['unit_price']);
            }

            $purchase = Purchase::create([
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'supplier_id' => $request->supplier_id,
                'status' => $request->status,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal membuat Purchase Order. Silakan periksa kembali data Anda atau hubungi admin.')->withInput();
        }
    }

    /**
     * Display the specified purchase order
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified purchase order
     */
    public function edit(Purchase $purchase)
    {
        if (in_array($purchase->status, ['Received', 'Canceled'])) {
            return back()->with('error', 'Purchase Order yang sudah Received / Canceled tidak dapat diubah.');
        }

        $purchase->load(['supplier', 'items.product']);
        $suppliers = Supplier::where('status', 'Active')->get();
        $products = Product::where('status', 'Active')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Update the specified purchase order
     */
    public function update(Request $request, Purchase $purchase)
    {
        if (in_array($purchase->status, ['Received', 'Canceled'])) {
            return back()->with('error', 'Purchase Order yang sudah Received / Canceled tidak dapat diubah.');
        }

        $validator = Validator::make($request->all(), [
            'po_number' => 'required|string|unique:purchases,po_number,' . $purchase->id,
            'po_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status' => 'required|in:Draft,Pending Approval,Approved,Received,Canceled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ($item['quantity'] * $item['unit_price']);
            }

            $purchase->update([
                'po_number' => $request->po_number,
                'po_date' => $request->po_date,
                'supplier_id' => $request->supplier_id,
                'status' => $request->status,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Replace existing items
            $purchase->items()->delete();
            foreach ($request->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase Order berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Gagal memperbarui Purchase Order. Silakan periksa kembali data Anda atau hubungi admin.')->withInput();
        }
    }

    /**
     * Remove the specified purchase order
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase Order berhasil dihapus!');
    }

    /**
     * Update purchase order status
     */
    public function updateStatus(Request $request, Purchase $purchase)
    {
        $request->validate([
            'status' => 'required|in:Draft,Pending Approval,Approved,Received,Canceled',
        ]);

        $purchase->update(['status' => $request->status]);

        return back()->with('success', 'Status PO #' . $purchase->po_number . ' diubah menjadi ' . $request->status);
    }

    /**
     * Export purchase orders data
     */
    public function export(Request $request)
    {
        $query = Purchase::with('supplier');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchases = $query->orderBy('po_date', 'desc')->get();

        $filename = 'purchase_orders_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($purchases) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No. PO',
                'Tanggal PO',
                'Supplier',
                'Total Amount (Rp)',
                'Status',
                'Catatan'
            ]);

            foreach ($purchases as $po) {
                fputcsv($file, [
                    $po->po_number,
                    $po->po_date ? \Carbon\Carbon::parse($po->po_date)->format('Y-m-d') : '-',
                    $po->supplier ? $po->supplier->name : '-',
                    $po->total_amount,
                    $po->status,
                    $po->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
