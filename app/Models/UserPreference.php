<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_notifications',
        'stock_alerts',
        'sales_reports',
    ];

    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'stock_alerts' => 'boolean',
            'sales_reports' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function forUser(User $user): self
    {
        return static::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => true,
                'stock_alerts' => true,
                'sales_reports' => false,
            ]
        );
    }
}
