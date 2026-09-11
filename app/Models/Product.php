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

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'Active' && $this->system_stock > 0;
    }

    public function getProductTypeAttribute(): string
    {
        $cat = strtolower($this->category ?? '');
        if (str_contains($cat, 'buku') || str_contains($cat, 'novel') || str_contains($cat, 'komik')) {
            return 'buku';
        }
        return 'alat_tulis';
    }

    public function getStockBadgeAttribute(): array
    {
        if ($this->system_stock <= 0 || $this->status !== 'Active') {
            return [
                'text' => 'Stok Habis',
                'class' => 'bg-rose-50 text-rose-700 border border-rose-200',
                'dot' => 'bg-rose-500',
            ];
        }

        if ($this->system_stock <= 10) {
            return [
                'text' => 'Tersisa ' . $this->system_stock . ' ' . $this->unit,
                'class' => 'bg-amber-50 text-amber-700 border border-amber-200',
                'dot' => 'bg-amber-500',
            ];
        }

        return [
            'text' => 'Tersedia (' . number_format($this->system_stock) . ' ' . $this->unit . ')',
            'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'dot' => 'bg-emerald-500',
        ];
    }

    public function getCategoryColorAttribute(): array
    {
        $cat = $this->category ?? 'General';
        return match ($cat) {
            'Novel' => [
                'badge' => 'bg-purple-100 text-purple-700 border-purple-200',
                'gradient' => 'from-indigo-900 via-purple-900 to-slate-900',
                'accent' => '#C084FC',
                'icon' => 'book-open',
            ],
            'Komik' => [
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
                'gradient' => 'from-amber-800 via-orange-900 to-slate-900',
                'accent' => '#FBBF24',
                'icon' => 'sparkles',
            ],
            'Buku Pelajaran' => [
                'badge' => 'bg-blue-100 text-blue-700 border-blue-200',
                'gradient' => 'from-blue-900 via-indigo-900 to-slate-900',
                'accent' => '#60A5FA',
                'icon' => 'academic-cap',
            ],
            'Buku Tulis' => [
                'badge' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                'gradient' => 'from-cyan-950 via-teal-900 to-slate-900',
                'accent' => '#2DD4BF',
                'icon' => 'document-text',
            ],
            'Buku Gambar' => [
                'badge' => 'bg-rose-100 text-rose-700 border-rose-200',
                'gradient' => 'from-pink-950 via-rose-900 to-slate-900',
                'accent' => '#FB7185',
                'icon' => 'paint-brush',
            ],
            'Alat Tulis' => [
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'gradient' => 'from-slate-900 via-emerald-950 to-slate-900',
                'accent' => '#34D399',
                'icon' => 'pencil',
            ],
            default => [
                'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                'gradient' => 'from-slate-900 via-indigo-950 to-slate-900',
                'accent' => '#818CF8',
                'icon' => 'cube',
            ],
        };
    }

    public function getDisplayImageAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return '';
    }
}
