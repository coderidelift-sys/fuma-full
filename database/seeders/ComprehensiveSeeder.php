<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\{
    User,
    Role,
    Tournament,
    Team,
    Player,
    MatchModel,
    MatchEvent,
    Committee
};

class ComprehensiveSeeder extends Seeder
{
    private $faker;
    private $cities = [
        'Jakarta',
        'Surabaya',
        'Bandung',
        'Medan',
        'Bekasi',
        'Tangerang',
        'Depok',
        'Semarang',
        'Palembang',
        'Makassar',
        'Batam',
        'Bogor',
        'Pekanbaru',
        'Bandar Lampung',
        'Padang',
        'Malang',
        'Denpasar',
        'Samarinda'
    ];
    private $teamNames = [
        'Persija Jakarta',
        'Persib Bandung',
        'Arema FC',
        'Bali United',
        'PSM Makassar',
        'Borneo FC',
        'Persebaya Surabaya',
        'PSS Sleman',
        'Persik Kediri',
        'PSIS Semarang',
        'Dewa United FC',
        'Persis Solo',
        'Persita Tangerang',
        'Bhayangkara FC',
        'Madura United',
        'Persikabo 1973',
        'Barito Putera',
        'PSB Biak',
        'Persiraja Banda Aceh',
        'Semen Padang FC',
        'Kalteng Putra FC',
        'Cilegon United',
        'PSIM Yogyakarta',
        'Persiku Kudus'
    ];
    private $positions = ['GK', 'CB', 'LB', 'RB', 'CDM', 'CM', 'CAM', 'LW', 'RW', 'ST'];
    private $committeePositions = [
        'Tournament Director',
        'Match Commissioner',
        'Referee Coordinator',
        'Media Officer',
        'Security Officer',
        'Medical Officer'
    ];

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('Seeding roles...');
            $this->seedRoles();

            $this->command->info('Seeding users in bulk...');
            $users = $this->seedUsersBulk();
            $this->assignRoles($users);

            $this->command->info('Seeding tournaments...');
            $tournaments = $this->seedTournaments($users);

            $this->command->info('Seeding teams in bulk...');
            $teams = $this->seedTeamsBulk($users);

            $this->command->info('Linking tournament teams...');
            $this->seedTournamentTeamsFast($tournaments, $teams);

            $this->command->info('Seeding players in bulk...');
            $this->seedPlayersBulk($teams);

            $this->command->info('Seeding matches in bulk...');
            $matches = $this->seedMatchesBulk($tournaments, $teams);

            $this->command->info('Seeding match events in bulk...');
            $this->seedMatchEventsBulk($matches);

            $this->command->info('Seeding committees in bulk...');
            $this->seedCommitteesBulk($tournaments, $users);

