<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_code',
        'product_name',
        'description',
        'category',
        'system_stock',
        'physical_stock',
        'price',
        'unit',
        'image',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function stockAudits()
    {
        return $this->hasMany(StockAudit::class);
    }

    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }

    /**
     * Generate automatic product code (e.g. PRD-2026-001)
     */
    public static function generateCode(): string
    {
        $year = date('Y');
        $prefix = "PRD-{$year}-";

        $last = self::where('product_code', 'like', "{$prefix}%")
            ->orderBy('product_code', 'desc')
            ->first();

        $next = 1;
        if ($last && preg_match('/-(\d+)$/', $last->product_code, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function getDiscrepancyAttribute()
    {
        return $this->physical_stock - $this->system_stock;
    }
}
