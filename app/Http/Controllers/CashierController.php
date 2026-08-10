<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard
     */
    public function index()
    {
        // Get today's statistics
        $today = now()->toDateString();
        
        $stats = [
            'today_revenue' => SalesInvoice::whereDate('created_at', $today)->sum('total_amount'),
            'today_transactions' => SalesInvoice::whereDate('created_at', $today)->count(),
            'today_income' => SalesInvoice::whereDate('created_at', $today)
                ->where('payment_status', 'Paid')
                ->sum('paid_amount'),
            'products_sold' => SalesInvoiceItem::whereHas('salesInvoice', function($q) use ($today) {
                $q->whereDate('created_at', $today);
            })->sum('quantity'),
        ];

        // Get sales trend for last 7 days
        $salesTrend = SalesInvoice::selectRaw('DATE(created_at) as date, 
                SUM(total_amount) as revenue,
                COUNT(*) as transactions')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent transactions (last 10)
        $recentTransactions = SalesInvoice::with('customer', 'items')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('cashier.index', compact('stats', 'salesTrend', 'recentTransactions'));
    }

    /**
     * Display cashier profile
     */
    public function profile()
    {
        $today = now()->toDateString();
        
        // Get today's stats for this cashier
        $todayStats = [
            'transactions' => SalesInvoice::whereDate('created_at', $today)->count(),
            'returns' => ProductReturn::whereDate('created_at', $today)->count(),
            'total_value' => SalesInvoice::whereDate('created_at', $today)->sum('total_amount'),
        ];

        return view('cashier.profile', compact('todayStats'));
    }

    /**
     * Show POS transaction page
     */
    public function transaction()
    {
        $products = Product::where('status', 'Active')
            ->where('system_stock', '>', 0)
            ->orderBy('product_name')
            ->get();
            
        $customers = Customer::orderBy('name')->get();

        return view('cashier.transaction', compact('products', 'customers'));
    }

    /**
     * Show enhanced POS transaction page
     */
    public function transactionEnhanced()
    {
        $products = Product::where('status', 'Active')
            ->where('system_stock', '>', 0)
            ->orderBy('product_name')
            ->get();
            
        $customers = Customer::orderBy('name')->get();

        $categories = Product::where('status', 'Active')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('cashier.transaction-enhanced', compact('products', 'customers', 'categories'));
    }

    /**
     * Process new transaction
     */
    public function processTransaction(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:Cash,QRIS,Credit Card,Debit Card,Bank Transfer,E-Wallet,Card',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }

            $discount = $validated['discount_amount'] ?? 0;
            $tax = $validated['tax_amount'] ?? 0;
            $total = $subtotal - $discount + $tax;

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber();

            // Determine payment status
            $paidAmount = $validated['paid_amount'];
            if ($paidAmount >= $total) {
                $paymentStatus = 'Paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'Partial';
            } else {
                $paymentStatus = 'Unpaid';
            }

            // Create invoice
            $invoice = SalesInvoice::create([
                'invoice_number' => $invoiceNumber,
                'date' => now(),
                'customer_id' => $validated['customer_id'] ?? null,
                'customer_name' => $validated['customer_name'],
                'total_amount' => $total,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'due_date' => now()->addDays(30),
                'payment_status' => $paymentStatus,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $paidAmount,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create invoice items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock availability
                if ($product->system_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->product_name}");
                }

                // Create invoice item
                SalesInvoiceItem::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update stock
                $product->system_stock -= $item['quantity'];
                $product->physical_stock -= $item['quantity'];
                $product->save();
            }

            // Update customer total purchases if customer exists
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                $customer->total_purchases += $total;
                $customer->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully!',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoiceNumber,
                'change' => $paidAmount - $total,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Show transaction history
     */
    public function history(Request $request)
    {
        $query = SalesInvoice::with('customer', 'items');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->has('status') && $request->status != '') {
            $query->where('payment_status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        $startDate = $request->start_date ?? now()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        
        $query->whereDate('created_at', '>=', $startDate);
        $query->whereDate('created_at', '<=', $endDate);

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Calculate stats for bottom cards
        $today = now()->toDateString();
        $dailyRevenue = SalesInvoice::whereDate('created_at', $today)
            ->where('payment_status', 'Paid')
            ->sum('total_amount');
        
        $successfulSales = SalesInvoice::whereDate('created_at', $today)
            ->where('payment_status', 'Paid')
            ->count();
        
        $pendingReturns = ProductReturn::where('status', 'Pending')
            ->count();

        // Handle CSV export
        if ($request->has('export') && $request->export == 'csv') {
            return $this->exportHistoryCSV($query->get());
        }

        return view('cashier.history', compact('transactions', 'dailyRevenue', 'successfulSales', 'pendingReturns'));
    }

    /**
     * Show transaction detail
     */
    public function show($id)
    {
        $transaction = SalesInvoice::with('customer', 'items.product')->findOrFail($id);
        
        return view('cashier.show', compact('transaction'));
    }

    /**
     * Print receipt
     */
    public function printReceipt($id)
    {
        $transaction = SalesInvoice::with('customer', 'items.product')->findOrFail($id);
        
        return view('cashier.receipt', compact('transaction'));
    }

    /**
     * Get product by barcode or search
     */
    public function searchProduct(Request $request)
    {
        $search = $request->get('search', '');
        
        $products = Product::where('status', 'Active')
            ->where('system_stock', '>', 0)
            ->where(function($q) use ($search) {
                $q->where('product_code', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        
        // Get last invoice number for today
        $lastInvoice = SalesInvoice::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract sequence number and increment
            $lastNumber = (int) substr($lastInvoice->invoice_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    /**
     * Export history to CSV
     */
    private function exportHistoryCSV($transactions)
    {
        $filename = 'sales-history-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Invoice Number',
                'Date',
                'Time',
                'Customer',
                'Total Amount',
                'Paid Amount',
                'Payment Method',
                'Payment Status',
                'Items Count'
            ]);

            // Data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->invoice_number,
                    $transaction->created_at->format('Y-m-d'),
                    $transaction->created_at->format('H:i:s'),
                    $transaction->customer_name,
                    $transaction->total_amount,
                    $transaction->paid_amount,
                    $transaction->payment_method ?? 'Cash',
                    $transaction->payment_status,
                    $transaction->items->count()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
