<?php

namespace App\Enums;

enum AmlStatus: string
{
    case CLEAR = 'clear';
    case PENDING = 'pending';
    case FLAGGED = 'flagged';
    case BLOCKED = 'blocked';

    public function label(): string
    {
        return match($this) {
            self::CLEAR => 'Clear',
            self::PENDING => 'Pending',
            self::FLAGGED => 'Flagged',
            self::BLOCKED => 'Blocked',
        };
    }

    public function canTransact(): bool
    {
        return in_array($this, [self::CLEAR, self::PENDING]);
    }
}
