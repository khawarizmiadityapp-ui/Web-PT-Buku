<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::firstOrCreate([], [
            'company_name' => 'PT Distribusi Buku dan Alat Tulis Nusantara',
            'tax_id' => '01.234.567.8-910.111',
            'address' => 'Jl. Industri Raya No. 45, Kawasan Industri Pulogadung, Jakarta Timur 13930',
            'app_name' => 'PT Nusantara ERP',
            'timezone' => 'Asia/Jakarta',
            'email_notifications' => true,
            'stock_alerts' => true,
        ]);
    }
}
