<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    /**
     * Display a listing of stock outs
     */
    public function index(Request $request)
    {
        $query = StockOut::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $stockOuts = $query->paginate(10)->withQueryString();

        return view('stock-outs.index', compact('stockOuts'));
    }

    /**
     * Show the form for creating a new stock out
     */
    public function create()
    {
        $transactionId = StockOut::generateTransactionId();
        return view('stock-outs.create', compact('transactionId'));
    }

    /**
     * Store a newly created stock out
     */
    public function store(Request $request)
    {
        if (!$request->filled('transaction_id')) {
            $request->merge(['transaction_id' => StockOut::generateTransactionId()]);
        }

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|unique:stock_outs,transaction_id',
            'date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'total_items' => 'required|integer|min:1',
            'recipient_name' => 'nullable|string|max:255',
            'status' => 'required|in:Completed,In-Progress,Canceled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        StockOut::create($request->all());

        return redirect()->route('stock-outs.index')
            ->with('success', 'Barang keluar berhasil dicatat!');
    }

    /**
     * Display the specified stock out
     */
    public function show(StockOut $stockOut)
    {
        return view('stock-outs.show', compact('stockOut'));
    }

    /**
     * Show the form for editing the stock out
     */
    public function edit(StockOut $stockOut)
    {
        return view('stock-outs.edit', compact('stockOut'));
    }

    /**
     * Update the specified stock out
     */
    public function update(Request $request, StockOut $stockOut)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|unique:stock_outs,transaction_id,' . $stockOut->id,
            'date' => 'required|date',
            'customer_name' => 'required|string|max:255',
            'total_items' => 'required|integer|min:1',
            'recipient_name' => 'nullable|string|max:255',
            'status' => 'required|in:Completed,In-Progress,Canceled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $stockOut->update($request->all());

        return redirect()->route('stock-outs.index')
            ->with('success', 'Data barang keluar berhasil diupdate!');
    }

    /**
     * Remove the specified stock out
     */
    public function destroy(StockOut $stockOut)
    {
        $stockOut->delete();

        return redirect()->route('stock-outs.index')
            ->with('success', 'Data barang keluar berhasil dihapus!');
    }

    /**
     * Export stock outs data
     */
    public function export(Request $request)
    {
        $query = StockOut::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stockOuts = $query->orderBy('date', 'desc')->get();

        $filename = 'barang_keluar_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($stockOuts) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'No. Transaksi',
                'Tanggal',
                'Customer / Penerima',
                'Tipe Transaksi',
                'Status',
                'Total Items',
                'Catatan'
            ]);

            foreach ($stockOuts as $item) {
                fputcsv($file, [
                    $item->transaction_id,
                    $item->date ? $item->date->format('Y-m-d') : ($item->created_at ? $item->created_at->format('Y-m-d') : '-'),
                    $item->customer_name ?? $item->recipient_name ?? '-',
                    $item->type ?? 'Sales',
                    $item->status ?? 'Completed',
                    $item->total_items ?? 0,
                    $item->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
