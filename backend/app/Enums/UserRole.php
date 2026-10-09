<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case AGENT = 'agent';
    case AGENCY_MANAGER = 'agency_manager';
    case COMPLIANCE_OFFICER = 'compliance_officer';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::CUSTOMER => 'Customer',
            self::AGENT => 'Agency Agent',
            self::AGENCY_MANAGER => 'Agency Manager',
            self::COMPLIANCE_OFFICER => 'Compliance Officer',
            self::ADMIN => 'Administrator',
        };
    }

    public function level(): int
    {
        return match($this) {
            self::CUSTOMER => 1,
            self::AGENT => 2,
            self::AGENCY_MANAGER => 3,
            self::COMPLIANCE_OFFICER => 4,
            self::ADMIN => 5,
        };
    }

    public function isInternal(): bool
    {
        return $this !== self::CUSTOMER;
    }
}
