<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_lineups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['starting_xi', 'substitutes', 'bench'])->default('starting_xi');
            $table->integer('jersey_number');
            $table->string('position');
            $table->integer('substitution_minute')->nullable(); // When substituted in/out
            $table->enum('substitution_type', ['in', 'out'])->nullable();
            $table->boolean('is_captain')->default(false);
            $table->json('metadata')->nullable(); // Additional data like formation position
            $table->timestamps();

            // Indexes for performance
            $table->index(['match_id', 'team_id']);
            $table->index(['match_id', 'player_id']);
            $table->unique(['match_id', 'team_id', 'player_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_lineups');
    }
};
