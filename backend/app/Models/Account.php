<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'iban',
        'balance',
        'available_balance',
        'blocked_balance',
        'currency',
        'status',
        'created_by_agency_id',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'blocked_balance' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected $attributes = [
        'currency' => 'XAF',
        'status' => 'active',
        'balance' => 0,
        'available_balance' => 0,
        'blocked_balance' => 0,
    ];

    // Relaciones
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'created_by_agency_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Métodos auxiliares
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canTransact(): bool
    {
        return $this->isActive() && $this->available_balance > 0;
    }

    public function credit(float $amount, string $description = null): void
    {
        $this->increment('balance', $amount);
        $this->increment('available_balance', $amount);
    }

    public function debit(float $amount, string $description = null): bool
    {
        if ($this->available_balance < $amount) {
            return false;
        }

        $this->decrement('available_balance', $amount);
        $this->decrement('balance', $amount);
        return true;
    }

    public function blockFunds(float $amount): void
    {
        $this->decrement('available_balance', $amount);
        $this->increment('blocked_balance', $amount);
    }

    public function releaseBlockedFunds(float $amount): void
    {
        $this->increment('available_balance', $amount);
        $this->decrement('blocked_balance', $amount);
    }

    public function getBalanceFormatted(): string
    {
        return number_format($this->balance, 2, ',', ' ');
    }
}
