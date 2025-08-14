<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::where('email', 'manager@fuma.com')->first();

        $teams = [
            [
                'name' => 'City FC',
                'description' => 'Professional football club from New York',
                'city' => 'New York',
                'country' => 'USA',
                'manager_name' => 'John Manager',
                'manager_phone' => '081234567890',
                'manager_email' => 'manager@cityfc.com',
                'rating' => 4.8,
                'trophies_count' => 12,
                'manager_id' => $manager->id
            ],
            [
                'name' => 'United SC',
                'description' => 'Elite football club from London',
                'city' => 'London',
                'country' => 'UK',
                'manager_name' => 'David Manager',
                'manager_phone' => '081234567891',
                'manager_email' => 'manager@unitedsc.com',
                'rating' => 4.7,
                'trophies_count' => 9,
                'manager_id' => $manager->id
            ],
            [
                'name' => 'Dynamo FC',
                'description' => 'Dynamic football club from Berlin',
                'city' => 'Berlin',
                'country' => 'Germany',
                'manager_name' => 'Michael Manager',
                'manager_phone' => '081234567892',
                'manager_email' => 'manager@dynamofc.com',
                'rating' => 4.6,
                'trophies_count' => 7,
                'manager_id' => $manager->id
            ],
            [
                'name' => 'Rovers FC',
                'description' => 'Traditional football club from Madrid',
                'city' => 'Madrid',
                'country' => 'Spain',
                'manager_name' => 'Robert Manager',
                'manager_phone' => '081234567893',
                'manager_email' => 'manager@roversfc.com',
                'rating' => 4.5,
                'trophies_count' => 5,
                'manager_id' => $manager->id
            ]
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
