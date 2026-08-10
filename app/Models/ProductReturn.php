<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'return_id',
        'date',
        'product_id',
        'sales_invoice_id',
        'entity',
        'type',
        'reason',
        'items',
        'unit_price',
        'total_amount',
        'refund_method',
        'refund_amount',
        'restocking_fee',
        'status',
        'notes',
        'proof_image',
    ];

    protected $casts = [
        'date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'restocking_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function getNetRefundAttribute()
    {
        return $this->total_amount - $this->restocking_fee;
    }
}
