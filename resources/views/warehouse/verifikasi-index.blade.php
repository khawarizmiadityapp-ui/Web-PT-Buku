@extends('layouts.app')

@section('title', 'Verifikasi Barang Masuk - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-900">Verifikasi Barang Masuk</h1>
        <p class="text-gray-500 mt-1">Daftar penerimaan barang yang menunggu pencocokan fisik</p>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th>No. Penerimaan</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="text-center px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incomingGoods as $good)
                        <tr>
                            <td class="px-4">{{ \Carbon\Carbon::parse($good->receive_date)->format('d M Y') }}</td>
                            <td class="font-medium text-blue-600">{{ $good->receipt_number }}</td>
                            <td>{{ $good->supplier->nama }}</td>
                            <td>
                                @if($good->status == 'Pending')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Menunggu Verifikasi</span>
                                @elseif($good->status == 'Revised')
                                    <span class="badge bg-info text-white px-2 py-1 rounded-pill">Telah Direvisi</span>
                                @endif
                            </td>
                            <td class="text-center px-4">
                                <div class="relative inline-block text-start action-menu-container">
                                    <button type="button" onclick="toggleActionMenu(this, event)" class="btn btn-sm btn-light border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Aksi">
                                        <svg class="bi mx-auto" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                    <div class="action-menu-popup position-absolute end-0 top-100 mt-1 z-3 bg-white border rounded-3 shadow p-1 align-items-center gap-1 min-w-max" style="display: none;">
                                        <a href="{{ route('warehouse.verifikasi.show', $good->id) }}" class="btn btn-sm btn-light text-primary p-2" title="Proses Verifikasi">
                                            <svg class="bi" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-gray-500">
                                <div class="mb-2"><i class="fas fa-box-open fa-2x text-gray-300"></i></div>
                                Tidak ada data barang masuk yang menunggu verifikasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top">
                {{ $incomingGoods->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
