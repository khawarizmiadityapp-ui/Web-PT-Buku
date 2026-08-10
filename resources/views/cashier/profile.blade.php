@extends('layouts.app')

@section('title', 'Cashier Profile - PT Nusantara')

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-4">Cashier Profile</h2>

    <div class="row g-3">
        <!-- Left Side - Profile Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="position-relative d-inline-block mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=150&background=0d6efd&color=fff" 
                             alt="Profile" class="rounded-circle" style="width: 150px; height: 150px;">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" 
                              style="width: 20px; height: 20px;"></span>
                    </div>

                    <!-- Name & Role -->
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <span class="badge bg-primary mb-3">Active Cashier</span>

                    <!-- Info Grid -->
                    <div class="row g-2 text-start mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Employee ID</small>
                            <strong>#{{ str_pad(auth()->user()->id, 6, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Shift Start</small>
                            <strong>08:00 AM</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Warehouse Section</small>
                            <strong>North Aisle B</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">Active</span>
                        </div>
                    </div>

                    <hr>

                    <!-- Today's Performance -->
                    <div class="text-start mb-3">
                        <h6 class="text-muted mb-3">TODAY'S PERFORMANCE</h6>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Transactions</span>
                            <strong>{{ $todayStats['transactions'] ?? 0 }}</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Returns Handled</span>
                            <strong class="text-warning">{{ $todayStats['returns'] ?? 0 }}</strong>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Value Processed</span>
                            <strong class="text-success">Rp {{ number_format($todayStats['total_value'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('settings.profile.view') }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </a>
                        <button class="btn btn-danger" onclick="confirmSignOut()">
                            <i class="fas fa-sign-out-alt me-2"></i> Sign Out & End Shift
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Settings & Activity -->
        <div class="col-lg-8">
            <!-- Terminal Hardware Settings -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-desktop text-primary me-2"></i> Terminal Hardware Settings
                    </h5>

                    <div class="row g-3">
                        <!-- Receipt Printer -->
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <small class="text-muted">Default Receipt Printer</small>
                                        <div class="mt-1">
                                            <strong>EPSON TM-T88VI (Thermal) - STATION_01</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-sync me-1"></i> Test Connection
                                    </button>
                                    <span class="badge bg-success align-self-center">
                                        <i class="fas fa-check-circle me-1"></i> Ready
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Drawer -->
                        <div class="col-md-6">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <small class="text-muted">Cash Drawer Connection</small>
                                        <div class="mt-1">
                                            <strong>Drawer #04 (USB) Status: Closed &</strong>
                                            <div class="small text-muted">Locked</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check-circle me-1"></i> Connected
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Action -->
                        <div class="col-12">
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div class="flex-grow-1">
                                    <strong>Emergency Open (Requires Manager ID)</strong>
                                </div>
                                <button class="btn btn-warning">
                                    <i class="fas fa-unlock me-1"></i> Emergency Open
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Session Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock text-primary me-2"></i> Today's Session Activity
                        </h5>
                        <a href="#" class="text-primary small">View Full Log</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>TIME</th>
                                    <th>EVENT</th>
                                    <th>TRANSACTION ID</th>
                                    <th>AMOUNT</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $activities = [
                                    ['time' => '11:42 AM', 'event' => 'Order Completed', 'id' => 'TRX-9921', 'amount' => 342.10, 'status' => 'SUCCESS'],
                                    ['time' => '11:15 AM', 'event' => 'Return Processed', 'id' => 'RTN-0012', 'amount' => -84.00, 'status' => 'IN-PROC'],
                                    ['time' => '10:53 AM', 'event' => 'Order Completed', 'id' => 'TRX-9918', 'amount' => 1290.00, 'status' => 'SUCCESS'],
                                    ['time' => '10:30 AM', 'event' => 'Terminal Login', 'id' => '---', 'amount' => 0, 'status' => 'INTERNAL'],
                                    ['time' => '08:50 AM', 'event' => 'Order Completed', 'id' => 'TRX-9912', 'amount' => 645.00, 'status' => 'SUCCESS'],
                                ];
                                @endphp

                                @foreach($activities as $activity)
                                <tr>
                                    <td><small>{{ $activity['time'] }}</small></td>
                                    <td>{{ $activity['event'] }}</td>
                                    <td>
                                        @if($activity['id'] !== '---')
                                        <a href="#" class="text-primary">{{ $activity['id'] }}</a>
                                        @else
                                        <span class="text-muted">---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity['amount'] != 0)
                                        <strong class="{{ $activity['amount'] < 0 ? 'text-danger' : '' }}">
                                            {{ $activity['amount'] < 0 ? '-' : '' }}${{ number_format(abs($activity['amount']), 2) }}
                                        </strong>
                                        @else
                                        <span class="text-muted">---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity['status'] == 'SUCCESS')
                                        <span class="badge bg-success">SUCCESS</span>
                                        @elseif($activity['status'] == 'IN-PROC')
                                        <span class="badge bg-warning">IN-PROC</span>
                                        @else
                                        <span class="badge bg-secondary">INTERNAL</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row g-2 mt-3">
                <div class="col-md-6">
                    <button class="btn btn-outline-primary w-100" onclick="changeTerminalPin()">
                        <i class="fas fa-key me-2"></i> Change Terminal Pin
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary w-100" onclick="saveChanges()">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmSignOut() {
    if(confirm('Are you sure you want to sign out and end your shift?\n\nPlease ensure all transactions are completed.')) {
        // Submit logout form
        document.getElementById('logout-form').submit();
    }
}

function changeTerminalPin() {
    const newPin = prompt('Enter new 4-digit PIN:');
    if(newPin && newPin.length === 4 && !isNaN(newPin)) {
        alert('PIN changed successfully!');
    } else if(newPin) {
        alert('Invalid PIN! Must be 4 digits.');
    }
}

function saveChanges() {
    alert('Settings saved successfully!');
}
</script>

<!-- Logout Form (hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
@endsection
