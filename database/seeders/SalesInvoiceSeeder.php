<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesInvoice;
use Carbon\Carbon;

class SalesInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'invoice_number' => 'INV/2026/0001',
                'date' => Carbon::parse('2026-10-24'),
                'customer_name' => 'Toko Buku Gramedia Nusantara',
                'total_amount' => 45230500,
                'due_date' => Carbon::parse('2026-10-31'),
                'payment_status' => 'Paid',
                'paid_amount' => 45230500,
            ],
            [
                'invoice_number' => 'INV/2026/0002',
                'date' => Carbon::parse('2026-10-21'),
                'customer_name' => 'Distributor Alat Tulis Jawa',
                'total_amount' => 12870800,
                'due_date' => Carbon::parse('2026-10-28'),
                'payment_status' => 'Unpaid',
                'paid_amount' => 0,
            ],
            [
                'invoice_number' => 'INV/2026/0003',
                'date' => Carbon::parse('2026-10-20'),
                'customer_name' => 'CV Pendidikan Mandiri',
                'total_amount' => 89320300,
                'due_date' => Carbon::parse('2026-10-27'),
                'payment_status' => 'Overdue',
                'paid_amount' => 0,
            ],
            [
                'invoice_number' => 'INV/2026/0004',
                'date' => Carbon::parse('2026-10-19'),
                'customer_name' => 'Sekolah Kartini Nusantara Jakarta',
                'total_amount' => 24100200,
                'due_date' => Carbon::parse('2026-10-26'),
                'payment_status' => 'Unpaid',
                'paid_amount' => 0,
            ],
            [
                'invoice_number' => 'INV/2026/0005',
                'date' => Carbon::parse('2026-10-18'),
                'customer_name' => 'Toko Buku Bambu Surabaya',
                'total_amount' => 15500800,
                'due_date' => Carbon::parse('2026-10-25'),
                'payment_status' => 'Paid',
                'paid_amount' => 15500800,
            ],
            [
                'invoice_number' => 'INV/2026/0006',
                'date' => Carbon::parse('2026-10-15'),
                'customer_name' => 'Universitas Bakti',
                'total_amount' => 142500000,
                'due_date' => Carbon::parse('2026-11-01'),
                'payment_status' => 'Partial',
                'paid_amount' => 70000000,
            ],
        ];

        foreach ($invoices as $invoice) {
            SalesInvoice::create($invoice);
        }
    }
}
