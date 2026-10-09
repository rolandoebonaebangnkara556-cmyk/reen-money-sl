<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Registrar acción en auditoría
     */
    public function log(
        $user,
        string $action,
        string $resourceType,
        ?int $resourceId = null,
        ?array $changes = null,
        ?string $description = null
    ): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user->id ?? null,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }
}
