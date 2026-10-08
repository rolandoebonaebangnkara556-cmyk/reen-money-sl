<?php

return [
    'roles' => [
        'admin' => [
            'level' => 1,
            'name' => 'Administrator',
            'description' => 'Full system access',
        ],
        'director' => [
            'level' => 2,
            'name' => 'Department Director',
            'description' => 'Department-level control',
        ],
        'responsable' => [
            'level' => 3,
            'name' => 'Area Responsible',
            'description' => 'Area-level management',
        ],
        'adjunto_senior' => [
            'level' => 4,
            'name' => 'Senior Assistant',
            'description' => 'Senior operational role',
        ],
        'adjunto_junior' => [
            'level' => 5,
            'name' => 'Junior Assistant',
            'description' => 'Junior operational role',
        ],
        'operador' => [
            'level' => 6,
            'name' => 'Operator',
            'description' => 'Support and consultation',
        ],
        'support' => [
            'level' => 7,
            'name' => 'Support Officer',
            'description' => 'Customer support',
        ],
        'customer' => [
            'level' => 8,
            'name' => 'Individual Customer',
            'description' => 'End customer',
        ],
        'merchant' => [
            'level' => 9,
            'name' => 'Merchant/Business',
            'description' => 'Business account',
        ],
        'api_partner' => [
            'level' => 10,
            'name' => 'API Partner',
            'description' => 'External integration',
        ],
    ],

    'permissions' => [
        // Authentication
        'auth.login' => 'Can login',
        'auth.register' => 'Can register',
        'auth.logout' => 'Can logout',
        'auth.2fa' => 'Can use 2FA',

        // Dashboard
        'dashboard.view' => 'Can view dashboard',
        'dashboard.global' => 'Can view global dashboard',
        'dashboard.department' => 'Can view department dashboard',
        'dashboard.area' => 'Can view area dashboard',

        // Users
        'users.list' => 'Can list users',
        'users.create' => 'Can create users',
        'users.edit' => 'Can edit users',
        'users.delete' => 'Can delete users',
        'users.edit_roles' => 'Can edit user roles',
        'users.edit_limits' => 'Can edit transaction limits',

        // Departments
        'departments.list' => 'Can list departments',
        'departments.create' => 'Can create departments',
        'departments.edit' => 'Can edit departments',
        'departments.delete' => 'Can delete departments',
        'departments.view_all' => 'Can view all departments',
        'departments.view_own' => 'Can view own department',

        // Areas
        'areas.list' => 'Can list areas',
        'areas.create' => 'Can create areas',
        'areas.edit' => 'Can edit areas',
        'areas.delete' => 'Can delete areas',
        'areas.view_own' => 'Can view own area',

        // Transactions
        'transactions.create' => 'Can create transactions',
        'transactions.view' => 'Can view transactions',
        'transactions.view_all' => 'Can view all transactions',
        'transactions.view_own' => 'Can view own transactions',
        'transactions.approve' => 'Can approve transactions',
        'transactions.reject' => 'Can reject transactions',
        'transactions.cancel' => 'Can cancel transactions',

        // KYC
        'kyc.view' => 'Can view KYC profiles',
        'kyc.approve' => 'Can approve KYC',
        'kyc.reject' => 'Can reject KYC',
        'kyc.list' => 'Can list KYC requests',

        // Reports
        'reports.view' => 'Can view reports',
        'reports.export' => 'Can export reports',
        'reports.global' => 'Can view global reports',
        'reports.department' => 'Can view department reports',
        'reports.area' => 'Can view area reports',

        // Audit
        'audit.view' => 'Can view audit logs',
        'audit.export' => 'Can export audit logs',
        'audit.download' => 'Can download audit logs',

        // Compliance
        'compliance.view' => 'Can view compliance',
        'compliance.manage' => 'Can manage compliance',
        'compliance.approve_kyc' => 'Can approve KYC requests',
        'compliance.aml_check' => 'Can review AML flags',

        // Fraud
        'fraud.view' => 'Can view fraud alerts',
        'fraud.manage' => 'Can manage fraud alerts',

        // Settings
        'settings.view' => 'Can view settings',
        'settings.edit' => 'Can edit settings',
        'settings.system' => 'Can edit system settings',
    ],

    'role_permissions' => [
        'admin' => [
            // All permissions
            '*',
        ],
        'director' => [
            'dashboard.view',
            'dashboard.department',
            'users.list',
            'users.create',
            'users.edit',
            'users.edit_roles',
            'departments.view_own',
            'areas.list',
            'areas.view_own',
            'transactions.create',
            'transactions.view',
            'transactions.approve',
            'transactions.reject',
            'kyc.view',
            'reports.view',
            'reports.department',
            'audit.view',
        ],
        'responsable' => [
            'dashboard.view',
            'dashboard.area',
            'users.list',
            'transactions.create',
            'transactions.view',
            'transactions.approve',
            'transactions.reject',
            'areas.view_own',
            'reports.view',
            'reports.area',
        ],
        'adjunto_senior' => [
            'dashboard.view',
            'transactions.create',
            'transactions.view_own',
            'reports.view',
        ],
        'adjunto_junior' => [
            'dashboard.view',
            'transactions.create',
            'transactions.view_own',
        ],
        'operador' => [
            'dashboard.view',
            'transactions.view',
            'reports.view',
        ],
        'support' => [
            'dashboard.view',
            'users.view',
            'transactions.view',
            'kyc.view',
        ],
        'customer' => [
            'auth.login',
            'auth.register',
            'auth.logout',
            'auth.2fa',
            'dashboard.view',
            'transactions.create',
            'transactions.view_own',
        ],
        'merchant' => [
            'auth.login',
            'dashboard.view',
            'transactions.view_own',
            'reports.view',
        ],
        'api_partner' => [
            'transactions.view_own',
        ],
    ],
];
