<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'customer', 'label' => 'Customer', 'level' => 1, 'description' => 'Customer user'],
            ['name' => 'agent', 'label' => 'Agency Agent', 'level' => 2, 'description' => 'Agency employee'],
            ['name' => 'agency_manager', 'label' => 'Agency Manager', 'level' => 3, 'description' => 'Agency manager'],
            ['name' => 'compliance_officer', 'label' => 'Compliance Officer', 'level' => 4, 'description' => 'Risk and compliance'],
            ['name' => 'admin', 'label' => 'Administrator', 'level' => 5, 'description' => 'System administrator'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
