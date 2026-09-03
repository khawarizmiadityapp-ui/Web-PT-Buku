<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOut extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'date',
        'customer_name',
        'total_items',
        'recipient_name',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Generate automatic transaction ID (e.g. TRX-OUT-2026-001)
     */
    public static function generateTransactionId(): string
    {
        $year = date('Y');
        $prefix = "TRX-OUT-{$year}-";

        $last = self::where('transaction_id', 'like', "{$prefix}%")
            ->orderBy('transaction_id', 'desc')
            ->first();

        $next = 1;
        if ($last && preg_match('/-(\d+)$/', $last->transaction_id, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
