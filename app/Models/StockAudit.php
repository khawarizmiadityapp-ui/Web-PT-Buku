<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAudit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'system_stock',
        'physical_stock',
        'difference',
        'adjustment_status',
        'notes',
        'audit_date',
        'audited_by',
    ];

    protected $casts = [
        'audit_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
