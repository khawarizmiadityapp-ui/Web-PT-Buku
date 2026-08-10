<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'tax_id',
        'address',
        'logo_path',
        'app_name',
        'timezone',
        'email_notifications',
        'stock_alerts',
    ];

    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'stock_alerts' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
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
