<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesInvoiceController extends Controller
{
    /**
     * Display sales invoices list
     */
    public function index(Request $request)
    {
        $query = SalesInvoice::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Filter by tab (All, Paid, Unpaid, Overdue)
        if ($request->filled('tab')) {
            switch ($request->tab) {
                case 'paid':
                    $query->where('payment_status', 'Paid');
                    break;
                case 'unpaid':
                    $query->where('payment_status', 'Unpaid');
                    break;
                case 'overdue':
                    $query->where(function($q) {
                        $q->where('payment_status', 'Overdue')
                          ->orWhere(function($sq) {
                              $sq->where('payment_status', 'Unpaid')
                                 ->where('due_date', '<', now());
                          });
                    });
                    break;
            }
        }

        // Stats for summary cards
        $stats = [
            'total_revenue_month' => SalesInvoice::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('total_amount'),
            'total_unpaid' => SalesInvoice::where('payment_status', 'Unpaid')
                ->orWhere('payment_status', 'Overdue')
                ->sum('total_amount'),
            'invoices_issued' => SalesInvoice::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
        ];

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Handle CSV export for invoices list
        if ($request->has('export') && $request->export == 'csv') {
            $invoices = $query->get();

            $filename = 'sales_invoices_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($invoices) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, [
                    'No. Invoice',
                    'Tanggal',
                    'Customer',
                    'Total Amount (Rp)',
                    'Paid Amount (Rp)',
                    'Metode Pembayaran',
                    'Status Pembayaran'
                ]);

                foreach ($invoices as $inv) {
                    fputcsv($file, [
                        $inv->invoice_number,
                        $inv->date ? \Carbon\Carbon::parse($inv->date)->format('Y-m-d') : ($inv->created_at ? $inv->created_at->format('Y-m-d') : '-'),
                        $inv->customer_name,
                        $inv->total_amount,
                        $inv->paid_amount ?? 0,
                        $inv->payment_method ?? '-',
                        $inv->payment_status
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Pagination
        $invoices = $query->paginate(10)->withQueryString();

        return view('sales.invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Display sales performance report
     */
    public function report(Request $request)
    {
        // Date range
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        // Handle CSV export for report
        if ($request->has('export') && $request->export == 'csv') {
            $invoices = SalesInvoice::whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();

            $filename = 'laporan_penjualan_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($invoices, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, ["Laporan Kinerja Penjualan ({$startDate} s/d {$endDate})"]);
                fputcsv($file, []);

                fputcsv($file, [
                    'No. Invoice',
                    'Tanggal',
                    'Customer',
                    'Total Amount (Rp)',
                    'Metode Pembayaran',
                    'Status Pembayaran'
                ]);

                foreach ($invoices as $inv) {
                    fputcsv($file, [
                        $inv->invoice_number,
                        $inv->date ? \Carbon\Carbon::parse($inv->date)->format('Y-m-d') : '-',
                        $inv->customer_name,
                        $inv->total_amount,
                        $inv->payment_method ?? '-',
                        $inv->payment_status
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Total sales
        $totalSales = SalesInvoice::whereBetween('date', [$startDate, $endDate])
            ->sum('total_amount');

        // Sales growth
        $previousPeriodStart = Carbon::parse($startDate)->subDays(30);
        $previousPeriodEnd = Carbon::parse($endDate)->subDays(30);
        $previousSales = SalesInvoice::whereBetween('date', [$previousPeriodStart, $previousPeriodEnd])
            ->sum('total_amount');
        $growthPercentage = $previousSales > 0 
            ? (($totalSales - $previousSales) / $previousSales) * 100 
            : 0;

        // Top performing category (dummy for now)
        $topCategory = [
            'name' => 'Educational Books',
            'percentage' => 45.8,
        ];

        // Monthly sales distribution
        $monthlySales = SalesInvoice::selectRaw('MONTH(date) as month, SUM(total_amount) as total')
            ->whereYear('date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Daily sales log
        $dailySales = SalesInvoice::selectRaw('DATE(date) as sale_date, 
                COUNT(*) as total_orders, 
                SUM(total_amount) as gross_revenue, 
                AVG(total_amount) as avg_order_value')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'desc')
            ->get();

        // Calculate status distribution
        foreach ($dailySales as $sale) {
            $dayInvoices = SalesInvoice::whereDate('date', $sale->sale_date)->get();
            $statusCounts = $dayInvoices->groupBy('payment_status')->map->count();
            $sale->status_label = $statusCounts->keys()->first() ?? 'Pending';
        }

        return view('sales.report', compact(
            'totalSales',
            'growthPercentage',
            'topCategory',
            'monthlySales',
            'dailySales',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show form to create new invoice
     */
    public function create()
    {
        $customers = \App\Models\Customer::all();
        $products = \App\Models\Product::where('status', 'Active')->get();
        
        $lastInvoice = SalesInvoice::orderBy('id', 'desc')->first();
        $nextNumber = $lastInvoice ? ((int) preg_replace('/[^0-9]/', '', $lastInvoice->invoice_number)) + 1 : 1;
        $invoiceNumber = 'INV/' . date('Y') . '/' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('sales.invoices.create', compact('customers', 'products', 'invoiceNumber'));
    }

    /**
     * Display the specified invoice
     */
    public function show(SalesInvoice $invoice)
    {
        $invoice->load(['customer', 'items.product']);
        return view('sales.invoices.show', compact('invoice'));
    }

    /**
     * Store a new invoice
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales_invoices',
            'date' => 'required|date',
            'customer_name' => 'required',
            'customer_id' => 'nullable|exists:customers,id',
            'total_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_status' => 'required|in:Paid,Unpaid,Overdue,Partial',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        if ($validated['payment_status'] === 'Paid') {
            $validated['paid_amount'] = $validated['total_amount'];
        } else {
            $validated['paid_amount'] = $request->get('paid_amount', 0);
        }

        $invoice = SalesInvoice::create($validated);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (!empty($item['product_name']) && !empty($item['quantity'])) {
                    $invoice->items()->create([
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? 0,
                        'subtotal' => ($item['quantity'] * ($item['price'] ?? 0)),
                    ]);
                }
            }
        }

        return redirect()->route('sales.invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    /**
     * Update payment status
     */
    public function updatePayment(Request $request, SalesInvoice $invoice)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Paid,Unpaid,Overdue,Partial',
        ]);

        $invoice->update($validated);

        return back()->with('success', 'Payment updated successfully!');
    }
}
