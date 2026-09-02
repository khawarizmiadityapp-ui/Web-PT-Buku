<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        // Period Filter: 'all', 'this_month', 'last_month', 'this_year', 'custom'
        $period = $request->get('period', 'all');

        if ($period === 'this_month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($period === 'last_month') {
            $startDate = now()->subMonth()->startOfMonth()->toDateString();
            $endDate = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($period === 'this_year') {
            $startDate = now()->startOfYear()->toDateString();
            $endDate = now()->endOfYear()->toDateString();
        } elseif ($period === 'custom' && $request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
        } else {
            $period = 'all';
            $startDate = $request->get('start_date', '2025-01-01');
            $endDate = $request->get('end_date', now()->addYear()->endOfYear()->toDateString());
        }

        // Real Revenue from Sales Invoices
        $revenue = (float) SalesInvoice::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        
        // Real Expenses from Purchase Orders (Excluding Canceled)
        $totalExpenses = (float) Purchase::whereBetween('po_date', [$startDate, $endDate])
            ->where('status', '!=', 'Canceled')
            ->sum('total_amount');

        // Net Profit = Revenue - Expenses
        $netProfit = $revenue - $totalExpenses;

        // Calculate Real Period-over-Period (MoM) Growth
        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);
        $daysDiff = max(1, $startCarbon->diffInDays($endCarbon) + 1);
        $prevEndDate = $startCarbon->copy()->subDay()->toDateString();
        $prevStartDate = $startCarbon->copy()->subDays($daysDiff)->toDateString();

        $prevRevenue = (float) SalesInvoice::whereBetween('date', [$prevStartDate, $prevEndDate])->sum('total_amount');
        $prevExpenses = (float) Purchase::whereBetween('po_date', [$prevStartDate, $prevEndDate])
            ->where('status', '!=', 'Canceled')
            ->sum('total_amount');
        $prevProfit = $prevRevenue - $prevExpenses;

        // Dynamic Profit Growth (%)
        if ($prevProfit != 0) {
            $profitGrowth = round((($netProfit - $prevProfit) / abs($prevProfit)) * 100, 1);
        } else {
            $profitGrowth = ($netProfit > 0) ? 100.0 : ($netProfit < 0 ? -100.0 : 0.0);
        }

        // Dynamic Expense Growth (%)
        if ($prevExpenses != 0) {
            $expenseGrowth = round((($totalExpenses - $prevExpenses) / abs($prevExpenses)) * 100, 1);
        } else {
            $expenseGrowth = ($totalExpenses > 0) ? 100.0 : 0.0;
        }

        // Real Accounts Receivable (Piutang Riil)
        $accountsReceivable = (float) SalesInvoice::where('payment_status', '!=', 'Paid')
            ->sum(DB::raw('total_amount - COALESCE(paid_amount, 0)'));
        
        $totalSalesAll = (float) SalesInvoice::sum('total_amount') ?: 1;
        $arRatio = round(($accountsReceivable / $totalSalesAll) * 100, 1);
        $arGrowth = $arRatio;

        // Handle CSV Export
        if ($request->has('export') && $request->export == 'csv') {
            $filename = 'laporan_keuangan_' . now()->format('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($startDate, $endDate, $revenue, $netProfit, $totalExpenses, $accountsReceivable) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, ["Ringkasan Laporan Keuangan PT Distribusi Buku dan ATK Nusantara"]);
                fputcsv($file, ["Filter Periode: {$startDate} s/d {$endDate}"]);
                fputcsv($file, []);
                fputcsv($file, ['Metrik Keuangan', 'Nilai (Rp)']);
                fputcsv($file, ['Total Pendapatan (Revenue)', $revenue]);
                fputcsv($file, ['Total Pengeluaran PO (Total Expenses)', $totalExpenses]);
                fputcsv($file, ['Laba Bersih (Net Profit)', $netProfit]);
                fputcsv($file, ['Total Piutang Belum Terbayar (Accounts Receivable)', $accountsReceivable]);
                fputcsv($file, []);

                fputcsv($file, ['Bulan', 'Pendapatan (Rp)', 'Pengeluaran (Rp)', 'Laba Bersih (Rp)', 'Margin (%)']);
                
                $months = DB::table('sales_invoices')
                    ->selectRaw('DATE_FORMAT(date, "%Y-%m") as m_key, DATE_FORMAT(MAX(date), "%M %Y") as m_label, SUM(total_amount) as m_rev')
                    ->groupByRaw('DATE_FORMAT(date, "%Y-%m")')
                    ->orderByRaw('m_key DESC')
                    ->get();

                foreach ($months as $m) {
                    $mExp = (float) Purchase::whereRaw('DATE_FORMAT(po_date, "%Y-%m") = ?', [$m->m_key])
                        ->where('status', '!=', 'Canceled')
                        ->sum('total_amount');
                    $mNet = (float) $m->m_rev - $mExp;
                    $mMargin = $m->m_rev > 0 ? round(($mNet / $m->m_rev) * 100, 1) : 0;
                    fputcsv($file, [$m->m_label, $m->m_rev, $mExp, $mNet, $mMargin . '%']);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Cash Flow Trends (Past 6 Months Dynamic: Inflow vs Outflow)
        $cashFlowData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $mNum = $month->month;
            $yNum = $month->year;

            $monthInflow = (float) SalesInvoice::whereMonth('date', $mNum)
                ->whereYear('date', $yNum)
                ->sum('total_amount');

            $monthOutflow = (float) Purchase::whereMonth('po_date', $mNum)
                ->whereYear('po_date', $yNum)
                ->where('status', '!=', 'Canceled')
                ->sum('total_amount');

            $cashFlowData[] = [
                'month' => $month->format('M Y'),
                'month_short' => $month->format('M'),
                'inflow' => $monthInflow,
                'outflow' => $monthOutflow,
                'net' => $monthInflow - $monthOutflow,
            ];
        }

        // Real Expense Distribution grouped by Product Categories
        $categoryExpenses = DB::table('purchase_items')
            ->join('products', 'purchase_items.product_id', '=', 'products.id')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->where('purchases.status', '!=', 'Canceled')
            ->whereNull('purchases.deleted_at')
            ->select('products.category', DB::raw('SUM(purchase_items.subtotal) as total'))
            ->groupBy('products.category')
            ->get();

        $totalCatSum = $categoryExpenses->sum('total');
        $expenseDistribution = [];
        $colorPalettes = ['bg-blue-600', 'bg-indigo-600', 'bg-emerald-600', 'bg-amber-600', 'bg-purple-600', 'bg-rose-600'];

        if ($categoryExpenses->isNotEmpty() && $totalCatSum > 0) {
            foreach ($categoryExpenses as $idx => $cat) {
                $catName = $cat->category ?: 'Umum / Lainnya';
                $catTotal = (float) $cat->total;
                $pct = round(($catTotal / $totalCatSum) * 100, 1);
                $expenseDistribution[] = [
                    'category' => $catName,
                    'amount' => $catTotal,
                    'percentage' => $pct,
                    'color' => $colorPalettes[$idx % count($colorPalettes)],
                ];
            }
        } else {
            $productsByCategory = Product::select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->get();
            $totalProdCount = $productsByCategory->sum('count') ?: 1;

            if ($productsByCategory->isNotEmpty()) {
                foreach ($productsByCategory as $idx => $cat) {
                    $catName = $cat->category ?: 'Kategori Umum';
                    $pct = round(($cat->count / $totalProdCount) * 100, 1);
                    $expenseDistribution[] = [
                        'category' => $catName,
                        'amount' => $totalExpenses * ($pct / 100),
                        'percentage' => $pct,
                        'color' => $colorPalettes[$idx % count($colorPalettes)],
                    ];
                }
            } else {
                $expenseDistribution = [
                    ['category' => 'Buku Pelajaran & Referensi', 'amount' => $totalExpenses * 0.50, 'percentage' => 50, 'color' => 'bg-blue-600'],
                    ['category' => 'Alat Tulis Kantor & Kertas', 'amount' => $totalExpenses * 0.30, 'percentage' => 30, 'color' => 'bg-indigo-600'],
                    ['category' => 'Biaya Operasional & Pengiriman', 'amount' => $totalExpenses * 0.20, 'percentage' => 20, 'color' => 'bg-amber-600'],
                ];
            }
        }

        // Monthly Financial Summary (Past 12 Months Dynamic)
        $distinctMonths = DB::table('sales_invoices')
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month_key, DATE_FORMAT(MAX(date), "%M %Y") as month_label, SUM(total_amount) as revenue')
            ->where('date', '>=', now()->subMonths(12))
            ->whereNull('deleted_at')
            ->groupByRaw('DATE_FORMAT(date, "%Y-%m")')
            ->orderByRaw('month_key DESC')
            ->get();

        $monthlySummary = [];
        foreach ($distinctMonths as $dm) {
            $mExpenses = (float) Purchase::whereRaw('DATE_FORMAT(po_date, "%Y-%m") = ?', [$dm->month_key])
                ->where('status', '!=', 'Canceled')
                ->whereNull('deleted_at')
                ->sum('total_amount');
            
            $mRevenue = (float) $dm->revenue;
            $mNetProfit = $mRevenue - $mExpenses;
            $mMargin = $mRevenue > 0 ? round(($mNetProfit / $mRevenue) * 100, 1) : 0;

            if ($mMargin >= 20) {
                $status = 'HEALTHY';
                $statusBadge = 'bg-green-100 text-green-700 border-green-200';
            } elseif ($mMargin >= 0) {
                $status = 'MODERATE';
                $statusBadge = 'bg-yellow-100 text-yellow-700 border-yellow-200';
            } else {
                $status = 'DEFICIT';
                $statusBadge = 'bg-red-100 text-red-700 border-red-200';
            }

            $monthlySummary[] = (object) [
                'month' => $dm->month_label,
                'month_key' => $dm->month_key,
                'revenue' => $mRevenue,
                'expenses' => $mExpenses,
                'net_profit' => $mNetProfit,
                'margin' => $mMargin,
                'status' => $status,
                'status_badge' => $statusBadge,
            ];
        }

        return view('reports.financial', compact(
            'netProfit',
            'profitGrowth',
            'totalExpenses',
            'expenseGrowth',
            'accountsReceivable',
            'arGrowth',
            'revenue',
            'cashFlowData',
            'expenseDistribution',
            'monthlySummary',
            'startDate',
            'endDate',
            'period'
        ));
    }
}

