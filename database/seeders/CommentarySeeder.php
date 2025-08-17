<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MatchCommentary;
use App\Models\MatchModel;
use App\Models\User;

class CommentarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some matches and users for commentary
        $matches = MatchModel::take(5)->get();
        $users = User::take(3)->get();

        if ($matches->isEmpty() || $users->isEmpty()) {
            $this->command->info('No matches or users found. Skipping commentary seeding.');
            return;
        }

        $commentaryTypes = ['general', 'tactical', 'incident', 'highlight', 'warning'];
        $userRoles = ['referee', 'commentator', 'admin'];

        foreach ($matches as $match) {
            // Generate 5-15 commentary entries per match
            $commentaryCount = rand(5, 15);

            for ($i = 0; $i < $commentaryCount; $i++) {
                $minute = rand(1, 90);
                $type = $commentaryTypes[array_rand($commentaryTypes)];
                $user = $users->random();
                $userRole = $userRoles[array_rand($userRoles)];

                // Generate realistic commentary based on type
                $description = $this->generateCommentaryDescription($type, $minute, $match);

                MatchCommentary::create([
                    'match_id' => $match->id,
                    'user_id' => $user->id,
                    'user_role' => $userRole,
                    'minute' => $minute,
                    'commentary_type' => $type,
                    'description' => $description,
                    'is_important' => rand(1, 10) <= 2, // 20% chance of being important
                ]);
            }
        }

        $this->command->info('Commentary seeded successfully!');
    }

    /**
     * Generate realistic commentary descriptions
     */
    private function generateCommentaryDescription(string $type, int $minute, $match): string
    {
        $descriptions = [
            'general' => [
                "The match is heating up as both teams look for an opening.",
                "Good tempo from both sides, maintaining possession well.",
                "The crowd is getting behind their team, creating a great atmosphere.",
                "Both teams are showing good discipline in their defensive shape.",
                "The referee is managing the game well, keeping control of the tempo."
            ],
            'tactical' => [
                "Excellent pressing from the home team, forcing the away side into mistakes.",
                "The away team has switched to a more defensive formation, looking to counter.",
                "Good tactical adjustment by the home coach, bringing on fresh legs.",
                "The home team is exploiting the wide areas effectively.",
                "Smart tactical foul by the away team, preventing a dangerous counter-attack."
            ],
            'incident' => [
                "A heated exchange between players, the referee steps in to calm things down.",
                "Medical staff called onto the pitch to attend to an injured player.",
                "VAR check in progress for a potential penalty decision.",
                "The ball has gone out of play, causing a brief stoppage.",
                "A player receives treatment for a minor injury."
            ],
            'highlight' => [
                "WHAT A SAVE! Incredible reflexes from the goalkeeper to deny a certain goal!",
                "Brilliant skill on display! The player shows excellent technique.",
                "A moment of magic! The player creates something out of nothing.",
                "Spectacular goal! That will be replayed for years to come.",
                "Incredible teamwork leads to a beautiful attacking move."
            ],
            'warning' => [
                "The referee issues a verbal warning to the player for persistent fouling.",
                "A stern talking-to from the referee about time-wasting.",
                "The referee warns both captains about their team's conduct.",
                "A player is cautioned for dissent towards the referee.",
                "The referee reminds players about the importance of fair play."
            ]
        ];

        $typeDescriptions = $descriptions[$type] ?? $descriptions['general'];
        return $typeDescriptions[array_rand($typeDescriptions)];
    }
}
