<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to match_events table for better performance
        Schema::table('match_events', function (Blueprint $table) {
            $table->index(['match_id', 'player_id', 'type'], 'match_events_match_player_type_index');
            $table->index(['team_id', 'type'], 'match_events_team_type_index');
            $table->index(['match_id', 'type'], 'match_events_match_type_index');
            $table->index(['player_id', 'type'], 'match_events_player_type_index');
        });

        // Add indexes to matches table
        Schema::table('matches', function (Blueprint $table) {
            $table->index(['home_team_id', 'away_team_id'], 'matches_teams_index');
            $table->index(['tournament_id', 'status'], 'matches_tournament_status_index');
            $table->index(['scheduled_at'], 'matches_scheduled_at_index');
        });

        // Add indexes to tournament_teams table
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->index(['tournament_id', 'team_id'], 'tournament_teams_tournament_team_index');
            $table->index(['team_id', 'status'], 'tournament_teams_team_status_index');
        });

        // Add indexes to players table
        Schema::table('players', function (Blueprint $table) {
            $table->index(['team_id', 'position'], 'players_team_position_index');
            $table->index(['position'], 'players_position_index');
        });

        // Add indexes to teams table
        Schema::table('teams', function (Blueprint $table) {
            $table->index(['city', 'country'], 'teams_location_index');
            $table->index(['status'], 'teams_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from match_events table
        Schema::table('match_events', function (Blueprint $table) {
            $table->dropIndex('match_events_match_player_type_index');
            $table->dropIndex('match_events_team_type_index');
            $table->dropIndex('match_events_match_type_index');
            $table->dropIndex('match_events_player_type_index');
        });

        // Remove indexes from matches table
        Schema::table('matches', function (Blueprint $table) {
            $table->dropIndex('matches_teams_index');
            $table->dropIndex('matches_tournament_status_index');
            $table->dropIndex('matches_scheduled_at_index');
        });

        // Remove indexes from tournament_teams table
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropIndex('tournament_teams_tournament_team_index');
            $table->dropIndex('tournament_teams_team_status_index');
        });

        // Remove indexes from players table
        Schema::table('players', function (Blueprint $table) {
            $table->dropIndex('players_team_position_index');
            $table->dropIndex('players_position_index');
        });

        // Remove indexes from teams table
        Schema::table('teams', function (Blueprint $table) {
            $table->dropIndex('teams_location_index');
            $table->dropIndex('teams_status_index');
        });
    }
};
