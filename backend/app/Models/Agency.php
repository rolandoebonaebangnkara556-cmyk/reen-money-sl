<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'city',
        'address',
        'phone',
        'email',
        'manager_id',
        'latitude',
        'longitude',
        'status',
        'commission_rate',
        'daily_limit',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'commission_rate' => 'decimal:3',
        'daily_limit' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'active',
        'commission_rate' => 0.02,
    ];

    // Relaciones
    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'agency_users')
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'created_by_agency_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Métodos auxiliares
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getStaffCount(): int
    {
        return $this->staff()->count();
    }

    public function getTodayTransactionsCount(): int
    {
        return $this->accounts()
            ->whereHas('transactions', function ($query) {
                $query->whereDate('created_at', today());
            })
            ->count();
    }
}
