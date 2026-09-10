@extends('layouts.app')

@section('title', 'Stok Gudang - LogiBook WMS')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stok Gudang</h1>
            <p class="text-gray-500 mt-1">Real-time inventory levels across all warehouses zones.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="exportCSV()">
                <x-icon name="download" class="w-4 h-4 me-2" /> Export CSV
            </button>
            <a href="{{ route('warehouse.incoming-goods') }}" class="btn btn-primary">
                <x-icon name="plus" class="w-4 h-4 me-2" /> Stock Levels
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #3B82F6;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 bg-blue-50 rounded-lg me-3">
                            <x-icon name="box" class="text-primary w-8 h-8" />
                        </div>
                        <div>
                            <div class="text-muted small">Total SKUs</div>
                            <h3 class="mb-0">{{ number_format($stats['total_sku']) }}</h3>
                            <small class="text-success"><x-icon name="arrow-up" class="w-4 h-4" /> +98 this week</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10B981;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 bg-success bg-opacity-10 rounded-lg me-3">
                            <x-icon name="cubes" class="text-success w-8 h-8" />
                        </div>
                        <div>
                            <div class="text-muted small">Total Units</div>
                            <h3 class="mb-0">{{ number_format($stats['total_units']) }}</h3>
                            <small class="text-muted">Across all locations</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #F59E0B;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 bg-warning bg-opacity-10 rounded-lg me-3">
                            <x-icon name="exclamation-triangle" class="text-warning w-8 h-8" />
                        </div>
                        <div>
                            <div class="text-muted small">Dibawah 68% • <span class="text-warning">Waspada Sivaraga</span></div>
                            <h3 class="mb-0">{{ $stats['reserved_units'] }}</h3>
                            <small class="text-muted">Reserved for orders</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #EF4444;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 p-3 bg-danger bg-opacity-10 rounded-lg me-3">
                            <x-icon name="box-open" class="text-danger w-8 h-8" />
                        </div>
                        <div>
                            <div class="text-muted small">Dibawah 20% • <span class="text-danger">Urgent</span></div>
                            <h3 class="mb-0">{{ number_format($stats['available_units']) }}</h3>
                            <small class="text-muted">Immediately not dispatch</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">WAREHOUSE LOCATION</label>
                    <select class="form-select">
                        <option>All Warehouses</option>
                        <option>Warehouse A</option>
                        <option>Warehouse B</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">CATEGORY</label>
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>Books</option>
                        <option>Stationery</option>
                        <option>Art Supplies</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">STATUS</label>
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Normal</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">&nbsp;</label>
                    <button class="btn btn-primary w-100">
                        <x-icon name="filter" class="w-4 h-4 me-2" /> Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th>ITEM <br> NUMBER</th>
                            <th>NAMA BARANG</th>
                            <th>KATEGORI</th>
                            <th>GUDANG</th>
                            <th>RAK</th>
                            <th>SISI</th>
                            <th class="text-center">UMUR <br> BARANG</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $product->product_code }}</strong>
                            </td>
                            <td>
                                <div>{{ $product->product_name }}</div>
                                <small class="text-muted">{{ $product->supplier ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $product->category }}</span>
                            </td>
                            <td>
                                <div class="small">
                                    <x-icon name="warehouse" class="w-4 h-4 text-primary me-1" />
                                    Central Hub
                                </div>
                            </td>
                            <td>
                                <div class="small">Zone B-52</div>
                            </td>
                            <td>
                                <div class="small">
                                    @if($product->system_stock > 100)
                                        <span class="badge bg-success">Normal</span>
                                    @elseif($product->system_stock > 50)
                                        <span class="badge bg-warning">Low</span>
                                    @else
                                        <span class="badge bg-danger">Critical</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="small">{{ $product->system_stock }}</div>
                            </td>
                            <td class="text-center">
                                @php
                                    $stockPercentage = ($product->system_stock / ($product->max_stock ?? 1000)) * 100;
                                @endphp
                                @if($stockPercentage > 70)
                                    <span class="badge bg-success">Normal</span>
                                @elseif($stockPercentage > 30)
                                    <span class="badge bg-warning">Warning</span>
                                @else
                                    <span class="badge bg-danger">Critical</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="relative inline-block text-start action-menu-container">
                                    <button type="button" onclick="toggleActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                        <svg class="bi mx-auto" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div class="action-menu-popup position-absolute end-0 top-100 mt-1 z-3 bg-white border rounded-3 shadow p-1 align-items-center gap-1 min-w-max" style="display: none;">
                                        <button type="button" title="View Details" class="btn btn-sm btn-light text-primary p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('products.edit', $product->id) }}" title="Edit" class="btn btn-sm btn-light text-warning p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button type="button" title="History" class="btn btn-sm btn-light text-info p-2">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <x-icon name="box-open" class="w-12 h-12 text-muted mb-3 d-block" />
                                <p class="text-muted">No stock data available</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @include('partials.pagination', ['paginator' => $products])
        </div>
    </div>
</div>

    <!-- Bottom Cards -->
    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-lg me-3">
                            <x-icon name="chart-bar" class="text-primary w-8 h-8" />
                        </div>
                        <div>
                            <h5 class="mb-0">Restock Analysis</h5>
                            <small class="text-muted">Items requiring restocking in next 7 days</small>
                        </div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <x-icon name="info-circle" class="w-4 h-4 me-2" />
                        23 items need reordering. View detailed analysis →
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded-lg me-3">
                            <x-icon name="map-marked-alt" class="text-warning w-8 h-8" />
                        </div>
                        <div>
                            <h5 class="mb-0">Zone Optimization</h5>
                            <small class="text-muted">Improve warehouse layout for efficiency</small>
                        </div>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <x-icon name="exclamation-triangle" class="w-4 h-4 me-2" />
                        Zone B-6 is running at 93% capacity. Consider moving 5 low-turnover items
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportCSV() {
    window.location.href = '{{ route("warehouse.stock") }}?export=csv';
}
</script>
@endsection
