<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'status',
        'phone_verified',
        'email_verified',
        'document_verified',
        'address_verified',
        'biometric_verified',
        'video_verified',
        'source_of_funds_verified',
        'documents',
        'verified_by_user_id',
        'rejection_reason',
        'verified_at',
        'expires_at',
    ];

    protected $casts = [
        'phone_verified' => 'boolean',
        'email_verified' => 'boolean',
        'document_verified' => 'boolean',
        'address_verified' => 'boolean',
        'biometric_verified' => 'boolean',
        'video_verified' => 'boolean',
        'source_of_funds_verified' => 'boolean',
        'documents' => 'json',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $attributes = [
        'level' => 1,
        'status' => 'pending',
        'phone_verified' => false,
        'email_verified' => false,
        'document_verified' => false,
        'address_verified' => false,
        'biometric_verified' => false,
        'video_verified' => false,
        'source_of_funds_verified' => false,
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    // Métodos auxiliares
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canUpgradeToLevel2(): bool
    {
        return $this->level === 1 
            && $this->phone_verified 
            && $this->email_verified;
    }

    public function canUpgradeToLevel3(): bool
    {
        return $this->level === 2 
            && $this->document_verified 
            && $this->address_verified 
            && $this->biometric_verified;
    }

    public function getLevelLabel(): string
    {
        return config("fintech.kyc.level_{$this->level}.name", 'Unknown');
    }
}
