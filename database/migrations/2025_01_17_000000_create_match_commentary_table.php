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
        Schema::create('match_commentary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_role', 50); // referee, commentator, admin, match_official
            $table->integer('minute'); // Match minute
            $table->enum('commentary_type', ['general', 'tactical', 'incident', 'highlight', 'warning'])->default('general');
            $table->text('description');
            $table->boolean('is_important')->default(false); // Highlight important commentary
            $table->timestamps();

            // Indexes for better performance
            $table->index(['match_id', 'minute']);
            $table->index(['user_role', 'commentary_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_commentary');
    }
};
