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
        Schema::table('match_lineups', function (Blueprint $table) {
            // First, drop the existing enum column
            $table->dropColumn('type');
        });

        Schema::table('match_lineups', function (Blueprint $table) {
            // Recreate with correct enum values
            $table->enum('type', ['starting_xi', 'substitute', 'bench'])->default('starting_xi')->after('player_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_lineups', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('match_lineups', function (Blueprint $table) {
            $table->enum('type', ['starting_xi', 'substitutes', 'bench'])->default('starting_xi')->after('player_id');
        });
    }
};
