<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'description',
        'subject_table',
        'subject_id',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionBadgeClassAttribute(): string
    {
        return match (strtoupper($this->action_type)) {
            'CREATE' => 'bg-green-100 text-green-800',
            'UPDATE' => 'bg-yellow-100 text-yellow-800',
            'DELETE' => 'bg-red-100 text-red-800',
            'LOGIN' => 'bg-blue-100 text-blue-800',
            'LOGOUT' => 'bg-gray-100 text-gray-800',
            'SYSTEM' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
