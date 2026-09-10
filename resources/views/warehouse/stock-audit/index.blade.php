@extends('layouts.app')

@section('content')
<div class="space-y-6">
                <!-- Breadcrumb -->
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700">Inventory</a>
                    <x-icon name="chevron-right" class="mx-2 w-3.5 h-3.5" />
                    <span class="text-gray-900 font-medium">Stock Opname</span>
                </div>

                <!-- Page Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Stock Count & Audit</h1>
                        <p class="text-sm text-gray-500">Verify physical inventory levels and adjust system discrepancies.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('warehouse.stock-audit.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
                            <x-icon name="download" class="w-4 h-4 text-gray-600" />
                            <span class="text-gray-700">Export CSV</span>
                        </a>
                        <a href="{{ route('warehouse.stock-audit.start') }}" class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <x-icon name="play" class="w-4 h-4" />
                            <span>Start Stock Count</span>
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <!-- Total Discrepancy -->
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="bg-red-50 p-3 rounded-lg">
                                <x-icon name="exclamation-triangle" class="text-red-600 w-6 h-6" />
                            </div>
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">-12%</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Discrepancy</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $totalDiscrepancy }}</h3>
                        <p class="text-xs text-gray-600 mt-1">SKUs with Mismatch</p>
                        <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 12%"></div>
                        </div>
                    </div>

                    <!-- Last Audit Date -->
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <x-icon name="calendar-check" class="text-blue-600 w-6 h-6" />
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Last Audit Date</p>
                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $lastAuditDate ? $lastAuditDate->audit_date->format('M d, Y') : 'Never' }}
                        </h3>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $lastAuditDate ? 'By: ' . $lastAuditDate->audited_by : 'No audit history' }}
                        </p>
                    </div>

                    <!-- Accuracy Rate -->
                    <div class="bg-white rounded-xl p-6 border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="bg-green-50 p-3 rounded-lg">
                                <x-icon name="check-circle" class="text-green-600 w-6 h-6" />
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Accuracy Rate</p>
                        <h3 class="text-4xl font-bold text-gray-900">{{ $accuracyRate }}%</h3>
                        <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $accuracyRate }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Filters & Tabs -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <form method="GET" class="flex items-center justify-between gap-4">
                        <!-- Left: Tabs -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('warehouse.stock-audit.index') }}" 
                               class="px-4 py-2 text-sm font-medium rounded-lg {{ !request('filter_by') || request('filter_by') == 'all' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                                All Items
                            </a>
                            <a href="{{ route('warehouse.stock-audit.index', ['filter_by' => 'discrepancies']) }}" 
                               class="px-4 py-2 text-sm font-medium rounded-lg {{ request('filter_by') == 'discrepancies' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                                Discrepancies
                            </a>
                            <a href="{{ route('warehouse.stock-audit.index', ['filter_by' => 'adjusted']) }}" 
                               class="px-4 py-2 text-sm font-medium rounded-lg {{ request('filter_by') == 'adjusted' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:bg-white' }}">
                                Adjusted
                            </a>
                        </div>

                        <!-- Right: Filters -->
                        <div class="flex items-center gap-3">
                            <select name="filter_by" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Filter by Warehouse A (default)</option>
                                <option value="warehouse_b">Warehouse B</option>
                            </select>
                            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Books">Books</option>
                                <option value="Stationery">Stationery</option>
                            </select>
                            <button type="submit" class="px-6 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 text-sm font-medium">
                                <x-icon name="filter" class="w-4 h-4 mr-2" />More Filters
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Selected Items Info -->
                <div id="selected-info" class="hidden bg-gray-900 text-white rounded-lg p-4 mb-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <x-icon name="check-circle" class="text-green-400 w-6 h-6" />
                        <div>
                            <p class="text-sm font-semibold"><span id="selected-count">0</span> items selected</p>
                            <p class="text-xs text-gray-400">142 SKUs completed</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="processSelected()" class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <x-icon name="sync" class="w-4 h-4" />
                            <span>Process All Selected Items</span>
                        </button>
                        <button onclick="closeSelection()" class="p-2 hover:bg-gray-800 rounded">
                            <x-icon name="times" class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-visible">
                    <form id="adjustment-form" action="{{ route('warehouse.stock-audit.process') }}" method="POST">
                        @csrf
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Product Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">System Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Physical Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Difference</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Adjustment Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            @if($product->system_stock != $product->physical_stock)
                                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox w-4 h-4 text-blue-600 rounded">
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-semibold text-blue-600">{{ $product->product_code }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded flex items-center justify-center">
                                                    <x-icon name="box" class="w-4 h-4 text-gray-400" />
                                                </div>
                                                <span class="text-sm font-medium text-gray-900">{{ $product->product_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($product->system_stock) }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($product->physical_stock) }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $diff = $product->physical_stock - $product->system_stock;
                                            @endphp
                                            @if($diff == 0)
                                                <span class="text-sm text-gray-500">0</span>
                                            @elseif($diff > 0)
                                                <span class="text-sm font-semibold text-green-600">+{{ $diff }}</span>
                                            @else
                                                <span class="text-sm font-semibold text-red-600">{{ $diff }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($product->system_stock == $product->physical_stock)
                                                <span class="px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">No Change</span>
                                            @elseif($product->stockAudits->where('adjustment_status', 'Has Adjusted')->count() > 0)
                                                <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Has Adjusted</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Pending Review</span>
                                            @endif
                                        </td>
                                         <td class="px-6 py-4">
                                             <div class="relative inline-block text-left action-menu-container">
                                                 <button type="button" onclick="toggleActionMenu(this, event)" class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition border border-gray-200 shadow-sm" title="Aksi">
                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                     </svg>
                                                 </button>
                                                  <div class="action-menu-popup absolute right-0 top-full mt-1 z-50 bg-white border border-gray-200 rounded-xl shadow-xl p-1.5 items-center gap-1 min-w-max" style="display: none;">
                                                     <a href="{{ route('warehouse.stock-audit.start') }}" title="Start Count / Adjust" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
                                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                         </svg>
                                                     </a>
                                                 </div>
                                             </div>
                                         </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <x-icon name="inbox" class="w-4 h-4 text-4xl mb-3 text-gray-300" />
                                            <p>No products found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Footer -->
                        @include('partials.pagination', ['paginator' => $products])
                    </form>
                </div>
</div>
@endsection

@push('scripts')
    <script>
        // Select all checkbox
        document.getElementById('select-all')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });

        // Individual checkbox
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.product-checkbox:checked').length;
            document.getElementById('selected-count').textContent = checked;
            document.getElementById('selected-info').classList.toggle('hidden', checked === 0);
        }

        function processSelected() {
            if(confirm('Process all selected items and adjust system stock?')) {
                document.getElementById('adjustment-form').submit();
            }
        }

        function closeSelection() {
            document.querySelectorAll('.product-checkbox').forEach(cb => cb.checked = false);
            updateSelectedCount();
        }
    </script>
@endpush
