<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\User;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('email', 'organizer@fuma.com')->first();

        $tournaments = [
            [
                'name' => 'Premier League 2024',
                'description' => '20 top teams battling for championship',
                'status' => 'ongoing',
                'start_date' => '2024-01-01',
                'end_date' => '2024-06-30',
                'max_teams' => 20,
                'venue' => 'National Stadium',
                'organizer_id' => $organizer->id
            ],
            [
                'name' => 'Champions Cup 2024',
                'description' => 'Knockout tournament with 16 elite teams',
                'status' => 'upcoming',
                'start_date' => '2024-07-01',
                'end_date' => '2024-09-30',
                'max_teams' => 16,
                'venue' => 'City Arena',
                'organizer_id' => $organizer->id
            ],
            [
                'name' => 'Winter Tournament 2024',
                'description' => 'Annual cold weather competition',
                'status' => 'completed',
                'start_date' => '2023-12-01',
                'end_date' => '2024-02-28',
                'max_teams' => 8,
                'venue' => 'Community Ground',
                'organizer_id' => $organizer->id
            ]
        ];

        foreach ($tournaments as $tournament) {
            Tournament::create($tournament);
        }
    }
}
