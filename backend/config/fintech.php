<?php

return [
    'currency' => env('FINTECH_CURRENCY', 'XAF'),
    'country' => env('FINTECH_COUNTRY', 'CM'),
    'timezone' => env('FINTECH_TIMEZONE', 'Africa/Douala'),

    'kyc' => [
        'level_1' => [
            'name' => 'Basic Verification',
            'limit' => env('KYC_LEVEL_1_LIMIT', 50000),
            'requirements' => [
                'phone_verified',
                'email_verified',
            ],
        ],
        'level_2' => [
            'name' => 'Intermediate Verification',
            'limit' => env('KYC_LEVEL_2_LIMIT', 500000),
            'requirements' => [
                'document_verified',
                'address_verified',
                'biometric_verified',
            ],
        ],
        'level_3' => [
            'name' => 'Premium Verification',
            'limit' => env('KYC_LEVEL_3_LIMIT', 2000000),
            'requirements' => [
                'video_verified',
                'source_of_funds_verified',
                'business_verified',
            ],
        ],
    ],

    'transaction_limits' => [
        'L1' => env('TRANSACTION_LIMIT_L1', 999999999), // Admin
        'L2' => env('TRANSACTION_LIMIT_L2', 500000),    // Director
        'L3' => env('TRANSACTION_LIMIT_L3', 100000),    // Responsable
        'L4' => env('TRANSACTION_LIMIT_L4', 50000),     // Adjunto Senior
        'L5' => env('TRANSACTION_LIMIT_L5', 10000),     // Adjunto Junior
    ],

    'fees' => [
        'p2p_transfer' => 0.015,           // 1.5%
        'bill_payment' => 0.02,             // 2%
        'merchant_payment' => 0.025,       // 2.5%
        'recharge_mobile' => 0.02,          // 2%
        'card_topup' => 0.015,              // 1.5%
        'withdrawal' => 0.03,               // 3%
    ],

    'min_fee' => 500,  // Minimum fee in base currency

    'two_factor' => [
        'required' => env('2FA_REQUIRED', true),
        'algorithm' => env('2FA_ALGORITHM', 'totp'),
        'digits' => 6,
        'window' => 1,
    ],

    'audit' => [
        'retention_days' => env('AUDIT_LOG_RETENTION_DAYS', 2555), // 7 years
        'immutable' => env('AUDIT_LOG_IMMUTABLE', true),
    ],

    'fraud' => [
        'enabled' => env('FRAUD_CHECK_ENABLED', true),
        'risk_threshold' => env('FRAUD_RISK_THRESHOLD', 50),
    ],

    'rate_limit' => [
        'auth_attempts' => '5,15', // 5 intentos en 15 minutos
        'api_calls' => '100,60',   // 100 calls por minuto
    ],
];
