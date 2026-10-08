<?php

return [
    'enabled' => true,
    'retention_days' => env('AUDIT_LOG_RETENTION_DAYS', 2555), // 7 years
    'immutable' => env('AUDIT_LOG_IMMUTABLE', true),

    'log_actions' => [
        'authentication' => [
            'user_login',
            'user_logout',
            'user_failed_login',
            'password_changed',
            '2fa_enabled',
            '2fa_disabled',
        ],
        'users' => [
            'user_created',
            'user_updated',
            'user_deleted',
            'role_assigned',
            'role_removed',
            'permission_assigned',
        ],
        'transactions' => [
            'transaction_created',
            'transaction_approved',
            'transaction_rejected',
            'transaction_completed',
            'transaction_failed',
            'transaction_reversed',
        ],
        'kyc' => [
            'kyc_submitted',
            'kyc_approved',
            'kyc_rejected',
            'kyc_level_upgraded',
        ],
        'compliance' => [
            'aml_flag_created',
            'aml_flag_reviewed',
            'fraud_alert_created',
        ],
        'settings' => [
            'config_changed',
            'limit_changed',
            'department_created',
            'area_created',
        ],
    ],

    'sensitive_fields' => [
        'password',
        'password_hash',
        'pin',
        'pin_hash',
        'credit_card',
        'cvv',
        'ssn',
        'id_document_number',
        'bank_account',
    ],
];
