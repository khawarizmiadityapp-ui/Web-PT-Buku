<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySettingSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            StockOutSeeder::class,
            SalesInvoiceSeeder::class,
            ProductReturnSeeder::class,
            AuditLogSeeder::class,
            MenuSeeder::class,
            PurchaseSeeder::class,
            IncomingGoodSeeder::class,
        ]);
    }
}
