<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.tailwind');

        \Illuminate\Support\Facades\View::composer(['layouts.header', 'layouts.sidebar'], function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            
            if ($user) {
                $role = $user->role;
                
                $navMenus = \App\Models\Menu::with('children')
                    ->where('role', $role)
                    ->where('location', 'navbar')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();
                    
                $sidebarMenus = \App\Models\Menu::with('children')
                    ->where('role', $role)
                    ->where('location', 'sidebar')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();

                // Dynamic System Notifications from Database
                $globalNotifications = collect();

                // 1. Low stock alerts
                try {
                    $lowStockProducts = \App\Models\Product::where('system_stock', '<=', 10)->take(3)->get();
                    foreach ($lowStockProducts as $p) {
                        $globalNotifications->push((object)[
                            'icon' => 'fas fa-exclamation-triangle',
                            'bg_color' => 'bg-amber-100 text-amber-600',
                            'title' => "Stok Menipis: {$p->product_name}",
                            'message' => "Stok sisa {$p->system_stock} {$p->unit}. Perlu restok segera.",
                            'time' => 'Sistem Gudang',
                            'link' => route('products.index')
                        ]);
                    }

                    // 2. Pending Purchase Orders
                    $pendingPOs = \App\Models\Purchase::where('status', 'Pending')->take(3)->get();
                    foreach ($pendingPOs as $po) {
                        $globalNotifications->push((object)[
                            'icon' => 'fas fa-file-invoice-dollar',
                            'bg_color' => 'bg-blue-100 text-blue-600',
                            'title' => "PO Menunggu Approval: {$po->po_number}",
                            'message' => "Total Rp " . number_format($po->total_amount, 0, ',', '.') . " perlu konfirmasi.",
                            'time' => $po->created_at ? $po->created_at->diffForHumans() : 'Hari ini',
                            'link' => route('purchases.index')
                        ]);
                    }

                    // 3. Unpaid Sales Invoices
                    $unpaidInvoices = \App\Models\SalesInvoice::whereIn('payment_status', ['Unpaid', 'Partial'])->take(3)->get();
                    foreach ($unpaidInvoices as $inv) {
                        $globalNotifications->push((object)[
                            'icon' => 'fas fa-clock',
                            'bg_color' => 'bg-red-100 text-red-600',
                            'title' => "Invoice Belum Lunas: {$inv->invoice_number}",
                            'message' => "{$inv->customer_name} sisa tagihan Rp " . number_format(max(0, $inv->total_amount - $inv->paid_amount), 0, ',', '.'),
                            'time' => $inv->created_at ? $inv->created_at->diffForHumans() : 'Tagihan',
                            'link' => route('sales.invoices.index')
                        ]);
                    }
                } catch (\Exception $e) {
                    // Fallback empty if any table doesn't exist yet
                }
                    
                $view->with('navMenus', $navMenus);
                $view->with('sidebarMenus', $sidebarMenus);
                $view->with('globalNotifications', $globalNotifications);
            }
        });
    }
}
