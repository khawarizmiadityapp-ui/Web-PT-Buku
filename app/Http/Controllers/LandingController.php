<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LandingController extends Controller
{
    /**
     * Display public landing page
     */
    public function index(Request $request)
    {
        $company = CompanySetting::current();

        // Get categories with item count
        $categories = Product::where('status', 'Active')
            ->select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        // Featured products for carousel / top picks
        $featuredProducts = Product::where('status', 'Active')
            ->where('system_stock', '>', 0)
            ->orderBy('system_stock', 'desc')
            ->take(8)
            ->get();

        // Initial product catalog items (with query filters)
        $query = Product::where('status', 'Active');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'newest':
                $query->orderBy('id', 'desc');
                break;
            default:
                $query->orderBy('system_stock', 'desc');
                break;
        }

        $catalogProducts = $query->paginate(12)->withQueryString();

        // Stats for hero / trust section
        $stats = [
            'total_products' => Product::where('status', 'Active')->count(),
            'total_stock' => Product::where('status', 'Active')->sum('system_stock'),
            'total_customers' => Customer::count(),
            'delivery_cities' => 34,
        ];

        return view('landing.index', compact(
            'company',
            'categories',
            'featuredProducts',
            'catalogProducts',
            'stats'
        ));
    }

    /**
     * AJAX/API endpoint to search and filter products without page reload
     */
    public function catalog(Request $request)
    {
        $query = Product::where('status', 'Active');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'ready') {
                $query->where('system_stock', '>', 10);
            } elseif ($request->stock === 'limited') {
                $query->whereBetween('system_stock', [1, 10]);
            }
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        $sort = $request->get('sort', 'popular');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'newest':
                $query->orderBy('id', 'desc');
                break;
            default:
                $query->orderBy('system_stock', 'desc');
                break;
        }

        $products = $query->paginate(12);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                    'has_more' => $products->hasMorePages(),
                ]
            ]);
        }

        $company = CompanySetting::current();
        $categories = Product::where('status', 'Active')->distinct()->pluck('category')->filter();

        return view('landing.catalog', compact('products', 'categories', 'company'));
    }

    /**
     * Get single product detail
     */
    public function productDetail(Product $product)
    {
        if ($product->status !== 'Active') {
            abort(404, 'Produk tidak aktif.');
        }

        $relatedProducts = Product::where('status', 'Active')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'product' => array_merge($product->toArray(), [
                    'formatted_price' => $product->formatted_price,
                    'stock_badge' => $product->stock_badge,
                    'category_color' => $product->category_color,
                    'product_type' => $product->product_type,
                ]),
                'related' => $relatedProducts,
            ]);
        }

        $company = CompanySetting::current();
        return view('landing.product-detail', compact('product', 'relatedProducts', 'company'));
    }

    /**
     * Process checkout and create online customer order
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|min:2|max:100',
            'customer_phone' => 'required|string|min:8|max:20',
            'customer_email' => 'nullable|email|max:100',
            'shipping_address' => 'required|string|min:10|max:500',
            'city' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:Bank Transfer,QRIS,Cash on Delivery',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:500',
        ], [
            'customer_name.required' => 'Nama lengkap pemesan wajib diisi.',
            'customer_phone.required' => 'Nomor HP / WhatsApp wajib diisi untuk konfirmasi pengiriman.',
            'shipping_address.required' => 'Alamat pengiriman lengkap wajib diisi.',
            'shipping_address.min' => 'Alamat pengiriman terlalu singkat, mohon isi lebih detail.',
            'city.required' => 'Kota / Kabupaten tujuan wajib diisi.',
            'payment_method.required' => 'Pilih salah satu metode pembayaran.',
            'items.required' => 'Keranjang belanja Anda masih kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $orderItems = [];

            // Verify stock and compute total from database prices (prevent price tampering)
            foreach ($validated['items'] as $item) {
                $product = Product::where('id', $item['id'])->lockForUpdate()->firstOrFail();

                if ($product->status !== 'Active') {
                    throw new \Exception("Produk '{$product->product_name}' sedang tidak tersedia.");
                }

                if ($product->system_stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk '{$product->product_name}'. Tersedia: {$product->system_stock} {$product->unit}.");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $totalAmount += $itemSubtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];

                // Deduct stock
                $product->system_stock -= $item['quantity'];
                $product->physical_stock -= $item['quantity'];
                $product->save();
            }

            // Find or create customer
            $phoneClean = preg_replace('/[^0-9]/', '', $validated['customer_phone']);
            $customer = Customer::where('phone', $validated['customer_phone'])
                ->orWhere('phone', $phoneClean)
                ->first();

            if (!$customer && !empty($validated['customer_email'])) {
                $customer = Customer::where('email', $validated['customer_email'])->first();
            }

            $fullAddress = trim($validated['shipping_address'] . ', ' . $validated['city']);

            if (!$customer) {
                $customer = Customer::create([
                    'customer_code' => Customer::generateCode(),
                    'name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'email' => $validated['customer_email'] ?? null,
                    'address' => $fullAddress,
                    'city' => $validated['city'],
                    'total_purchases' => $totalAmount,
                    'status' => 'Active',
                ]);
            } else {
                $customer->total_purchases += $totalAmount;
                if (!empty($validated['customer_email']) && empty($customer->email)) {
                    $customer->email = $validated['customer_email'];
                }
                $customer->save();
            }

            // Generate codes
            $orderCode = SalesInvoice::generateOrderCode();
            $invoiceNumber = SalesInvoice::generateInvoiceNumber();

            // Initial status history
            $initialHistory = [
                [
                    'status' => 'pending',
                    'title' => 'Pesanan Dibuat',
                    'description' => 'Pesanan Anda telah berhasil dibuat dan terdata di sistem PT Buku Nusantara.',
                    'timestamp' => now()->toDateTimeString(),
                    'formatted_time' => now()->translatedFormat('d F Y, H:i'),
                ]
            ];

            // Create SalesInvoice
            $invoice = SalesInvoice::create([
                'invoice_number' => $invoiceNumber,
                'order_code' => $orderCode,
                'date' => now(),
                'customer_id' => $customer->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'total_amount' => $totalAmount,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'due_date' => now()->addDays(7),
                'payment_status' => 'Unpaid',
                'payment_method' => $validated['payment_method'],
                'paid_amount' => 0,
                'notes' => $validated['notes'] ?? null,
                'shipping_address' => $fullAddress,
                'order_status' => 'pending',
                'status_history' => $initialHistory,
                'status_updated_at' => now(),
            ]);

            // Create SalesInvoiceItems
            foreach ($orderItems as $item) {
                SalesInvoiceItem::create([
                    'sales_invoice_id' => $invoice->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->product_name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Store in session for quick access
            session(['last_order_code' => $orderCode]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_code' => $orderCode,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'formatted_total' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                'redirect_url' => route('public.order.success', ['orderCode' => $orderCode]),
                'tracking_url' => route('public.tracking', ['code' => $orderCode]),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Public Checkout Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Display order success screen
     */
    public function orderSuccess($orderCode)
    {
        $order = SalesInvoice::with('items.product', 'customer')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $company = CompanySetting::current();

        return view('landing.success', compact('order', 'company'));
    }

    /**
     * Display order tracking page
     */
    public function trackingPage(Request $request)
    {
        $company = CompanySetting::current();
        $code = trim($request->get('code', ''));
        $order = null;
        $error = null;

        if (!empty($code)) {
            $order = SalesInvoice::with('items.product', 'customer')
                ->where(function ($q) use ($code) {
                    $q->where('order_code', $code)
                      ->orWhere('invoice_number', $code);
                })
                ->first();

            if (!$order) {
                $error = 'Nomor pesanan "' . htmlspecialchars($code) . '" tidak ditemukan. Silakan periksa kembali nomor pesanan Anda (contoh: ORD-2026-00001).';
            }
        }

        return view('landing.tracking', compact('company', 'code', 'order', 'error'));
    }

    /**
     * JSON API for real-time tracking polling
     */
    public function trackOrderApi(Request $request)
    {
        $code = trim($request->get('code', ''));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor pesanan yang valid.',
            ], 400);
        }

        $order = SalesInvoice::with('items.product')
            ->where(function ($q) use ($code) {
                $q->where('order_code', $code)
                  ->orWhere('invoice_number', $code);
            })
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan. Periksa kembali nomor pesanan yang Anda masukkan.',
            ], 404);
        }

        $map = SalesInvoice::statusMap();
        $currentStatus = $order->order_status ?: 'pending';
        $currentMeta = $map[$currentStatus] ?? [
            'title' => ucfirst($currentStatus),
            'description' => '',
            'badge' => 'bg-gray-100 text-gray-800 border-gray-300',
            'color' => '#64748B',
            'step' => 1,
        ];

        // Format timeline stages
        $stages = [
            [
                'key' => 'pending',
                'title' => 'Pesanan Dibuat',
                'step' => 1,
                'icon' => 'document-text',
            ],
            [
                'key' => 'confirmed',
                'title' => 'Dikonfirmasi',
                'step' => 2,
                'icon' => 'check-circle',
            ],
            [
                'key' => 'processing',
                'title' => 'Sedang Diproses',
                'step' => 3,
                'icon' => 'cog',
            ],
            [
                'key' => 'ready',
                'title' => 'Pesanan Siap',
                'step' => 4,
                'icon' => 'truck',
            ],
            [
                'key' => 'completed',
                'title' => 'Selesai',
                'step' => 5,
                'icon' => 'check-badge',
            ],
        ];

        return response()->json([
            'success' => true,
            'order' => [
                'order_code' => $order->order_code ?? $order->invoice_number,
                'invoice_number' => $order->invoice_number,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'order_date' => $order->date ? $order->date->translatedFormat('d F Y') : ($order->created_at ? $order->created_at->translatedFormat('d F Y, H:i') : '-'),
                'total_amount' => $order->total_amount,
                'formatted_total' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                'payment_method' => $order->payment_method ?? 'Bank Transfer',
                'payment_status' => $order->payment_status,
                'order_status' => $currentStatus,
                'status_title' => $currentMeta['title'],
                'status_description' => $currentMeta['description'],
                'status_color' => $currentMeta['color'],
                'current_step' => $currentMeta['step'],
                'status_history' => $order->status_history ?? [],
                'status_updated_at' => $order->status_updated_at ? $order->status_updated_at->translatedFormat('d F Y, H:i:s') : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'formatted_price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                        'subtotal' => $item->subtotal,
                        'formatted_subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                    ];
                }),
            ],
            'stages' => $stages,
        ]);
    }

    /**
     * Customer order history page
     */
    public function orderHistoryPage(Request $request)
    {
        $company = CompanySetting::current();
        $query = trim($request->get('contact', ''));
        $orders = collect();

        if (!empty($query)) {
            $orders = SalesInvoice::with('items')
                ->where(function ($q) use ($query) {
                    $q->where('customer_phone', 'like', "%{$query}%")
                      ->orWhere('customer_email', 'like', "%{$query}%")
                      ->orWhere('customer_name', 'like', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }

        return view('landing.orders', compact('company', 'query', 'orders'));
    }

    /**
     * API lookup for customer order history
     */
    public function orderLookupApi(Request $request)
    {
        $query = trim($request->input('contact', ''));

        if (empty($query) || strlen($query) < 4) {
            return response()->json([
                'success' => false,
                'message' => 'Masukkan nomor HP atau email minimal 4 karakter.',
            ], 400);
        }

        $orders = SalesInvoice::with('items')
            ->where(function ($q) use ($query) {
                $q->where('customer_phone', 'like', "%{$query}%")
                  ->orWhere('customer_email', 'like', "%{$query}%")
                  ->orWhere('customer_name', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

        $map = SalesInvoice::statusMap();

        return response()->json([
            'success' => true,
            'count' => $orders->count(),
            'orders' => $orders->map(function ($order) use ($map) {
                $status = $order->order_status ?: 'pending';
                $meta = $map[$status] ?? ['title' => ucfirst($status), 'color' => '#64748B'];
                return [
                    'order_code' => $order->order_code ?? $order->invoice_number,
                    'invoice_number' => $order->invoice_number,
                    'date' => $order->date ? $order->date->translatedFormat('d M Y') : ($order->created_at ? $order->created_at->translatedFormat('d M Y') : '-'),
                    'total_items' => $order->items->sum('quantity'),
                    'total_amount' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                    'order_status' => $status,
                    'status_title' => $meta['title'],
                    'status_color' => $meta['color'],
                    'tracking_url' => route('public.tracking', ['code' => $order->order_code ?? $order->invoice_number]),
                ];
            }),
        ]);
    }
}
