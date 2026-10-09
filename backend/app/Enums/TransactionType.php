<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case P2P_TRANSFER = 'p2p_transfer';
    case BILL_PAYMENT = 'bill_payment';
    case MERCHANT_PAYMENT = 'merchant_payment';
    case RECHARGE_MOBILE = 'recharge_mobile';

    public function label(): string
    {
        return match($this) {
            self::DEPOSIT => 'Deposit',
            self::WITHDRAWAL => 'Withdrawal',
            self::P2P_TRANSFER => 'P2P Transfer',
            self::BILL_PAYMENT => 'Bill Payment',
            self::MERCHANT_PAYMENT => 'Merchant Payment',
            self::RECHARGE_MOBILE => 'Mobile Recharge',
        };
    }

    public function requiresApproval(): bool
    {
        return in_array($this, [
            self::WITHDRAWAL,
        ]);
    }

    public function isInternal(): bool
    {
        return in_array($this, [
            self::DEPOSIT,
            self::WITHDRAWAL,
            self::P2P_TRANSFER,
        ]);
    }
}
