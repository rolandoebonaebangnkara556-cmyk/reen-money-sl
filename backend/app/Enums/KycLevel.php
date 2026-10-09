<?php

namespace App\Enums;

enum KycLevel: int
{
    case LEVEL_1 = 1;
    case LEVEL_2 = 2;
    case LEVEL_3 = 3;

    public function label(): string
    {
        return match($this) {
            self::LEVEL_1 => 'Basic',
            self::LEVEL_2 => 'Intermediate',
            self::LEVEL_3 => 'Premium',
        };
    }

    public function dailyLimit(): int
    {
        return match($this) {
            self::LEVEL_1 => 50000,
            self::LEVEL_2 => 500000,
            self::LEVEL_3 => 2000000,
        };
    }

    public function monthlyLimit(): int
    {
        return match($this) {
            self::LEVEL_1 => 500000,
            self::LEVEL_2 => 5000000,
            self::LEVEL_3 => 20000000,
        };
    }
}
