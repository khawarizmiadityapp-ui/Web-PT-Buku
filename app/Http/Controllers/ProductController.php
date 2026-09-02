<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

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

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(10)->withQueryString();

        // Get unique categories
        $categories = Product::distinct()->pluck('category')->filter();

        return view('master-data.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Product::distinct()->pluck('category')->filter();
        return view('master-data.products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Product::distinct()->pluck('category')->filter();
        return view('master-data.products.edit', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products',
            'product_name' => 'required',
            'category' => 'required',
            'system_stock' => 'required|integer|min:0|max:500',
            'price' => 'required|numeric|min:0',
            'unit' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        // Set physical stock = system stock initially
        $validated['physical_stock'] = $validated['system_stock'];

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products,product_code,' . $product->id,
            'product_name' => 'required',
            'category' => 'required',
            'system_stock' => 'required|integer|min:0|max:500',
            'price' => 'required|numeric|min:0',
            'unit' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = Product::query();

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('product_name', 'asc')->get();

        $filename = 'daftar_barang_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Kode Barang',
                'Nama Barang',
                'Kategori',
                'Satuan',
                'Stok Sistem',
                'Stok Fisik',
                'Harga Jual (Rp)',
                'Status'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->product_code,
                    $product->product_name,
                    $product->category,
                    $product->unit,
                    $product->system_stock,
                    $product->physical_stock ?? $product->system_stock,
                    $product->price,
                    $product->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
