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

            $this->command->info('Seeding users...');
            $users = $this->seedUsers();

            $this->command->info('Assigning roles...');
            $this->assignRoles($users);

            $this->command->info('Seeding tournaments...');
            $tournaments = $this->seedTournaments($users);

            $this->command->info('Seeding teams...');
            $teams = $this->seedTeams($users);

            $this->command->info('Linking tournament teams...');
            $this->seedTournamentTeams($tournaments, $teams);

            $this->command->info('Seeding players...');
            $this->seedPlayers($teams);

            $this->command->info('Seeding matches...');
            $matches = $this->seedMatches($tournaments);

            $this->command->info('Seeding match events...');
            $this->seedMatchEvents($matches);

            $this->command->info('Seeding committees...');
            $this->seedCommittees($tournaments, $users);

            $this->command->info('✅ Comprehensive seeding completed!');
        });
    }

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

    private function seedUsers()
    {
        $data = [];

        // Admin
        $data[] = [
            'name' => 'Admin FUMA',
            'email' => 'admin@fuma.com',
            'password' => Hash::make('password'),
            'whatsapp' => '081234567890',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ];

        // Organizers
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

        // Managers
        foreach (range(1, count($this->teamNames)) as $i) {
            $data[] = [
                'name' => "Manager Team $i",
                'email' => "manager$i@fuma.com",
                'password' => Hash::make('password'),
                'whatsapp' => '082' . $this->faker->numerify('########'),
                'phone' => '082' . $this->faker->numerify('########'),
                'email_verified_at' => now(),
            ];
        }

        // Committee members
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

    private function assignRoles($users)
    {
        $roleMap = Role::pluck('id', 'name');

        foreach ($users as $user) {
            $role = 'user';
            if (str_contains($user->email, 'admin')) $role = 'admin';
            elseif (str_contains($user->email, 'organizer')) $role = 'organizer';
            elseif (str_contains($user->email, 'manager')) $role = 'manager';
            elseif (str_contains($user->email, 'committee')) $role = 'committee';

            DB::table('user_roles')->insert([
                'role_id' => $roleMap[$role],
                'user_id' => $user->id
            ]);
        }
    }

    private function seedTournaments($users)
    {
        $organizers = $users->filter(fn($u) => str_contains($u->email, 'organizer'))->values();
        $tournamentsData = [
            ['Liga FUMA 2024', 'ongoing', -30, 60, 16, 'Stadion Utama Jakarta'],
            ['Piala FUMA Cup', 'ongoing', -15, 45, 8, 'Stadion Bandung Lautan Api'],
            ['Championship FUMA 2024', 'upcoming', 30, 90, 12, 'Stadion GBK'],
            ['Youth League FUMA', 'upcoming', 45, 105, 10, 'Stadion Pakansari'],
            ['Liga FUMA 2023', 'completed', -180, -90, 14, 'Stadion Manahan Solo'],
        ];

        $records = [];
        foreach ($tournamentsData as $t) {
            $records[] = [
                'name' => $t[0],
                'description' => $this->faker->sentence(10),
                'prize_pool' => rand(10000000, 500000000), // 10 juta - 500 juta
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

    private function seedTeams($users)
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

    private function seedTournamentTeams($tournaments, $teams)
    {
        $pivot = [];
        foreach ($tournaments as $t) {
            $selectedTeams = $teams->random(min($t->max_teams, $teams->count()));
            foreach ($selectedTeams as $team) {
                $pivot[] = [
                    'tournament_id' => $t->id,
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
        DB::table('tournament_teams')->insert($pivot);
    }

    private function seedPlayers($teams)
    {
        $records = [];
        foreach ($teams as $team) {
            $jerseys = range(1, 99);
            shuffle($jerseys);
            foreach (range(0, rand(18, 25) - 1) as $i) {
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
        collect($records)->chunk(500)->each(fn($c) => Player::insert($c->toArray()));
    }

    private function seedMatches($tournaments)
    {
        $records = [];
        foreach ($tournaments as $t) {
            $tTeams = $t->teams;
            if ($tTeams->count() < 2) continue;
            $numMatches = rand(3, 15);
            for ($i = 0; $i < $numMatches; $i++) {
                $home = $tTeams->random();
                $away = $tTeams->where('id', '!=', $home->id)->random();
                $records[] = [
                    'tournament_id' => $t->id,
                    'home_team_id' => $home->id,
                    'away_team_id' => $away->id,
                    'stage' => $this->faker->randomElement(['group', 'round_of_16', 'quarter_final', 'semi_final', 'final']),
                    'status' => 'scheduled',
                    'scheduled_at' => $this->faker->dateTimeBetween($t->start_date, $t->end_date),
                    'venue' => $t->venue
                ];
            }
        }
        MatchModel::insert($records);
        return MatchModel::all();
    }

    private function seedMatchEvents($matches)
    {
        $records = [];
        foreach ($matches as $match) {
            $players = Player::whereIn('team_id', [$match->home_team_id, $match->away_team_id])->get();
            foreach (range(1, rand(3, 8)) as $i) {
                $player = $players->random();
                $records[] = [
                    'match_id' => $match->id,
                    'player_id' => $player->id,
                    'team_id' => $player->team_id,
                    'type' => $this->faker->randomElement(['goal', 'yellow_card', 'red_card', 'substitution', 'injury', 'other']),
                    'minute' => rand(1, 90),
                    'description' => $this->faker->sentence(6),
                ];
            }
        }
        // Insert in chunks untuk mencegah memory overflow
        collect($records)->chunk(500)->each(fn($c) => MatchEvent::insert($c->toArray()));
    }

    private function seedCommittees($tournaments, $users)
    {
        $committeeUsers = $users->filter(fn($u) => str_contains($u->email, 'committee'))->values();
        $records = [];

        foreach ($tournaments as $t) {
            $assigned = $committeeUsers->random(min(count($this->committeePositions), $committeeUsers->count()));
            foreach ($this->committeePositions as $i => $position) {
                if (!isset($assigned[$i])) continue;
                $user = $assigned[$i];
                $records[] = [
                    'tournament_id' => $t->id,
                    'user_id' => $user->id,
                    'position' => $position,
                    // 'contact' => $user->phone,
                ];
            }
        }

        Committee::insert($records);
    }
}
