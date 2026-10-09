<?php

return [
    'enabled' => true,
    'retention_days' => 2555, // 7 años
    'immutable' => true,

    'actions' => [
        'user_login' => 'User Login',
        'user_logout' => 'User Logout',
        'user_failed_login' => 'Failed Login Attempt',
        'password_changed' => 'Password Changed',
        '2fa_enabled' => '2FA Enabled',
        '2fa_disabled' => '2FA Disabled',
        'account_created' => 'Account Created',
        'account_updated' => 'Account Updated',
        'account_frozen' => 'Account Frozen',
        'account_closed' => 'Account Closed',
        'transaction_created' => 'Transaction Created',
        'transaction_approved' => 'Transaction Approved',
        'transaction_rejected' => 'Transaction Rejected',
        'transaction_completed' => 'Transaction Completed',
        'transaction_failed' => 'Transaction Failed',
        'transaction_reversed' => 'Transaction Reversed',
        'kyc_submitted' => 'KYC Submitted',
        'kyc_approved' => 'KYC Approved',
        'kyc_rejected' => 'KYC Rejected',
        'kyc_level_upgraded' => 'KYC Level Upgraded',
        'aml_flag_created' => 'AML Flag Created',
        'fraud_alert_created' => 'Fraud Alert Created',
        'role_assigned' => 'Role Assigned',
        'permission_granted' => 'Permission Granted',
        'config_changed' => 'Configuration Changed',
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
