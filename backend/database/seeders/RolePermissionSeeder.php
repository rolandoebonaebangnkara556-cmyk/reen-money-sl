<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            'customer' => [
                'auth.login', 'auth.register', 'auth.logout', 'auth.2fa',
                'account.view_own', 'transactions.create_own', 'transactions.view_own',
                'kyc.view_own', 'kyc.submit'
            ],
            'agent' => [
                'auth.login', 'auth.logout', 'auth.2fa', 'account.create', 'account.view',
                'transactions.create', 'transactions.view', 'kyc.view', 'kyc.submit',
                'agency.view_own', 'reports.daily'
            ],
            'agency_manager' => [
                'auth.login', 'auth.logout', 'auth.2fa', 'account.create', 'account.view', 'account.manage',
                'transactions.create', 'transactions.view', 'transactions.approve', 'kyc.view',
                'kyc.approve', 'agency.manage', 'staff.manage', 'reports.view', 'reports.export'
            ],
            'compliance_officer' => [
                'auth.login', 'auth.logout', 'auth.2fa', 'kyc.view', 'kyc.approve', 'kyc.reject',
                'transactions.view', 'audit.view', 'fraud.view', 'fraud.manage', 'reports.view', 'reports.export'
            ],
            'admin' => [
                'auth.login', 'auth.logout', 'auth.2fa', 'account.create', 'account.view', 'account.manage',
                'transactions.create', 'transactions.view', 'transactions.approve', 'transactions.reject',
                'kyc.view', 'kyc.approve', 'kyc.reject', 'fraud.view', 'fraud.manage', 'agency.manage',
                'staff.manage', 'reports.view', 'reports.export', 'audit.view', 'audit.export', 'settings.manage'
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->firstOrFail();
            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}
