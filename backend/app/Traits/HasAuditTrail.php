<?php

namespace App\Traits;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait HasAuditTrail
{
    protected static function bootHasAuditTrail(): void
    {
        static::created(function ($model) {
            self::writeAudit($model, AuditAction::USER_CREATED, 'created');
        });

        static::updated(function ($model) {
            self::writeAudit($model, AuditAction::USER_UPDATED, 'updated');
        });

        static::deleted(function ($model) {
            self::writeAudit($model, AuditAction::USER_DELETED, 'deleted');
        });
    }

    protected static function writeAudit($model, AuditAction $action, string $event): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action->value,
            'resource_type' => class_basename($model),
            'resource_id' => $model->id,
            'changes' => [
                'event' => $event,
                'model' => $model->toArray(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
