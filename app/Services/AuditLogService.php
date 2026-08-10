<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public static function log(
        string $actionType,
        string $description,
        ?string $subjectTable = null,
        ?int $subjectId = null,
        ?array $metadata = null,
        ?Request $request = null
    ): AuditLog {
        $request = $request ?? request();

        return AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => strtoupper($actionType),
            'description' => $description,
            'subject_table' => $subjectTable,
            'subject_id' => $subjectId,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
