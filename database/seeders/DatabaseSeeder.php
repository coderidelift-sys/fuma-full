<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\MatchEvent;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Role;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     $this->call([
    //         ComprehensiveSeeder::class,
    //     ]);
    // }

    // public function run()
    // {
    //     $this->call([
    //         VenueSeeder::class,
    //     ]);

    //     $faker = \Faker\Factory::create('id_ID');
    //     $roles = [
    //         'admin' => 'Administrator',
    //         'organizer' => 'Organizer',
    //         'manager' => 'Team Manager',
    //         'committee' => 'Committee Member',
    //         // 'user' => 'Regular User', // bisa ditambahkan jika diperlukan
    //     ];

    //     foreach ($roles as $name => $display) {
    //         Role::firstOrCreate(['name' => $name, 'display_name' => $display]);
    //     }

    //     // Ambil semua role
    //     $roleMap = Role::pluck('id', 'name');

    //     // -------------------------------
    //     // Create Users
    //     // -------------------------------
    //     $usersToCreate = [
    //         'admin' => 'admin@fuma.com',
    //         'manager' => 'manager@fuma.com',
    //         'committee' => 'committee@fuma.com',
    //     ];

    //     foreach ($usersToCreate as $roleName => $email) {
    //         $user = User::create([
    //             'name' => $faker->name,
    //             'email' => $email,
    //             'password' => Hash::make('password'),
    //             'phone' => $faker->phoneNumber
    //         ]);

    //         DB::table('user_roles')->insert([
    //             'role_id' => $roleMap[$roleName],
    //             'user_id' => $user->id
    //         ]);
    //     }

    //     // -------------------------------
    //     // Create Organizers
    //     // -------------------------------
    //     $organizers = [];

    //     for ($o = 1; $o <= 3; $o++) {
    //         $user = User::create([
    //             'name' => $faker->name,
    //             'email' => "organizer{$o}@fuma.com",
    //             'password' => Hash::make('password'),
    //             'phone' => $faker->phoneNumber
    //         ]);

    //         // Assign role organizer
    //         DB::table('user_roles')->insert([
    //             'role_id' => $roleMap['organizer'],
    //             'user_id' => $user->id
    //         ]);

    //         $organizers[] = $user;
    //     }

    //     // -------------------------------
    //     // Create 5 Tournaments
    //     // -------------------------------
    //     foreach (range(1, 5) as $tIndex) {

    //         // Tentukan status tournament
    //         $statusChoice = ['completed', 'ongoing', 'upcoming'];
    //         $status = $statusChoice[array_rand($statusChoice)];

    //         // Tentukan tanggal realistis
    //         if ($status == 'completed') {
    //             $start = $faker->dateTimeBetween('-60 days', '-15 days');
    //         } elseif ($status == 'ongoing') {
    //             $start = $faker->dateTimeBetween('-10 days', 'now');
    //         } else {
    //             $start = $faker->dateTimeBetween('now', '+15 days');
    //         }
    //         $end = (clone $start)->modify('+' . rand(5, 15) . ' days');

    //         // Max teams realistis
    //         $maxTeams = [8, 16, 32][array_rand([0, 1, 2])];

    //         $tournament = Tournament::create([
    //             'name' => $faker->company . " Cup",
    //             'description' => $faker->paragraph,
    //             'prize_pool' => rand(100_000_000, 1_000_000_000),
    //             'start_date' => $start,
    //             'end_date' => $end,
    //             'max_teams' => $maxTeams,
    //             'venue' => $faker->city . " Stadium",
    //             'organizer_id' => $organizers[array_rand($organizers)]->id
    //         ]);

    //         // -------------------------------
    //         // Create Teams & Attach ke Tournament
    //         // -------------------------------
    //         $teams = [];
    //         for ($i = 1; $i <= $maxTeams; $i++) {
    //             $teamName = $faker->city . " FC";
    //             $team = Team::create([
    //                 'name' => $teamName,
    //                 'description' => $faker->sentence,
    //                 'city' => explode(' ', $teamName)[0],
    //                 'country' => 'Indonesia',
    //                 'manager_name' => $faker->name,
    //                 'manager_email' => $faker->email,
    //                 'rating' => rand(1, 5),
    //                 'trophies_count' => rand(0, 10)
    //             ]);
    //             $teams[] = $team;

    //             $tournament->teams()->attach($team->id, [
    //                 'status' => 'registered',
    //                 'points' => 0,
    //                 'goals_for' => 0,
    //                 'goals_against' => 0,
    //                 'goal_difference' => 0,
    //                 'matches_played' => 0,
    //                 'wins' => 0,
    //                 'draws' => 0,
    //                 'losses' => 0
    //             ]);

    //             // Players
    //             for ($p = 1; $p <= rand(11, 18); $p++) {
    //                 Player::create([
    //                     'name' => $faker->name,
    //                     'position' => ['GK', 'DEF', 'MID', 'FWD'][rand(0, 3)],
    //                     'jersey_number' => $p,
    //                     'birth_date' => $faker->dateTimeBetween('-35 years', '-18 years'),
    //                     'nationality' => 'Indonesia',
    //                     'height' => rand(165, 195),
    //                     'weight' => rand(60, 90),
    //                     'rating' => rand(1, 5),
    //                     'team_id' => $team->id,
    //                     'goals_scored' => 0,
    //                     'assists' => 0,
    //                     'clean_sheets' => 0,
    //                     'yellow_cards' => 0,
    //                     'red_cards' => 0
    //                 ]);
    //             }
    //         }

    //         // -------------------------------
    //         // Create Matches
    //         // -------------------------------
    //         $matches = [];
    //         $stages = $maxTeams <= 16
    //             ? ['group', 'quarter_final', 'semi_final', 'final']
    //             : ['group', 'round_of_16', 'quarter_final', 'semi_final', 'final'];

    //         foreach ($stages as $stage) {
    //             $teamsCopy = $teams;
    //             shuffle($teamsCopy);
    //             for ($i = 0; $i < count($teamsCopy); $i += 2) {
    //                 if (!isset($teamsCopy[$i + 1])) break;

    //                 $matchStatus = $status == 'completed'
    //                     ? 'completed'
    //                     : ($status == 'ongoing' ? ['scheduled', 'live', 'completed'][rand(0, 2)] : 'scheduled');

    //                 $homeScore = $matchStatus == 'completed' ? rand(0, 5) : 0;
    //                 $awayScore = $matchStatus == 'completed' ? rand(0, 5) : 0;

    //                 $match = MatchModel::create([
    //                     'tournament_id' => $tournament->id,
    //                     'home_team_id' => $teamsCopy[$i]->id,
    //                     'away_team_id' => $teamsCopy[$i + 1]->id,
    //                     'stage' => $stage,
    //                     'status' => $matchStatus,
    //                     'scheduled_at' => $faker->dateTimeBetween($start, $end),
    //                     'venue' => $tournament->venue,
    //                     'home_score' => $homeScore,
    //                     'away_score' => $awayScore
    //                 ]);

    //                 // Events (Goals)
    //                 if ($matchStatus == 'completed') {
    //                     for ($g = 0; $g < $homeScore; $g++) {
    //                         $player = $teamsCopy[$i]->players()->inRandomOrder()->first();
    //                         MatchEvent::create([
    //                             'match_id' => $match->id,
    //                             'player_id' => $player->id,
    //                             'team_id' => $player->team_id,
    //                             'type' => 'goal',
    //                             'minute' => rand(1, 90),
    //                             'description' => 'Goal by ' . $player->name
    //                         ]);
    //                     }
    //                     for ($g = 0; $g < $awayScore; $g++) {
    //                         $player = $teamsCopy[$i + 1]->players()->inRandomOrder()->first();
    //                         MatchEvent::create([
    //                             'match_id' => $match->id,
    //                             'player_id' => $player->id,
    //                             'team_id' => $player->team_id,
    //                             'type' => 'goal',
    //                             'minute' => rand(1, 90),
    //                             'description' => 'Goal by ' . $player->name
    //                         ]);
    //                     }
    //                 }

    //                 $matches[] = $match;
    //             }
    //         }

    //         // -------------------------------
    //         // Update tournament_teams pivot berdasarkan hasil match
    //         // -------------------------------
    //         foreach ($tournament->teams as $team) {
    //             $teamMatches = MatchModel::where('tournament_id', $tournament->id)
    //                 ->where(function ($q) use ($team) {
    //                     $q->where('home_team_id', $team->id)
    //                         ->orWhere('away_team_id', $team->id);
    //                 })->get();

    //             $wins = $draws = $losses = $goals_for = $goals_against = 0;

    //             foreach ($teamMatches as $match) {
    //                 if ($match->status !== 'completed') continue;

    //                 if ($match->home_team_id == $team->id) {
    //                     $gf = $match->home_score;
    //                     $ga = $match->away_score;
    //                 } else {
    //                     $gf = $match->away_score;
    //                     $ga = $match->home_score;
    //                 }

    //                 $goals_for += $gf;
    //                 $goals_against += $ga;

    //                 if ($gf > $ga) $wins++;
    //                 elseif ($gf == $ga) $draws++;
    //                 else $losses++;
    //             }

    //             $points = $wins * 3 + $draws;
    //             $matches_played = $wins + $draws + $losses;
    //             $goal_difference = $goals_for - $goals_against;

    //             $tournament->teams()->updateExistingPivot($team->id, [
    //                 'points' => $points,
    //                 'matches_played' => $matches_played,
    //                 'wins' => $wins,
    //                 'draws' => $draws,
    //                 'losses' => $losses,
    //                 'goals_for' => $goals_for,
    //                 'goals_against' => $goals_against,
    //                 'goal_difference' => $goal_difference
    //             ]);
    //         }

    //         // -------------------------------
    //         // Update Tournament Status Otomatis
    //         // -------------------------------
    //         $totalMatches = count($matches);
    //         $completedMatches = collect($matches)->where('status', 'completed')->count();
    //         if ($completedMatches == 0) $tournament->update(['status' => 'upcoming']);
    //         elseif ($completedMatches < $totalMatches) $tournament->update(['status' => 'ongoing']);
    //         else $tournament->update(['status' => 'completed']);
    //     }
    // }

    public function run()
    {
        $this->call([
            ComprehensiveSeeder::class,
        ]);
    }
}
