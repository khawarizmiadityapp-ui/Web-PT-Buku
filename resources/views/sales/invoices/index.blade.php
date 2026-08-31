@extends('layouts.app')

@section('title', 'Sales Invoices - PT Nusantara ERP')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Sales Invoices</h1>
            <p class="text-sm text-gray-500">Manage and track customer billing and payments.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('sales.invoices.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                <i class="fas fa-download text-gray-600"></i>
                <span class="text-gray-700">Export CSV</span>
            </a>
            <a href="{{ route('sales.report') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                <i class="fas fa-print text-gray-600"></i>
                <span class="text-gray-700">Print Report</span>
            </a>
            <a href="{{ route('sales.invoices.create') }}" class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium shadow-sm">
                <i class="fas fa-plus"></i>
                <span>Create Invoice</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Revenue This Month -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="bg-blue-500 p-3 rounded-lg">
                    <i class="fas fa-receipt text-white text-xl"></i>
                </div>
            </div>
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Revenue Month</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue_month'], 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-600 mt-1">Based on paid invoices</p>
        </div>

        <!-- Total Unpaid -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="bg-red-500 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
            <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-1">Total Unpaid</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_unpaid'], 0, ',', '.') }}</h3>
            <p class="text-xs text-gray-600 mt-1">Awaiting pending invoices</p>
        </div>

        <!-- Invoices Issued -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div class="bg-indigo-500 p-3 rounded-lg">
                    <i class="fas fa-file-invoice text-white text-xl"></i>
                </div>
            </div>
            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">Invoices Issued</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['invoices_issued']) }}</h3>
            <p class="text-xs text-gray-600 mt-1">Issued this year</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <div class="flex items-center space-x-6">
            <a href="{{ route('sales.invoices.index') }}" 
               class="pb-2 border-b-2 {{ !request('tab') ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                All Invoices
            </a>
            <a href="{{ route('sales.invoices.index', ['tab' => 'paid']) }}" 
               class="pb-2 border-b-2 {{ request('tab') == 'paid' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                Paid
            </a>
            <a href="{{ route('sales.invoices.index', ['tab' => 'unpaid']) }}" 
               class="pb-2 border-b-2 {{ request('tab') == 'unpaid' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                Unpaid
            </a>
            <a href="{{ route('sales.invoices.index', ['tab' => 'overdue']) }}" 
               class="pb-2 border-b-2 {{ request('tab') == 'overdue' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                Overdue
            </a>
        </div>

        <!-- Auto Filter Bar (without button) -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <form method="GET" action="{{ route('sales.invoices.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
                @if(request('tab'))
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                @endif

                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto flex-1">
                    <!-- Search -->
                    <div class="relative flex-1 min-w-[200px] max-w-xs">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search Invoice or Customer..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                            onchange="this.form.submit()"
                        >
                    </div>

                    <!-- Date Range -->
                    <input 
                        type="date" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        title="From Date"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                        onchange="this.form.submit()"
                    >
                    <span class="text-gray-400 text-sm">-</span>
                    <input 
                        type="date" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        title="To Date"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                        onchange="this.form.submit()"
                    >

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">All Statuses</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>

                    @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
                        <a href="{{ route('sales.invoices.index', array_filter(['tab' => request('tab')])) }}" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-visible shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Invoice Number</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Date</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Customer</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Total Amount</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Due Date</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Payment Status</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('sales.invoices.show', $invoice) }}" class="font-bold text-blue-600 hover:underline">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ $invoice->date ? $invoice->date->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $invoice->customer_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($invoice->payment_status == 'Paid')
                                <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full">Paid</span>
                            @elseif($invoice->payment_status == 'Unpaid')
                                <span class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full">Unpaid</span>
                            @elseif($invoice->payment_status == 'Overdue')
                                <span class="px-3 py-1 text-xs font-semibold text-rose-700 bg-rose-100 rounded-full">Overdue</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">{{ $invoice->payment_status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="relative inline-block text-left action-menu-container">
                                <button type="button" onclick="toggleActionMenu(this, event)" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition border border-gray-200 shadow-sm" title="Aksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div class="action-menu-popup absolute right-0 top-full mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-xl p-1.5 items-center gap-1 min-w-max" style="display: none;">
                                    <a href="{{ route('sales.invoices.show', $invoice) }}" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition" title="View Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm font-medium">Tidak ada data invoice</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Footer -->
        @include('partials.pagination', ['paginator' => $invoices])
    </div>
</div>
@endsection
