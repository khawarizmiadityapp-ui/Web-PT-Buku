<?php

namespace App\Http\Controllers;

use App\Models\ProductReturn;
use App\Models\Product;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductReturnController extends Controller
{
    /**
     * Display returns index (Process Sales Return page)
     */
    public function index(Request $request)
    {
        $query = ProductReturn::with(['product', 'salesInvoice']);

        // Search by invoice, barcode, or customer
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('return_id', 'like', "%{$search}%")
                  ->orWhere('entity', 'like', "%{$search}%")
                  ->orWhereHas('salesInvoice', function($sq) use ($search) {
                      $sq->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Recent returns (default last 30 days)
        $dateFilter = $request->get('date_filter', 'last_30');
        if ($dateFilter === 'last_30') {
            $query->where('date', '>=', now()->subDays(30));
        } elseif ($dateFilter === 'last_7') {
            $query->where('date', '>=', now()->subDays(7));
        }

        // Stats
        $stats = [
            'total_returns' => ProductReturn::count(),
            'pending_approvals' => ProductReturn::where('status', 'Pending')->count(),
            'approved_today' => ProductReturn::where('status', 'Approved')
                ->whereDate('updated_at', today())
                ->count(),
            'total_refund_amount' => ProductReturn::where('status', 'Approved')
                ->sum('refund_amount'),
        ];

        $returns = $query->latest('date')->paginate(15)->withQueryString();
        
        // Recent returns for quick access
        $recentReturns = ProductReturn::with('salesInvoice')
            ->latest()
            ->take(5)
            ->get();

        return view('returns.index', compact('returns', 'stats', 'recentReturns'));
    }

    /**
     * Show form to create new return (Sales Return Process page)
     */
    public function create(Request $request)
    {
        $invoice = null;
        
        // If invoice_id provided, pre-fill data
        if ($request->has('invoice_id')) {
            $invoice = SalesInvoice::with('items.product')->findOrFail($request->invoice_id);
        }
        
        $products = Product::where('status', 'Active')->orderBy('product_name')->get();
        $recentInvoices = SalesInvoice::latest()->take(10)->get();
        
        return view('returns.create', compact('invoice', 'products', 'recentInvoices'));
    }

    /**
     * Store new return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sales_invoice_id' => 'nullable|exists:sales_invoices,id',
            'date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'entity' => 'required|string',
            'type' => 'required|in:SALES,PURCHASE',
            'reason' => 'required|string',
            'items' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'refund_method' => 'required|in:Cash,QRIS',
            'restocking_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'proof_image.image' => 'Bukti foto harus berupa file gambar yang valid',
            'proof_image.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP',
            'proof_image.max' => 'Ukuran file gambar maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // Calculate amounts
            $totalAmount = $validated['items'] * $validated['unit_price'];
            $restockingFee = $validated['restocking_fee'] ?? 0;
            $refundAmount = $totalAmount - $restockingFee;

            // Generate return ID
            $returnId = $this->generateReturnId();

            // Handle image upload
            $proofImage = null;
            if ($request->hasFile('proof_image')) {
                $proofImage = $request->file('proof_image')->store('returns', 'public');
            }

            // Create return
            $return = ProductReturn::create([
                'return_id' => $returnId,
                'date' => $validated['date'],
                'sales_invoice_id' => $validated['sales_invoice_id'] ?? null,
                'product_id' => $validated['product_id'],
                'entity' => $validated['entity'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'items' => $validated['items'],
                'unit_price' => $validated['unit_price'],
                'total_amount' => $totalAmount,
                'refund_method' => $validated['refund_method'],
                'refund_amount' => $refundAmount,
                'restocking_fee' => $restockingFee,
                'status' => 'Pending',
                'notes' => $validated['notes'] ?? null,
                'proof_image' => $proofImage,
            ]);

            DB::commit();

            return redirect()->route('returns.show', $return->id)
                ->with('success', 'Return created successfully! Awaiting verification.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create return: ' . $e->getMessage());
        }
    }

    /**
     * Show return details
     */
    public function show(ProductReturn $return)
    {
        $return->load(['product', 'salesInvoice.items']);
        
        return view('returns.show', compact('return'));
    }

    /**
     * Update return status
     */
    public function updateStatus(Request $request, ProductReturn $return)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected',
            'admin_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $return->update([
                'status' => $validated['status'],
                'notes' => $return->notes . "\n\nAdmin: " . ($validated['admin_notes'] ?? 'Status updated'),
            ]);

            // If approved, update stock
            if ($validated['status'] === 'Approved') {
                $product = $return->product;
                $product->system_stock += $return->items;
                $product->physical_stock += $return->items;
                $product->save();
            }

            DB::commit();

            return back()->with('success', 'Return status updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request, ProductReturn $return)
    {
        if ($return->status !== 'Approved') {
            return back()->with('error', 'Can only process refund for approved returns!');
        }

        $validated = $request->validate([
            'refund_notes' => 'nullable|string',
        ]);

        // Here you would integrate with payment gateway
        // For now, just mark as processed
        
        $return->update([
            'notes' => $return->notes . "\n\nRefund Processed: " . ($validated['refund_notes'] ?? 'Completed'),
        ]);

        return back()->with('success', 'Refund processed successfully!');
    }

    /**
     * Search invoices for return
     */
    public function searchInvoice(Request $request)
    {
        $search = $request->get('search', '');
        
        $invoices = SalesInvoice::with('items.product')
            ->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            })
            ->latest()
            ->take(10)
            ->get();

        return response()->json($invoices);
    }

    /**
     * Generate unique return ID
     */
    private function generateReturnId()
    {
        $prefix = 'RET';
        $date = now()->format('Ymd');
        
        $lastReturn = ProductReturn::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReturn) {
            $lastNumber = (int) substr($lastReturn->return_id, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }
}
