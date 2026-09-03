<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'date',
        'customer_id',
        'customer_name',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'due_date',
        'payment_status',
        'payment_method',
        'paid_amount',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->payment_status !== 'Paid' && $this->due_date->isPast();
    }

    /**
     * Generate automatic invoice number (e.g. INV/2026/0001)
     */
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = "INV/{$year}/";

        $last = self::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        $next = 1;
        if ($last && preg_match('/\/(\d+)$/', $last->invoice_number, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
