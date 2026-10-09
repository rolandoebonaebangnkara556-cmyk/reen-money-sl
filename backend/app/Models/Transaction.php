<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasAuditTrail;

class Transaction extends Model
{
    use HasFactory, HasAuditTrail;

    protected $fillable = [
        'user_id',
        'account_id',
        'type',
        'amount',
        'fee',
        'net_amount',
        'currency',
        'status',
        'recipient_id',
        'recipient_account_id',
        'description',
        'reference_number',
        'metadata',
        'ip_address',
        'device_id',
        'user_agent',
        'fraud_score',
        'fraud_status',
        'approved_by_user_id',
        'rejected_by_user_id',
        'rejection_reason',
        'completed_at',
        'failed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'json',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    protected $attributes = [
        'currency' => 'XAF',
        'status' => 'pending',
        'fee' => 0,
        'fraud_score' => 0,
        'fraud_status' => 'clean',
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function recipientAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'recipient_account_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by_user_id');
    }

    // Métodos auxiliares
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    public function markAsFailed(string $reason): void
    {
        $this->status = 'failed';
        $this->failed_at = now();
        $this->rejection_reason = $reason;
        $this->save();
    }

    public function calculateFee(): float
    {
        $feePercentage = config("fintech.fees.{$this->type}", 0);
        $fee = $this->amount * $feePercentage;
        $minFee = config('fintech.fees.min_fee', 500);
        return max($fee, $minFee);
    }

    public function getFormattedAmount(): string
    {
        return number_format($this->amount, 2, ',', ' ');
    }

    public function getFormattedFee(): string
    {
        return number_format($this->fee, 2, ',', ' ');
    }

    public function getTransactionTypeLabel(): string
    {
        return config("fintech.transaction_types.{$this->type}.name", 'Unknown');
    }
}
