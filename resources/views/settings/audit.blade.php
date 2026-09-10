@extends('layouts.app')

@section('title', 'Settings - Audit Log')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">System Audit Log</h1>
        <p class="text-gray-500 mt-1">Track all system activities and changes</p>
    </div>

    @include('settings._tabs')

    <div class="bg-white rounded-b-xl border border-gray-200 border-t-0 p-6">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Events (24h)</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_24h']) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Security Alerts</p>
                <p class="text-2xl font-bold {{ $stats['security_alerts'] > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">{{ $stats['security_alerts'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Storage Usage</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">4.2 <span class="text-sm font-normal text-gray-500">GB of 10GB</span></p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Users</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['active_users'] }} <span class="text-sm font-normal text-green-600">Live</span></p>
            </div>
        </div>

        {{-- Header + Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
            <h2 class="text-lg font-semibold text-gray-900">Activity Log</h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('settings.audit.export', request()->query()) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <x-icon name="download" class="w-4 h-4 mr-2" />Export CSV
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('settings.audit') }}" class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                    <select name="action_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="">All Types</option>
                        @foreach(['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT', 'SYSTEM'] as $type)
                            <option value="{{ $type }}" {{ request('action_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <x-icon name="filter" class="w-4 h-4 mr-2" />Filter
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('settings.audit') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <span class="text-sm text-gray-500">Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user?->name ?? 'System') }}&background=4F46E5&color=fff&size=32" class="w-7 h-7 rounded-full">
                                    <span class="text-sm font-medium text-gray-900">{{ $log->user?->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->action_badge_class }}">
                                    {{ $log->action_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button type="button" onclick="alert('{{ addslashes($log->description) }}\n\nTable: {{ $log->subject_table ?? '-' }}\nUser Agent: {{ Str::limit($log->user_agent ?? '-', 80) }}')" class="text-blue-600 hover:text-blue-800">
                                    <x-icon name="eye" class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <x-icon name="clipboard-list" class="w-4 h-4 text-4xl text-gray-300 mb-4" />
                                    <p class="text-lg font-medium text-gray-900 mb-1">No audit logs found</p>
                                    <p class="text-sm">System activities will be recorded here automatically.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $logs])
        </div>

        <p class="text-xs text-gray-400 mt-4 text-center">
            System integrity verified &bull; Server status: Online
        </p>
    </div>
</div>
@endsection
