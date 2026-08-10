<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReturn;
use Carbon\Carbon;

class ProductReturnSeeder extends Seeder
{
    public function run(): void
    {
        $returns = [
            [
                'return_id' => 'RET-2026-001',
                'date' => Carbon::parse('2026-10-24'),
                'product_id' => 1, // Ergo Mouse
                'entity' => 'PT Gramedia Asri Nusantara',
                'type' => 'SALES',
                'reason' => 'Damaged binding on arrival',
                'items' => 45,
                'status' => 'Pending',
            ],
            [
                'return_id' => 'RET-2026-002',
                'date' => Carbon::parse('2026-10-23'),
                'product_id' => 4, // Buku Tulis
                'entity' => 'Sinar Prisma Inc',
                'type' => 'PURCHASE',
                'reason' => 'Incorrect SKU delivered',
                'items' => 120,
                'status' => 'Approved',
            ],
            [
                'return_id' => 'RET-2026-003',
                'date' => Carbon::parse('2026-10-22'),
                'product_id' => 5, // Kertas HVS
                'entity' => 'Toko Buku Barat',
                'type' => 'SALES',
                'reason' => 'Overstock clearance return',
                'items' => 300,
                'status' => 'Rejected',
            ],
            [
                'return_id' => 'RET-2026-004',
                'date' => Carbon::parse('2026-10-22'),
                'product_id' => 3, // Keyboard
                'entity' => 'Astra Stationery',
                'type' => 'SALES',
                'reason' => 'Defective paper quality',
                'items' => 12,
                'status' => 'Approved',
            ],
            [
                'return_id' => 'RET-2026-005',
                'date' => Carbon::parse('2026-10-21'),
                'product_id' => 2, // Box
                'entity' => 'Indo Paper Co.',
                'type' => 'PURCHASE',
                'reason' => 'Bulk order pre-set damage',
                'items' => 650,
                'status' => 'Pending',
            ],
        ];

        foreach ($returns as $return) {
            ProductReturn::create($return);
        }
    }
}
