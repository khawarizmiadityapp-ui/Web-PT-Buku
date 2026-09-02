<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'product_code' => '#SKU-BK-001',
                'product_name' => 'Buku Tulis Sinar Dunia 58 Lembar',
                'description' => 'Buku tulis bergaris kualitas tinggi 58 lembar',
                'category' => 'Buku Tulis',
                'system_stock' => 500,
                'physical_stock' => 500,
                'price' => 5500,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-NV-002',
                'product_name' => 'Novel Laskar Pelangi - Andrea Hirata',
                'description' => 'Novel fiksi inspiratif best seller Laskar Pelangi',
                'category' => 'Novel',
                'system_stock' => 450,
                'physical_stock' => 450,
                'price' => 85000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-BP-003',
                'product_name' => 'Buku Pelajaran Matematika SMA Kelas 10',
                'description' => 'Buku teks pelajaran matematika Kurikulum Merdeka',
                'category' => 'Buku Pelajaran',
                'system_stock' => 500,
                'physical_stock' => 500,
                'price' => 65000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-KM-004',
                'product_name' => 'Komik Doraemon Edisi Spesial Vol 1',
                'description' => 'Komik anak edisi bergambar berwarna Bahasa Indonesia',
                'category' => 'Komik',
                'system_stock' => 300,
                'physical_stock' => 300,
                'price' => 35000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-AT-005',
                'product_name' => 'Pulpen Kenko Gel 0.5mm Hitam',
                'description' => 'Pulpen gel tinta hitam cepat kering 0.5mm',
                'category' => 'Alat Tulis',
                'system_stock' => 450,
                'physical_stock' => 450,
                'price' => 3500,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-AT-006',
                'product_name' => 'Pensil 2B Faber-Castell Original',
                'description' => 'Pensil ujian 2B Faber Castell hitam pekat',
                'category' => 'Alat Tulis',
                'system_stock' => 400,
                'physical_stock' => 400,
                'price' => 4500,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-AT-007',
                'product_name' => 'Penghapus Joyko EB-30 Soft Eraser',
                'description' => 'Penghapus pensil putih tidak merusak kertas',
                'category' => 'Alat Tulis',
                'system_stock' => 350,
                'physical_stock' => 350,
                'price' => 2000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-AT-008',
                'product_name' => 'Penggaris Plastik Butterfly 30cm',
                'description' => 'Penggaris bening transparan ukuran 30 cm',
                'category' => 'Alat Tulis',
                'system_stock' => 250,
                'physical_stock' => 250,
                'price' => 5000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-BG-009',
                'product_name' => 'Buku Gambar A3 Kiki Premium',
                'description' => 'Buku gambar ukuran besar A3 kertas tebal',
                'category' => 'Buku Gambar',
                'system_stock' => 500,
                'physical_stock' => 500,
                'price' => 12000,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
            [
                'product_code' => '#SKU-AT-010',
                'product_name' => 'Tipe-X Correction Tape Joyko CT-522',
                'description' => 'Tip-ex pita kering pita tidak mudah putus',
                'category' => 'Alat Tulis',
                'system_stock' => 300,
                'physical_stock' => 300,
                'price' => 8500,
                'unit' => 'Pcs',
                'status' => 'Active',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['product_code' => $product['product_code']],
                $product
            );
        }
    }
}
