<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin FUMA',
            'email' => 'admin@fuma.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole->id);

        // Create organizer user
        $organizer = User::create([
            'name' => 'Organizer Demo',
            'email' => 'organizer@fuma.com',
            'whatsapp' => '081234567891',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $organizerRole = Role::where('name', 'organizer')->first();
        $organizer->roles()->attach($organizerRole->id);

        // Create manager user
        $manager = User::create([
            'name' => 'Manager Demo',
            'email' => 'manager@fuma.com',
            'whatsapp' => '081234567892',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $managerRole = Role::where('name', 'manager')->first();
        $manager->roles()->attach($managerRole->id);

        // Create committee user
        $committee = User::create([
            'name' => 'Committee Demo',
            'email' => 'committee@fuma.com',
            'whatsapp' => '081234567893',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $committeeRole = Role::where('name', 'committee')->first();
        $committee->roles()->attach($committeeRole->id);
    }
}
