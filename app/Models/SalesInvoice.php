<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'order_code',
        'date',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'due_date',
        'payment_status',
        'payment_method',
        'paid_amount',
        'notes',
        'shipping_address',
        'order_status',
        'status_history',
        'status_updated_at',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'status_history' => 'array',
        'status_updated_at' => 'datetime',
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
        return $this->payment_status !== 'Paid' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Map backend order_status to customer-facing label and styling
     */
    public static function statusMap(): array
    {
        return [
            'pending' => [
                'title' => 'Pesanan Dibuat',
                'description' => 'Pesanan Anda telah diterima sistem dan menunggu konfirmasi tim PT Buku Nusantara.',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                'color' => '#F59E0B',
                'step' => 1,
            ],
            'confirmed' => [
                'title' => 'Pesanan Dikonfirmasi',
                'description' => 'Pesanan telah diverifikasi oleh tim administrasi dan siap diproses ke gudang.',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
                'color' => '#3B82F6',
                'step' => 2,
            ],
            'processing' => [
                'title' => 'Sedang Diproses',
                'description' => 'Gudang sedang melakukan picking, pemeriksaan kualitas, dan pengepakan barang.',
                'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                'color' => '#6366F1',
                'step' => 3,
            ],
            'ready' => [
                'title' => 'Pesanan Siap',
                'description' => 'Barang telah dipacking rapi dan siap dikirim / diserahkan kepada kurir pengiriman.',
                'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
                'color' => '#8B5CF6',
                'step' => 4,
            ],
            'completed' => [
                'title' => 'Selesai',
                'description' => 'Pesanan telah berhasil diterima customer. Terima kasih telah berbelanja di PT Buku Nusantara.',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'color' => '#10B981',
                'step' => 5,
            ],
            'cancelled' => [
                'title' => 'Dibatalkan',
                'description' => 'Pesanan ini telah dibatalkan.',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
                'color' => '#EF4444',
                'step' => 0,
            ],
        ];
    }

    public function getOrderStatusLabelAttribute(): string
    {
        $status = $this->order_status ?: 'pending';
        $map = self::statusMap();
        return $map[$status]['title'] ?? ucfirst($status);
    }

    public function getOrderStatusBadgeAttribute(): string
    {
        $status = $this->order_status ?: 'pending';
        $map = self::statusMap();
        return $map[$status]['badge'] ?? 'bg-gray-100 text-gray-800 border-gray-300';
    }

    /**
     * Record a new status change into status_history JSON
     */
    public function recordStatusChange(string $newStatus, ?string $note = null): void
    {
        $map = self::statusMap();
        $title = $map[$newStatus]['title'] ?? ucfirst($newStatus);
        $description = $note ?: ($map[$newStatus]['description'] ?? '');

        $history = is_array($this->status_history) ? $this->status_history : [];
        $history[] = [
            'status' => $newStatus,
            'title' => $title,
            'description' => $description,
            'timestamp' => now()->toDateTimeString(),
            'formatted_time' => now()->translatedFormat('d F Y, H:i'),
        ];

        $this->order_status = $newStatus;
        $this->status_history = $history;
        $this->status_updated_at = now();
        $this->save();
    }

    /**
     * Generate automatic order code (e.g. ORD-2026-00001)
     */
    public static function generateOrderCode(): string
    {
        $year = date('Y');
        $prefix = "ORD-{$year}-";

        $last = self::where('order_code', 'like', "{$prefix}%")
            ->orderBy('order_code', 'desc')
            ->first();

        $next = 1;
        if ($last && preg_match('/-(\d+)$/', $last->order_code, $matches)) {
            $next = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
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
