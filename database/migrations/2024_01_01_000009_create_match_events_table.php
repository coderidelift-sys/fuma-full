<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['goal', 'yellow_card', 'red_card', 'substitution', 'injury', 'other']);
            $table->integer('minute');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data like assist, card reason, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_events');
    }
};
