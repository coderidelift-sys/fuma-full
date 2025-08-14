<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Team;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $cityFC = Team::where('name', 'City FC')->first();
        $unitedSC = Team::where('name', 'United SC')->first();
        $dynamoFC = Team::where('name', 'Dynamo FC')->first();
        $roversFC = Team::where('name', 'Rovers FC')->first();

        $players = [
            // City FC Players
            [
                'name' => 'John Smith',
                'position' => 'Forward',
                'jersey_number' => '9',
                'birth_date' => '1995-05-15',
                'nationality' => 'USA',
                'height' => 180.0,
                'weight' => 75.0,
                'rating' => 4.8,
                'goals_scored' => 12,
                'assists' => 5,
                'team_id' => $cityFC->id
            ],
            [
                'name' => 'Mike Johnson',
                'position' => 'Midfielder',
                'jersey_number' => '8',
                'birth_date' => '1993-08-20',
                'nationality' => 'USA',
                'height' => 175.0,
                'weight' => 70.0,
                'rating' => 4.6,
                'goals_scored' => 3,
                'assists' => 8,
                'team_id' => $cityFC->id
            ],

            // United SC Players
            [
                'name' => 'David Johnson',
                'position' => 'Midfielder',
                'jersey_number' => '10',
                'birth_date' => '1994-03-10',
                'nationality' => 'UK',
                'height' => 178.0,
                'weight' => 72.0,
                'rating' => 4.7,
                'goals_scored' => 8,
                'assists' => 12,
                'team_id' => $unitedSC->id
            ],
            [
                'name' => 'James Wilson',
                'position' => 'Defender',
                'jersey_number' => '4',
                'birth_date' => '1992-11-25',
                'nationality' => 'UK',
                'height' => 185.0,
                'weight' => 78.0,
                'rating' => 4.5,
                'goals_scored' => 1,
                'assists' => 2,
                'team_id' => $unitedSC->id
            ],

            // Dynamo FC Players
            [
                'name' => 'Michael Brown',
                'position' => 'Defender',
                'jersey_number' => '4',
                'birth_date' => '1991-07-12',
                'nationality' => 'Germany',
                'height' => 188.0,
                'weight' => 80.0,
                'rating' => 4.6,
                'goals_scored' => 0,
                'assists' => 1,
                'clean_sheets' => 5,
                'team_id' => $dynamoFC->id
            ],
            [
                'name' => 'Hans Mueller',
                'position' => 'Goalkeeper',
                'jersey_number' => '1',
                'birth_date' => '1990-04-18',
                'nationality' => 'Germany',
                'height' => 190.0,
                'weight' => 85.0,
                'rating' => 4.7,
                'goals_scored' => 0,
                'assists' => 0,
                'clean_sheets' => 8,
                'team_id' => $dynamoFC->id
            ],

            // Rovers FC Players
            [
                'name' => 'Robert Wilson',
                'position' => 'Goalkeeper',
                'jersey_number' => '1',
                'birth_date' => '1989-09-30',
                'nationality' => 'Spain',
                'height' => 188.0,
                'weight' => 82.0,
                'rating' => 4.9,
                'goals_scored' => 0,
                'assists' => 0,
                'clean_sheets' => 7,
                'team_id' => $roversFC->id
            ],
            [
                'name' => 'Carlos Rodriguez',
                'position' => 'Forward',
                'jersey_number' => '7',
                'birth_date' => '1996-01-05',
                'nationality' => 'Spain',
                'height' => 182.0,
                'weight' => 76.0,
                'rating' => 4.4,
                'goals_scored' => 6,
                'assists' => 4,
                'team_id' => $roversFC->id
            ]
        ];

        foreach ($players as $player) {
            Player::create($player);
        }
    }
}
