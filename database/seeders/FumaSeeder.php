<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel;
use App\Models\MatchEvent;
use Carbon\Carbon;

class FumaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create organizer user
        $organizer = User::firstOrCreate([
            'email' => 'organizer@fuma.com'
        ], [
            'name' => 'Tournament Organizer',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create tournaments
        $tournaments = [
            [
                'name' => 'Premier League 2023',
                'description' => 'The premier football tournament featuring the best teams',
                'status' => 'ongoing',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'max_teams' => 20,
                'venue' => 'Various Stadiums',
                'organizer_id' => $organizer->id,
            ],
            [
                'name' => 'Champions Cup',
                'description' => 'Elite knockout tournament for champion teams',
                'status' => 'upcoming',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(45),
                'max_teams' => 16,
                'venue' => 'National Stadium',
                'organizer_id' => $organizer->id,
            ],
            [
                'name' => 'Winter Tournament',
                'description' => 'Winter season championship',
                'status' => 'completed',
                'start_date' => Carbon::now()->subDays(120),
                'end_date' => Carbon::now()->subDays(60),
                'max_teams' => 8,
                'venue' => 'Winter Arena',
                'organizer_id' => $organizer->id,
            ],
        ];

        foreach ($tournaments as $tournamentData) {
            Tournament::firstOrCreate([
                'name' => $tournamentData['name']
            ], $tournamentData);
        }

        // Create teams
        $teams = [
            [
                'name' => 'City FC',
                'description' => 'Professional football club from the city',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'manager_name' => 'John Smith',
                'manager_phone' => '+62812345678',
                'manager_email' => 'john@cityfc.com',
                'rating' => 4.5,
                'trophies_count' => 3,
            ],
            [
                'name' => 'United SC',
                'description' => 'United Sports Club with rich history',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'manager_name' => 'Mike Johnson',
                'manager_phone' => '+62823456789',
                'manager_email' => 'mike@unitedsc.com',
                'rating' => 4.2,
                'trophies_count' => 2,
            ],
            [
                'name' => 'Dynamo FC',
                'description' => 'Dynamic and aggressive playing style',
                'city' => 'Surabaya',
                'country' => 'Indonesia',
                'manager_name' => 'Carlos Rodriguez',
                'manager_phone' => '+62834567890',
                'manager_email' => 'carlos@dynamofc.com',
                'rating' => 4.0,
                'trophies_count' => 1,
            ],
            [
                'name' => 'Rovers FC',
                'description' => 'Traditional football club with passionate fans',
                'city' => 'Medan',
                'country' => 'Indonesia',
                'manager_name' => 'David Wilson',
                'manager_phone' => '+62845678901',
                'manager_email' => 'david@roversfc.com',
                'rating' => 3.8,
                'trophies_count' => 1,
            ],
            [
                'name' => 'Arsenal FC',
                'description' => 'Strong attacking team with technical players',
                'city' => 'Yogyakarta',
                'country' => 'Indonesia',
                'manager_name' => 'Antonio Garcia',
                'manager_phone' => '+62856789012',
                'manager_email' => 'antonio@arsenalfc.com',
                'rating' => 4.3,
                'trophies_count' => 2,
            ],
            [
                'name' => 'Lightning FC',
                'description' => 'Fast-paced team known for quick attacks',
                'city' => 'Semarang',
                'country' => 'Indonesia',
                'manager_name' => 'Roberto Silva',
                'manager_phone' => '+62867890123',
                'manager_email' => 'roberto@lightningfc.com',
                'rating' => 3.9,
                'trophies_count' => 0,
            ],
        ];

        foreach ($teams as $teamData) {
            Team::firstOrCreate([
                'name' => $teamData['name']
            ], $teamData);
        }

        // Create players
        $playerNames = [
            'Marcus Johnson', 'David Silva', 'Carlos Rodriguez', 'Antonio Garcia',
            'Roberto Santos', 'Fernando Lopez', 'Miguel Torres', 'Diego Martinez',
            'Luis Gonzalez', 'Pablo Hernandez', 'Alejandro Ruiz', 'Francisco Morales',
            'Ricardo Vargas', 'Andres Castillo', 'Manuel Jimenez', 'Gabriel Romero',
            'Adrian Flores', 'Sergio Mendoza', 'Raul Delgado', 'Cristian Herrera',
            'Javier Guerrero', 'Eduardo Medina', 'Hector Aguilar', 'Omar Reyes'
        ];

        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];
        $nationalities = ['Indonesia', 'Brazil', 'Argentina', 'Spain', 'Portugal'];

        $teams = Team::all();
        foreach ($teams as $team) {
            for ($i = 0; $i < 15; $i++) {
                $name = $playerNames[array_rand($playerNames)];
                $position = $positions[array_rand($positions)];
                
                Player::firstOrCreate([
                    'name' => $name . ' ' . $team->name . ' ' . $i,
                    'team_id' => $team->id,
                ], [
                    'position' => $position,
                    'jersey_number' => $i + 1,
                    'birth_date' => Carbon::now()->subYears(rand(18, 35)),
                    'nationality' => $nationalities[array_rand($nationalities)],
                    'height' => rand(165, 195),
                    'weight' => rand(65, 85),
                    'rating' => rand(30, 50) / 10,
                    'goals_scored' => rand(0, 15),
                    'assists' => rand(0, 10),
                    'clean_sheets' => $position === 'Goalkeeper' ? rand(0, 8) : 0,
                    'yellow_cards' => rand(0, 5),
                    'red_cards' => rand(0, 2),
                ]);
            }
        }

        // Associate teams with tournaments
        $premierLeague = Tournament::where('name', 'Premier League 2023')->first();
        $championsLague = Tournament::where('name', 'Champions Cup')->first();
        
        if ($premierLeague) {
            $teamsForPremier = Team::limit(6)->get();
            foreach ($teamsForPremier as $index => $team) {
                $premierLeague->teams()->attach($team->id, [
                                            'status' => 'approved',
                    'points' => rand(0, 30),
                    'goals_for' => rand(5, 25),
                    'goals_against' => rand(3, 20),
                    'goal_difference' => rand(-10, 15),
                    'matches_played' => rand(5, 15),
                    'wins' => rand(0, 10),
                    'draws' => rand(0, 5),
                    'losses' => rand(0, 5),
                ]);
            }
        }

        if ($championsLague) {
            $teamsForChampions = Team::skip(2)->limit(4)->get();
            foreach ($teamsForChampions as $team) {
                $championsLague->teams()->attach($team->id, [
                    'status' => 'registered',
                    'points' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'goal_difference' => 0,
                    'matches_played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                ]);
            }
        }

        // Create matches
        if ($premierLeague) {
            $tournamentTeams = $premierLeague->teams;
            for ($i = 0; $i < 10; $i++) {
                $homeTeam = $tournamentTeams->random();
                $awayTeam = $tournamentTeams->where('id', '!=', $homeTeam->id)->random();
                
                $isCompleted = rand(0, 1);
                $scheduledAt = $isCompleted ? 
                    Carbon::now()->subDays(rand(1, 20)) : 
                    Carbon::now()->addDays(rand(1, 30));
                
                $match = MatchModel::firstOrCreate([
                    'tournament_id' => $premierLeague->id,
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'scheduled_at' => $scheduledAt,
                ], [
                    'stage' => 'group',
                    'status' => $isCompleted ? 'completed' : 'scheduled',
                    'venue' => 'Stadium ' . ($i + 1),
                    'home_score' => $isCompleted ? rand(0, 4) : null,
                    'away_score' => $isCompleted ? rand(0, 4) : null,
                    'notes' => 'Match ' . ($i + 1) . ' notes',
                ]);

                // Create match events for completed matches
                if ($isCompleted && $match->home_score > 0) {
                    $homePlayer = $homeTeam->players->random();
                    MatchEvent::firstOrCreate([
                        'match_id' => $match->id,
                        'player_id' => $homePlayer->id,
                        'type' => 'goal',
                        'minute' => rand(1, 90),
                    ], [
                        'description' => 'Great goal by ' . $homePlayer->name,
                    ]);
                }
            }
        }
    }
}