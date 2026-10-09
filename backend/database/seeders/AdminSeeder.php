<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@reenmoney.local'],
            [
                'phone_number' => '+237600000000',
                'password' => Hash::make('Password123!'),
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'status' => 'active',
                'aml_status' => 'clear',
                'two_factor_enabled' => false,
            ]
        );

        $role = Role::where('name', 'admin')->first();
        if ($role) {
            $admin->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
