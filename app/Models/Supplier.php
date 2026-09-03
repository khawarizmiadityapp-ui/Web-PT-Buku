<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_code',
        'name',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'status',
        'address',
        'city',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Generate automatic supplier code (e.g. SUP-2026-001)
     */
    public static function generateCode(): string
    {
        $year = date('Y');
        $prefix = "SUP-{$year}-";

        $last = self::where('supplier_code', 'like', "{$prefix}%")
            ->orderBy('supplier_code', 'desc')
            ->first();

        $next = 1;
        if ($last && preg_match('/-(\d+)$/', $last->supplier_code, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}
