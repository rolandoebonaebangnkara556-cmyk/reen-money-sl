<?php

namespace App\Enums;

enum KycStatus: string
{
    case PENDING = 'pending';
    case REVIEWING = 'reviewing';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::REVIEWING => 'Under Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
        };
    }

    public function isActive(): bool
    {
        return $this === self::APPROVED;
    }
}
