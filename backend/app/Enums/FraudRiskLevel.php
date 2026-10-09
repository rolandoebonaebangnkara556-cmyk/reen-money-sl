<?php

namespace App\Enums;

enum FraudRiskLevel: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
        };
    }

    public function scoreRange(): array
    {
        return match($this) {
            self::LOW => [0, 25],
            self::MEDIUM => [26, 50],
            self::HIGH => [51, 75],
            self::CRITICAL => [76, 100],
        };
    }

    public static function fromScore(int $score): self
    {
        return match(true) {
            $score <= 25 => self::LOW,
            $score <= 50 => self::MEDIUM,
            $score <= 75 => self::HIGH,
            default => self::CRITICAL,
        };
    }
}
