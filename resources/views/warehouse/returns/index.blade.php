@extends('layouts.app')

@section('content')
<div class="space-y-6">
                <!-- Page Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">Retur Barang</h1>
                        <p class="text-sm text-gray-500">Manage and track inventory returns from customers and to suppliers.</p>
                    </div>
                    <button class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                        <i class="fas fa-plus"></i>
                        <span>New Return</span>
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-blue-50 p-2 rounded-lg">
                                <i class="fas fa-undo text-blue-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Total Returns</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_returns']) }}</h3>
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-arrow-up"></i> +7% vs last week
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-yellow-50 p-2 rounded-lg">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Pending Approvals</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending_approvals'] }}</h3>
                        <p class="text-xs text-gray-600 mt-1">Active now</p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-green-50 p-2 rounded-lg">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Approved Today</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['approved_today'] }}</h3>
                        <p class="text-xs text-green-600 mt-1">+3 since last hour</p>
                    </div>

                    <div class="bg-white rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="bg-red-50 p-2 rounded-lg">
                                <i class="fas fa-times-circle text-red-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Rejected (1wk)</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['rejected_this_week'] }}</h3>
                        <p class="text-xs text-red-600 mt-1">-3% vs last wk</p>
                    </div>
                </div>

                <!-- Tabs & Filters -->
                <div class="bg-white border-b border-gray-200 mb-6">
                    <div class="flex items-center justify-between">
                        <!-- Tabs -->
                        <div class="flex items-center space-x-6">
                            <a href="{{ route('warehouse.returns.index') }}" 
                               class="pb-3 border-b-2 {{ !request('tab') ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                                All Returns
                            </a>
                            <a href="{{ route('warehouse.returns.index', ['tab' => 'sales']) }}" 
                               class="pb-3 border-b-2 {{ request('tab') == 'sales' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                                Sales Return
                            </a>
                            <a href="{{ route('warehouse.returns.index', ['tab' => 'purchase']) }}" 
                               class="pb-3 border-b-2 {{ request('tab') == 'purchase' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }} text-sm">
                                Purchase Return
                            </a>
                        </div>

                        <!-- Date Range & Filters -->
                        <div class="flex items-center gap-3">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                <option>Last 30 Days</option>
                                <option>Last 7 Days</option>
                                <option>This Month</option>
                            </select>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">
                                <i class="fas fa-filter mr-2"></i>More Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-6">
                    <form method="GET" class="relative max-w-md">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search transactions..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                        >
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white border border-gray-200 rounded-lg overflow-visible">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Return ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Entity</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($returns as $return)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-blue-600">{{ $return->return_id }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $return->date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $return->date->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center">
                                                <i class="fas fa-building text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $return->entity }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($return->type == 'SALES')
                                            <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">SALES</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">PURCHASE</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $return->reason }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($return->items) }}</td>
                                    <td class="px-6 py-4">
                                        @if($return->status == 'Pending')
                                            <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>
                                        @elseif($return->status == 'Approved')
                                            <span class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Approved</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Rejected</span>
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
                                                 <a href="{{ route('returns.index') }}" title="Detail Retur" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition">
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
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                        <p>No returns found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($returns->hasPages())
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Showing {{ $returns->firstItem() }} to {{ $returns->lastItem() }} of {{ $returns->total() }} entries
                        </div>
                        <div>
                            {{ $returns->links() }}
                        </div>
                    </div>
                @endif
</div>
@endsection
