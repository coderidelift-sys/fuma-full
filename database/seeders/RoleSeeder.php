<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access'
            ],
            [
                'name' => 'organizer',
                'display_name' => 'Tournament Organizer',
                'description' => 'Can manage tournaments and matches'
            ],
            [
                'name' => 'committee',
                'display_name' => 'Committee Member',
                'description' => 'Can manage matches and events'
            ],
            [
                'name' => 'manager',
                'display_name' => 'Team Manager',
                'description' => 'Can manage teams and players'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
