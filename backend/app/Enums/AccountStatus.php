<?php

namespace App\Enums;

enum AccountStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case FROZEN = 'frozen';
    case CLOSED = 'closed';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::FROZEN => 'Frozen',
            self::CLOSED => 'Closed',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function canTransact(): bool
    {
        return $this === self::ACTIVE;
    }
}