            $this->command->info('✅ Fast seeding completed!');
        });
    }

    /** -------------------------
     * Seeder Steps (Bulk Version)
     * ------------------------- */
    private function seedRoles(): void
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'organizer', 'display_name' => 'Tournament Organizer'],
            ['name' => 'committee', 'display_name' => 'Committee Member'],
            ['name' => 'manager', 'display_name' => 'Team Manager'],
            ['name' => 'user', 'display_name' => 'Regular User'],
        ];
        Role::insertOrIgnore($roles);
    }

    private function assignRoles($users)
    {
        $roleMap = Role::pluck('id', 'name');

        foreach ($users as $user) {
            if (str_contains($user->email, 'admin')) {
                DB::table('user_roles')->insert([
                    'role_id' => $roleMap['admin'],
                    'user_id' => $user->id
                ]);
            } elseif (str_contains($user->email, 'organizer')) {
                DB::table('user_roles')->insert([
                    'role_id' => $roleMap['organizer'],
                    'user_id' => $user->id
                ]);
            } elseif (str_contains($user->email, 'manager')) {
                DB::table('user_roles')->insert([
                    'role_id' => $roleMap['manager'],
                    'user_id' => $user->id
                ]);
            } elseif (str_contains($user->email, 'committee')) {
                DB::table('user_roles')->insert([
                    'role_id' => $roleMap['committee'],
                    'user_id' => $user->id
                ]);
            } else {
                DB::table('user_roles')->insert([
                    'role_id' => $roleMap['user'],
                    'user_id' => $user->id
                ]);
            }
        }
    }

    private function seedUsersBulk()
    {
        $data = [];
        $roleMap = Role::pluck('id', 'name');

        // Admin
        $data[] = [
            'name' => 'Admin FUMA',
            'email' => 'admin@fuma.com',
            'password' => Hash::make('password'),
            'whatsapp' => '081234567890',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ];

        // Organizer, Manager, Committee
        foreach (range(1, 5) as $i) {
            $data[] = [
                'name' => "Organizer $i",
                'email' => "organizer$i@fuma.com",
                'password' => Hash::make('password'),
                'whatsapp' => '08123' . $this->faker->numerify('#####'),
                'phone' => '08123' . $this->faker->numerify('#####'),
                'email_verified_at' => now(),
            ];
        }
        foreach (range(1, 24) as $i) {
            $data[] = [
                'name' => "Manager Team $i",
                'email' => "manager$i@fuma.com",
                'password' => Hash::make('password'),
                'whatsapp' => '082' . $this->faker->numerify('########'),
                'phone' => '082' . $this->faker->numerify('########'),
                'email_verified_at' => now(),
            ];
        }
        foreach (range(1, 15) as $i) {
            $data[] = [
                'name' => "Committee Member $i",
                'email' => "committee$i@fuma.com",
                'password' => Hash::make('password'),
                'whatsapp' => '083' . $this->faker->numerify('########'),
                'phone' => '083' . $this->faker->numerify('########'),
                'email_verified_at' => now(),
            ];
        }

        User::insert($data);
        return User::all();
    }

    private function seedTournaments($users)
    {
        $organizers = $users->filter(fn($u) => str_contains($u->email, 'organizer'))->values();
        $data = [
            ['Liga FUMA 2024', 'ongoing', -30, 60, 16, 'Stadion Utama Jakarta'],
            ['Piala FUMA Cup', 'ongoing', -15, 45, 8, 'Stadion Bandung Lautan Api'],
            ['Championship FUMA 2024', 'upcoming', 30, 90, 12, 'Stadion GBK'],
            ['Youth League FUMA', 'upcoming', 45, 105, 10, 'Stadion Pakansari'],
            ['Liga FUMA 2023', 'completed', -180, -90, 14, 'Stadion Manahan Solo'],
        ];
        $records = [];
        foreach ($data as $t) {
            $records[] = [
                'name' => $t[0],
                'description' => $this->faker->sentence(8),
                'status' => $t[1],
                'start_date' => Carbon::now()->addDays($t[2]),
                'end_date' => Carbon::now()->addDays($t[3]),
                'max_teams' => $t[4],
                'venue' => $t[5],
                'organizer_id' => $organizers->random()->id
            ];
        }
        Tournament::insert($records);
        return Tournament::all();
    }

    private function seedTeamsBulk($users)
    {
        $managers = $users->filter(fn($u) => str_contains($u->email, 'manager'))->values();
        $records = [];
        foreach ($this->teamNames as $i => $name) {
            $m = $managers[$i % $managers->count()];
            $records[] = [
                'name' => $name,
                'description' => "Tim profesional dari " . $this->faker->randomElement($this->cities),
                'city' => $this->faker->randomElement($this->cities),
                'country' => 'Indonesia',
                'manager_id' => $m->id,
                'manager_name' => $m->name,
                'manager_phone' => $m->phone,
                'manager_email' => $m->email,
                'rating' => round($this->faker->randomFloat(2, 1, 5), 2),
                'trophies_count' => rand(0, 15),
            ];
        }
        Team::insert($records);
        return Team::all();
    }

    private function seedTournamentTeamsFast($tournaments, $teams)
    {
        $pivotData = [];
        foreach ($tournaments as $tournament) {
            $selectedTeams = $teams->random(min($tournament->max_teams, $teams->count()));
            foreach ($selectedTeams as $team) {
                $pivotData[] = [
                    'tournament_id' => $tournament->id,
                    'team_id' => $team->id,
                    'status' => 'approved',
                    'points' => 0,
                    'goals_for' => 0,
                    'goals_against' => 0,
                    'goal_difference' => 0,
                    'matches_played' => 0,
                    'wins' => 0,
                    'draws' => 0,
                    'losses' => 0,
                ];
            }
        }
        DB::table('tournament_teams')->insert($pivotData);
    }

    private function seedPlayersBulk($teams)
    {
        $records = [];
        foreach ($teams as $team) {
            $jerseys = range(1, 99);
            shuffle($jerseys);
            foreach (range(1, rand(18, 25)) as $i) {
                $records[] = [
                    'name' => $this->faker->name,
                    'position' => $this->faker->randomElement($this->positions),
                    'jersey_number' => $jerseys[$i],
                    'birth_date' => $this->faker->dateTimeBetween('-35 years', '-18 years'),
                    'nationality' => 'Indonesia',
                    'height' => $this->faker->randomFloat(1, 160, 195),
                    'weight' => $this->faker->randomFloat(1, 55, 90),
                    'rating' => round($this->faker->randomFloat(2, 1, 5), 2),
                    'team_id' => $team->id
                ];
            }
        }
        collect($records)->chunk(500)->each(fn($chunk) => Player::insert($chunk->toArray()));
    }

    private function seedMatchesBulk($tournaments, $teams)
    {
        $records = [];
        foreach ($tournaments as $tournament) {
            $tTeams = $tournament->teams;
            if ($tTeams->count() < 2) continue;
            foreach (range(1, rand(3, 15)) as $i) {
                $home = $tTeams->random();
                $away = $tTeams->where('id', '!=', $home->id)->random();
                $records[] = [
                    'tournament_id' => $tournament->id,
                    'home_team_id' => $home->id,
                    'away_team_id' => $away->id,
                    'stage' => $this->faker->randomElement(['group', 'round_of_16', 'quarter_final', 'semi_final', 'final']),
                    'status' => 'scheduled',
                    'scheduled_at' => $this->faker->dateTimeBetween($tournament->start_date, $tournament->end_date),
                    'venue' => $tournament->venue
                ];
            }
        }
        MatchModel::insert($records);
        return MatchModel::all();
    }

    private function seedMatchEventsBulk($matches)
    {
        $records = [];
        foreach ($matches as $match) {
            foreach (range(1, rand(3, 8)) as $i) {
                $records[] = [
                    'match_id' => $match->id,
                    'player_id' => Player::whereIn('team_id', [$match->home_team_id, $match->away_team_id])
                        ->inRandomOrder()->first()->id,
                    'type' => $this->faker->randomElement(['goal', 'yellow_card', 'red_card', 'substitution']),
                    'minute' => rand(1, 90),
                    'description' => $this->faker->sentence
                ];
            }
        }
        collect($records)->chunk(500)->each(fn($chunk) => MatchEvent::insert($chunk->toArray()));
    }

    private function seedCommitteesBulk($tournaments, $users)
    {
        $committeeUsers = $users->filter(fn($u) => str_contains($u->email, 'committee'));
        $records = [];
        foreach ($tournaments as $tournament) {
            foreach ($committeeUsers->random(min(5, $committeeUsers->count())) as $cu) {
                $records[] = [
                    'tournament_id' => $tournament->id,
                    'user_id' => $cu->id,
                    'position' => $this->faker->randomElement($this->committeePositions),
                    'status' => 'active'
                ];
            }
        }
        Committee::insert($records);
    }
}
