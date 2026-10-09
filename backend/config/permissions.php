<?php

return [
    'roles' => [
        'customer' => [
            'level' => 1,
            'label' => 'Customer',
            'permissions' => [
                'auth.login',
                'auth.register',
                'auth.logout',
                'auth.2fa',
                'account.view_own',
                'transactions.create_own',
                'transactions.view_own',
                'kyc.view_own',
                'kyc.submit',
            ],
        ],
        'agent' => [
            'level' => 2,
            'label' => 'Agency Agent',
            'permissions' => [
                'account.create',
                'account.view',
                'transactions.create',
                'transactions.view',
                'kyc.view',
                'kyc.submit',
                'agency.view_own',
                'reports.daily',
            ],
        ],
        'agency_manager' => [
            'level' => 3,
            'label' => 'Agency Manager',
            'permissions' => [
                'account.create',
                'account.view',
                'account.manage',
                'transactions.create',
                'transactions.view',
                'transactions.approve',
                'kyc.view',
                'kyc.approve',
                'agency.manage',
                'staff.manage',
                'reports.view',
                'reports.export',
            ],
        ],
        'compliance_officer' => [
            'level' => 4,
            'label' => 'Compliance Officer',
            'permissions' => [
                'kyc.view',
                'kyc.approve',
                'kyc.reject',
                'transactions.view',
                'audit.view',
                'fraud.view',
                'fraud.manage',
                'reports.view',
                'reports.export',
            ],
        ],
        'admin' => [
            'level' => 5,
            'label' => 'Administrator',
            'permissions' => ['*'],
        ],
    ],

    'permissions' => [
        // Auth
        'auth.login' => 'Can login to the system',
        'auth.register' => 'Can register new account',
        'auth.logout' => 'Can logout from system',
        'auth.2fa' => 'Can use 2FA',

        // Accounts
        'account.view_own' => 'Can view own account',
        'account.create' => 'Can create new accounts',
        'account.view' => 'Can view accounts',
        'account.manage' => 'Can manage accounts',
        'account.freeze' => 'Can freeze accounts',

        // Transactions
        'transactions.create_own' => 'Can create own transactions',
        'transactions.create' => 'Can create transactions',
        'transactions.view_own' => 'Can view own transactions',
        'transactions.view' => 'Can view transactions',
        'transactions.approve' => 'Can approve transactions',
        'transactions.reject' => 'Can reject transactions',
        'transactions.reverse' => 'Can reverse transactions',

        // KYC
        'kyc.view_own' => 'Can view own KYC',
        'kyc.view' => 'Can view KYC profiles',
        'kyc.submit' => 'Can submit KYC documents',
        'kyc.approve' => 'Can approve KYC',
        'kyc.reject' => 'Can reject KYC',

        // Fraud & AML
        'fraud.view' => 'Can view fraud alerts',
        'fraud.manage' => 'Can manage fraud cases',
        'aml.check' => 'Can check AML status',

        // Agency
        'agency.view_own' => 'Can view own agency',
        'agency.manage' => 'Can manage agency',
        'staff.manage' => 'Can manage staff',

        // Reports
        'reports.daily' => 'Can view daily reports',
        'reports.view' => 'Can view all reports',
        'reports.export' => 'Can export reports',

        // Audit
        'audit.view' => 'Can view audit logs',
        'audit.export' => 'Can export audit logs',

        // Settings
        'settings.manage' => 'Can manage system settings',
    ],
];
