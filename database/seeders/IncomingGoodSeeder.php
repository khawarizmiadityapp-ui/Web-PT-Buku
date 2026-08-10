<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomingGood;
use App\Models\IncomingGoodItem;
use App\Models\Supplier;
use App\Models\Product;

class IncomingGoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supplier1 = Supplier::first();
        $supplier2 = Supplier::skip(1)->first() ?? $supplier1;
        $products = Product::all();

        if (!$supplier1 || $products->isEmpty()) {
            return;
        }

        $prod1 = $products->first();
        $prod2 = $products->skip(1)->first() ?? $prod1;

        // Pending Goods Receipt 1
        $ig1 = IncomingGood::updateOrCreate(
            ['receipt_number' => 'GR-' . date('Ymd') . '-001'],
            [
                'receive_date' => now()->toDateString(),
                'supplier_id' => $supplier1->id,
                'status' => 'Pending',
                'notes' => 'Pengiriman barang dari PO-2026-0001, perlu verifikasi fisik',
            ]
        );

        IncomingGoodItem::updateOrCreate(
            ['incoming_good_id' => $ig1->id, 'product_id' => $prod1->id],
            [
                'quantity' => 20,
                'price' => $prod1->price ?? 45000,
            ]
        );

        IncomingGoodItem::updateOrCreate(
            ['incoming_good_id' => $ig1->id, 'product_id' => $prod2->id],
            [
                'quantity' => 10,
                'price' => $prod2->price ?? 65000,
            ]
        );

        // Pending Goods Receipt 2
        $ig2 = IncomingGood::updateOrCreate(
            ['receipt_number' => 'GR-' . date('Ymd') . '-002'],
            [
                'receive_date' => now()->subDay()->toDateString(),
                'supplier_id' => $supplier2->id,
                'status' => 'Pending',
                'notes' => 'Penerimaan perlengkapan ATK baru dari vendor',
            ]
        );

        $prod3 = $products->skip(2)->first() ?? $prod1;

        IncomingGoodItem::updateOrCreate(
            ['incoming_good_id' => $ig2->id, 'product_id' => $prod3->id],
            [
                'quantity' => 30,
                'price' => $prod3->price ?? 80000,
            ]
        );
    }
}
