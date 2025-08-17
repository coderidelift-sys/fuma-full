<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('country')->default('Indonesia');
            $table->integer('capacity');
            $table->string('surface_type')->default('grass'); // grass, artificial, hybrid
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('facilities')->nullable(); // parking, food, etc
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index(['city', 'country']);
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('venues');
    }
};
