@extends('layouts.app')

@section('title', 'Detail Verifikasi Barang Masuk - PT Nusantara ERP')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Penerimaan: {{ $incomingGood->receipt_number }}</h1>
            <p class="text-gray-500 mt-1">Tanggal: {{ \Carbon\Carbon::parse($incomingGood->receive_date)->format('d F Y') }} | Supplier: {{ $incomingGood->supplier->nama }}</p>
        </div>
        <div>
            <a href="{{ route('warehouse.verifikasi.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form id="verifikasiForm">
                @csrf
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">NO</th>
                                <th width="35%">NAMA BARANG</th>
                                <th width="20%">KODE BARANG</th>
                                <th width="20%" class="text-center">QTY (SISTEM)</th>
                                <th width="20%" class="text-center">QTY (FISIK AKTUAL)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incomingGood->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $item->product->product_name }}</td>
                                <td>{{ $item->product->product_code }}</td>
                                <td class="text-center bg-light">
                                    <strong>{{ $item->quantity }}</strong>
                                </td>
                                <td>
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    <input type="number" class="form-control text-center physical-qty" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="0" required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i> 
                    Jika ada perbedaan antara QTY Sistem dan QTY Fisik Aktual, harap sesuaikan angkanya pada kolom <strong>QTY (FISIK AKTUAL)</strong> dan pilih <strong>Revisi</strong>. Jika semua sesuai, pilih <strong>Verifikasi (Lengkap)</strong>.
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-warning text-white" onclick="submitVerifikasi('revise')">
                        <i class="fas fa-edit me-1"></i> Revisi (Ada Perbedaan)
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitVerifikasi('verify')">
                        <i class="fas fa-check-circle me-1"></i> Verifikasi (Lengkap & Sesuai)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitVerifikasi(action) {
    const form = document.getElementById('verifikasiForm');
    const formData = new FormData(form);
    
    let isMismatch = false;
    const items = [];
    let idx = 0;
    
    // Check for mismatch simply by relying on user interaction or comparing if we wanted to
    // Here we just collect what's in the inputs
    while (formData.has(`items[${idx}][id]`)) {
        items.push({
            id: formData.get(`items[${idx}][id]`),
            quantity: formData.get(`items[${idx}][quantity]`)
        });
        idx++;
    }

    if (action === 'verify' && !confirm('Anda yakin stok fisik sudah lengkap dan sesuai? Stok sistem akan langsung ditambahkan.')) {
        return;
    }

    if (action === 'revise' && !confirm('Anda yakin ingin menyimpan perubahan (revisi) ini?')) {
        return;
    }

    const payload = {
        action: action,
        items: items
    };

    fetch('{{ route("warehouse.verifikasi.process", $incomingGood->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            window.location.href = '{{ route("warehouse.verifikasi.index") }}';
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses data.');
    });
}
</script>
@endsection
