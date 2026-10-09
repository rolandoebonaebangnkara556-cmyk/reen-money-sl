<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'phone_number',
        'email',
        'password',
        'first_name',
        'last_name',
        'date_of_birth',
        'id_document_type',
        'id_document_number',
        'nationality',
        'address',
        'city',
        'postal_code',
        'occupation',
        'source_of_income',
        'pin_hash',
        'two_factor_enabled',
        'two_factor_method',
        'status',
        'aml_status',
        'politically_exposed',
        'created_at_agency_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'pin_hash',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'politically_exposed' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relaciones
    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function sentTransfers(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function receivedTransfers(): HasMany
    {
        return $this->hasMany(Transaction::class, 'recipient_id');
    }

    public function kycProfile(): HasOne
    {
        return $this->hasOne(KycProfile::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function fraudAlerts(): HasMany
    {
        return $this->hasMany(FraudAlert::class);
    }

    public function agencyRelations(): BelongsToMany
    {
        return $this->belongsToMany(Agency::class, 'agency_users')
            ->withPivot('role', 'status')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function createdByAgency()
    {
        return $this->belongsTo(Agency::class, 'created_at_agency_id');
    }

    // Métodos auxiliares
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists() || $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function isAgent(): bool
    {
        return $this->hasRole('agent') || $this->hasRole('agency_manager');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function canTransact(): bool
    {
        return $this->status === 'active' 
            && $this->aml_status !== 'blocked'
            && optional($this->kycProfile)->isApproved();
    }

    public function getDailyLimit(): int
    {
        $kycLevel = optional($this->kycProfile)->level ?? 1;
        return config("fintech.kyc.level_{$kycLevel}.daily_limit");
    }

    public function getMonthlyLimit(): int
    {
        $kycLevel = optional($this->kycProfile)->level ?? 1;
        return config("fintech.kyc.level_{$kycLevel}.monthly_limit");
    }

    public function getTransactionLimit(): int
    {
        $kycLevel = optional($this->kycProfile)->level ?? 1;
        return config("fintech.kyc.level_{$kycLevel}.per_transaction_limit");
    }

    public function getTodaySpent(): int
    {
        return $this->transactions()
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('net_amount');
    }

    public function getMonthSpent(): int
    {
        return $this->transactions()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('net_amount');
    }
}
