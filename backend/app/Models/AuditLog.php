<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'resource_type',
        'resource_id',
        'changes',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    public $timestamps = true;
    const UPDATED_AT = null; // Inmutable - no se actualiza

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Métodos auxiliares
    public function getActionLabel(): string
    {
        return config("audit.actions.{$this->action}", ucwords(str_replace('_', ' ', $this->action)));
    }
}
