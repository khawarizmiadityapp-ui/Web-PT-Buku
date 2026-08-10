<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        if ($suppliers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $supplier1 = $suppliers->first();
        $supplier2 = $suppliers->skip(1)->first() ?? $supplier1;

        // PO 1 - Approved
        $po1 = Purchase::updateOrCreate(
            ['po_number' => 'PO-2026-0001'],
            [
                'po_date' => now()->subDays(2)->toDateString(),
                'supplier_id' => $supplier1->id,
                'status' => 'Approved',
                'total_amount' => 1500000,
                'notes' => 'Pesanan rutin ATK & Kertas A4',
            ]
        );

        $prod1 = $products->first();
        $prod2 = $products->skip(1)->first() ?? $prod1;

        PurchaseItem::updateOrCreate(
            ['purchase_id' => $po1->id, 'product_id' => $prod1->id],
            [
                'quantity' => 20,
                'unit_price' => $prod1->price ?? 50000,
                'subtotal' => 20 * ($prod1->price ?? 50000),
            ]
        );

        PurchaseItem::updateOrCreate(
            ['purchase_id' => $po1->id, 'product_id' => $prod2->id],
            [
                'quantity' => 10,
                'unit_price' => $prod2->price ?? 50000,
                'subtotal' => 10 * ($prod2->price ?? 50000),
            ]
        );

        // PO 2 - Pending Approval
        $po2 = Purchase::updateOrCreate(
            ['po_number' => 'PO-2026-0002'],
            [
                'po_date' => now()->subDay()->toDateString(),
                'supplier_id' => $supplier2->id,
                'status' => 'Pending Approval',
                'total_amount' => 2400000,
                'notes' => 'Pesanan tambahan perlengkapan kantor',
            ]
        );

        $prod3 = $products->skip(2)->first() ?? $prod1;

        PurchaseItem::updateOrCreate(
            ['purchase_id' => $po2->id, 'product_id' => $prod3->id],
            [
                'quantity' => 30,
                'unit_price' => $prod3->price ?? 80000,
                'subtotal' => 30 * ($prod3->price ?? 80000),
            ]
        );

        // PO 3 - Received
        $po3 = Purchase::updateOrCreate(
            ['po_number' => 'PO-2026-0003'],
            [
                'po_date' => now()->toDateString(),
                'supplier_id' => $supplier1->id,
                'status' => 'Received',
                'total_amount' => 950000,
                'notes' => 'Pengiriman kilat alat tulis kantor',
            ]
        );

        PurchaseItem::updateOrCreate(
            ['purchase_id' => $po3->id, 'product_id' => $prod1->id],
            [
                'quantity' => 15,
                'unit_price' => $prod1->price ?? 50000,
                'subtotal' => 15 * ($prod1->price ?? 50000),
            ]
        );
    }
}
