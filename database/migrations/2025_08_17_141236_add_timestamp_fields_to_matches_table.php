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
        Schema::table('matches', function (Blueprint $table) {
            // Add missing timestamp fields for match lifecycle
            $table->timestamp('started_at')->nullable()->after('status');
            $table->timestamp('paused_at')->nullable()->after('started_at');
            $table->timestamp('resumed_at')->nullable()->after('paused_at');
            $table->timestamp('completed_at')->nullable()->after('resumed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'started_at',
                'paused_at',
                'resumed_at',
                'completed_at'
            ]);
        });
    }
};
