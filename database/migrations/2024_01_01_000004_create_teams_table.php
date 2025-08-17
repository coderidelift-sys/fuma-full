<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('city');
            $table->string('country')->default('Indonesia');
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('manager_email')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('trophies_count')->default(0);
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Migration untuk menambah field yang kurang
        Schema::table('teams', function (Blueprint $table) {
            $table->string('short_name')->nullable()->after('name');
            $table->integer('founded_year')->nullable()->after('logo');
            $table->string('nickname')->nullable()->after('founded_year');
            $table->string('stadium')->nullable()->after('nickname');
            $table->string('primary_color')->nullable()->after('stadium');
            $table->string('secondary_color')->nullable()->after('primary_color');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->string('capacity')->nullable()->after('stadium');
            $table->string('website')->nullable()->after('secondary_color');
            $table->string('status')->default('active')->after('website');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
