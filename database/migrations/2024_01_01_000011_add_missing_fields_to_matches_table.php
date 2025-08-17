<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Add referee field
            $table->string('referee')->nullable()->after('venue');

            // Add current minute for live matches
            $table->integer('current_minute')->nullable()->after('away_score');

            // Add match duration
            $table->integer('duration')->default(90)->after('current_minute');

            // Add weather conditions
            $table->string('weather')->nullable()->after('duration');

            // Add attendance
            $table->integer('attendance')->nullable()->after('weather');

            // Add match officials
            $table->json('officials')->nullable()->after('attendance');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'referee',
                'current_minute',
                'duration',
                'weather',
                'attendance',
                'officials'
            ]);
        });
    }
};
