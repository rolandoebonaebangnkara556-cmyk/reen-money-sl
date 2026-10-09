<?php

return [
    'currency' => env('FINTECH_CURRENCY', 'XAF'),
    'country' => env('FINTECH_COUNTRY', 'CM'),
    'timezone' => env('FINTECH_TIMEZONE', 'Africa/Douala'),

    'kyc' => [
        'level_1' => [
            'name' => 'Basic Verification',
            'daily_limit' => 50000,
            'monthly_limit' => 500000,
            'per_transaction_limit' => 50000,
            'requirements' => ['phone_verified', 'email_verified'],
        ],
        'level_2' => [
            'name' => 'Intermediate Verification',
            'daily_limit' => 500000,
            'monthly_limit' => 5000000,
            'per_transaction_limit' => 500000,
            'requirements' => ['document_verified', 'address_verified'],
        ],
        'level_3' => [
            'name' => 'Premium Verification',
            'daily_limit' => 2000000,
            'monthly_limit' => 20000000,
            'per_transaction_limit' => 2000000,
            'requirements' => ['video_verified', 'source_of_funds_verified'],
        ],
    ],

    'roles' => [
        'customer' => ['level' => 1, 'label' => 'Customer'],
        'agent' => ['level' => 2, 'label' => 'Agency Agent'],
        'agency_manager' => ['level' => 3, 'label' => 'Agency Manager'],
        'compliance_officer' => ['level' => 4, 'label' => 'Compliance Officer'],
        'admin' => ['level' => 5, 'label' => 'Administrator'],
    ],

    'transaction_types' => [
        'deposit' => ['name' => 'Deposit', 'internal' => true],
        'withdrawal' => ['name' => 'Withdrawal', 'internal' => true],
        'p2p_transfer' => ['name' => 'P2P Transfer', 'internal' => true],
        'bill_payment' => ['name' => 'Bill Payment', 'internal' => false],
        'merchant_payment' => ['name' => 'Merchant Payment', 'internal' => false],
        'recharge_mobile' => ['name' => 'Mobile Recharge', 'internal' => false],
    ],

    'fees' => [
        'p2p_transfer' => 0.015,          // 1.5%
        'bill_payment' => 0.02,            // 2%
        'merchant_payment' => 0.025,      // 2.5%
        'recharge_mobile' => 0.02,         // 2%
        'withdrawal' => 0.03,              // 3%
        'min_fee' => 500,                  // Mínima comisión en XAF
    ],

    'two_factor' => [
        'required' => true,
        'algorithm' => 'totp',
        'digits' => 6,
        'window' => 1,
        'otp_lifetime' => 300, // 5 minutos
    ],

    'audit' => [
        'retention_days' => 2555, // 7 años
        'immutable' => true,
    ],

    'fraud' => [
        'enabled' => true,
        'risk_threshold' => 50,
        'velocity_limit' => 5, // máximo 5 transacciones en 10 minutos
    ],

    'rate_limit' => [
        'auth_attempts' => '5,15',   // 5 intentos en 15 minutos
        'api_calls' => '100,60',     // 100 llamadas por minuto
    ],
];
