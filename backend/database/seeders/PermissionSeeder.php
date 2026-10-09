<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'auth.login', 'description' => 'Can login', 'category' => 'auth'],
            ['name' => 'auth.register', 'description' => 'Can register', 'category' => 'auth'],
            ['name' => 'auth.logout', 'description' => 'Can logout', 'category' => 'auth'],
            ['name' => 'auth.2fa', 'description' => 'Can use 2FA', 'category' => 'auth'],
            ['name' => 'account.view_own', 'description' => 'Can view own account', 'category' => 'account'],
            ['name' => 'account.create', 'description' => 'Can create account', 'category' => 'account'],
            ['name' => 'account.view', 'description' => 'Can view accounts', 'category' => 'account'],
            ['name' => 'transactions.create_own', 'description' => 'Can create own transactions', 'category' => 'transactions'],
            ['name' => 'transactions.create', 'description' => 'Can create transactions', 'category' => 'transactions'],
            ['name' => 'transactions.view_own', 'description' => 'Can view own transactions', 'category' => 'transactions'],
            ['name' => 'transactions.view', 'description' => 'Can view transactions', 'category' => 'transactions'],
            ['name' => 'transactions.approve', 'description' => 'Can approve transaction', 'category' => 'transactions'],
            ['name' => 'kyc.view_own', 'description' => 'Can view own KYC', 'category' => 'kyc'],
            ['name' => 'kyc.view', 'description' => 'Can view KYC', 'category' => 'kyc'],
            ['name' => 'kyc.submit', 'description' => 'Can submit KYC', 'category' => 'kyc'],
            ['name' => 'kyc.approve', 'description' => 'Can approve KYC', 'category' => 'kyc'],
            ['name' => 'kyc.reject', 'description' => 'Can reject KYC', 'category' => 'kyc'],
            ['name' => 'fraud.view', 'description' => 'Can view fraud alerts', 'category' => 'fraud'],
            ['name' => 'fraud.manage', 'description' => 'Can manage fraud alerts', 'category' => 'fraud'],
            ['name' => 'agency.manage', 'description' => 'Can manage agencies', 'category' => 'agency'],
            ['name' => 'staff.manage', 'description' => 'Can manage staff', 'category' => 'agency'],
            ['name' => 'reports.view', 'description' => 'Can view reports', 'category' => 'reports'],
            ['name' => 'reports.export', 'description' => 'Can export reports', 'category' => 'reports'],
            ['name' => 'audit.view', 'description' => 'Can view audit logs', 'category' => 'audit'],
            ['name' => 'audit.export', 'description' => 'Can export audit logs', 'category' => 'audit'],
            ['name' => 'settings.manage', 'description' => 'Can manage settings', 'category' => 'settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}
