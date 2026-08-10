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

    public function getDiscrepancyAttribute()
    {
        return $this->physical_stock - $this->system_stock;
    }
}
