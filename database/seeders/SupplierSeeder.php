<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'supplier_code' => 'SUP-2023-001',
                'name' => 'CV Maju Terus Logistik',
                'company_name' => 'CV Maju Terus Logistik',
                'contact_person' => 'Budi Santoso',
                'phone' => '0812-3456-7890',
                'email' => 'budi@majuterus.com',
                'status' => 'Aktif',
                'address' => 'Jl. Industri No. 45',
                'city' => 'Jakarta',
            ],
            [
                'supplier_code' => 'SUP-2023-002',
                'name' => 'PT Sinar Mas Logistics',
                'company_name' => 'PT Sinar Mas Logistics',
                'contact_person' => 'Dewi Kartika',
                'phone' => '021-5551234',
                'email' => 'dewi@sinarmas.co.id',
                'status' => 'Aktif',
                'address' => 'Jl. Gatot Subroto Kav. 52-53',
                'city' => 'Jakarta',
            ],
            [
                'supplier_code' => 'SUP-2023-003',
                'name' => 'Gudang Plastik Material',
                'company_name' => 'Gudang Plastik Material',
                'contact_person' => 'Hendra Wijaya',
                'phone' => '031-555-8888',
                'email' => 'info@gp-plastics.id',
                'status' => 'Non-aktif',
                'address' => 'Jl. Raya Surabaya No. 100',
                'city' => 'Surabaya',
            ],
            [
                'supplier_code' => 'SUP-2023-004',
                'name' => 'PT Elektronik Nusantara',
                'company_name' => 'PT Elektronik Nusantara',
                'contact_person' => 'Agus Priatna',
                'phone' => '0811-2233-4455',
                'email' => 'agus@elnusa.co.id',
                'status' => 'Aktif',
                'address' => 'Kawasan Industri MM2100',
                'city' => 'Bekasi',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
