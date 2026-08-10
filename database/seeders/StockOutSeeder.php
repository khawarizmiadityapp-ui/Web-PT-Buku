<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockOut;
use Carbon\Carbon;

class StockOutSeeder extends Seeder
{
    public function run(): void
    {
        $stockOuts = [
            [
                'transaction_id' => 'TRX-OUT-2024-001',
                'date' => Carbon::parse('2024-10-24'),
                'customer_name' => 'Toko Buku Atasa',
                'total_items' => 1235,
                'recipient_name' => 'Budi Santoso',
                'status' => 'Completed',
            ],
            [
                'transaction_id' => 'TRX-OUT-2024-002',
                'date' => Carbon::parse('2024-10-24'),
                'customer_name' => 'CV Maju Toko 87',
                'total_items' => 840,
                'recipient_name' => 'Siti Aminah',
                'status' => 'In-Progress',
            ],
            [
                'transaction_id' => 'TRX-OUT-2024-003',
                'date' => Carbon::parse('2024-10-23'),
                'customer_name' => 'Gramedia Depok',
                'total_items' => 1503,
                'recipient_name' => 'Agus Wijaya',
                'status' => 'Completed',
            ],
            [
                'transaction_id' => 'TRX-OUT-2024-004',
                'date' => Carbon::parse('2024-10-23'),
                'customer_name' => 'Toko JKR Sejahtera',
                'total_items' => 120,
                'recipient_name' => 'Rini Yanti',
                'status' => 'Canceled',
            ],
            [
                'transaction_id' => 'TRX-OUT-2024-005',
                'date' => Carbon::parse('2024-10-22'),
                'customer_name' => 'Universitas Indonesia Coop',
                'total_items' => 5350,
                'recipient_name' => 'Dr Handoko',
                'status' => 'Completed',
            ],
        ];

        foreach ($stockOuts as $stockOut) {
            StockOut::create($stockOut);
        }
    }
}
