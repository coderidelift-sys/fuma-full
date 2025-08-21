<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add constraints using raw SQL for data integrity

        // Players table constraints
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_rating CHECK (rating >= 0 AND rating <= 100)');
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_goals CHECK (goals_scored >= 0)');
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_assists CHECK (assists >= 0)');
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_clean_sheets CHECK (clean_sheets >= 0)');
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_yellow_cards CHECK (yellow_cards >= 0)');
        DB::statement('ALTER TABLE players ADD CONSTRAINT chk_players_red_cards CHECK (red_cards >= 0)');

        // Teams table constraints
        DB::statement('ALTER TABLE teams ADD CONSTRAINT chk_teams_rating CHECK (rating >= 0 AND rating <= 100)');
        DB::statement('ALTER TABLE teams ADD CONSTRAINT chk_teams_trophies CHECK (trophies_count >= 0)');

        // Matches table constraints
        DB::statement('ALTER TABLE matches ADD CONSTRAINT chk_matches_home_score CHECK (home_score >= 0 OR home_score IS NULL)');
        DB::statement('ALTER TABLE matches ADD CONSTRAINT chk_matches_away_score CHECK (away_score >= 0 OR away_score IS NULL)');
        DB::statement('ALTER TABLE matches ADD CONSTRAINT chk_matches_current_minute CHECK (current_minute >= 0 AND current_minute <= 120 OR current_minute IS NULL)');
        DB::statement('ALTER TABLE matches ADD CONSTRAINT chk_matches_duration CHECK (duration >= 45 AND duration <= 120)');

        // Match events table constraints
        DB::statement('ALTER TABLE match_events ADD CONSTRAINT chk_match_events_minute CHECK (minute >= 0 AND minute <= 120)');

        // Tournament teams table constraints
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_points CHECK (points >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_goals_for CHECK (goals_for >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_goals_against CHECK (goals_against >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_matches_played CHECK (matches_played >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_wins CHECK (wins >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_draws CHECK (draws >= 0)');
        DB::statement('ALTER TABLE tournament_teams ADD CONSTRAINT chk_tournament_teams_losses CHECK (losses >= 0)');

        // Venues table constraints
        DB::statement('ALTER TABLE venues ADD CONSTRAINT chk_venues_capacity CHECK (capacity > 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove constraints from players table
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_rating');
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_goals');
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_assists');
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_clean_sheets');
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_yellow_cards');
        DB::statement('ALTER TABLE players DROP CONSTRAINT IF EXISTS chk_players_red_cards');

        // Remove constraints from teams table
        DB::statement('ALTER TABLE teams DROP CONSTRAINT IF EXISTS chk_teams_rating');
        DB::statement('ALTER TABLE teams DROP CONSTRAINT IF EXISTS chk_teams_trophies');

        // Remove constraints from matches table
        DB::statement('ALTER TABLE matches DROP CONSTRAINT IF EXISTS chk_matches_home_score');
        DB::statement('ALTER TABLE matches DROP CONSTRAINT IF EXISTS chk_matches_away_score');
        DB::statement('ALTER TABLE matches DROP CONSTRAINT IF EXISTS chk_matches_current_minute');
        DB::statement('ALTER TABLE matches DROP CONSTRAINT IF EXISTS chk_matches_duration');

        // Remove constraints from match_events table
        DB::statement('ALTER TABLE match_events DROP CONSTRAINT IF EXISTS chk_match_events_minute');

        // Remove constraints from tournament_teams table
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_points');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_goals_for');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_goals_against');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_matches_played');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_wins');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_draws');
        DB::statement('ALTER TABLE tournament_teams DROP CONSTRAINT IF EXISTS chk_tournament_teams_losses');

        // Remove constraints from venues table
        DB::statement('ALTER TABLE venues DROP CONSTRAINT IF EXISTS chk_venues_capacity');
    }
};
