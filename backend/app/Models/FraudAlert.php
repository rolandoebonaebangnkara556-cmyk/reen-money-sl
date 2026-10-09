<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'alert_type',
        'risk_score',
        'description',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'open',
        'risk_score' => 0,
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    // Métodos auxiliares
    public function getRiskLevelLabel(): string
    {
        return match(true) {
            $this->risk_score <= 25 => 'Low',
            $this->risk_score <= 50 => 'Medium',
            $this->risk_score <= 75 => 'High',
            default => 'Critical',
        };
    }
}
