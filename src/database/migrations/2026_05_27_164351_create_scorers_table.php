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
        Schema::create('scorers', function (Blueprint $table) {

            $table->id();

            $table->string('team', 3)->nullable();
            $table->integer('number')->nullable();

            $table->foreignId('player_id')
                ->unique()
                ->constrained('players')
                ->cascadeOnDelete();

            $table->integer('matches')->nullable();
            $table->integer('goals')->nullable();
            $table->integer('penalties')->nullable();
            $table->integer('own_goals')->nullable();
            $table->integer('minutes_per_goal')->nullable();
            $table->integer('goals_starting')->nullable();
            $table->integer('goals_substitute')->nullable();
            $table->integer('points')->nullable();
            $table->integer('victory_goals')->nullable();
            $table->integer('comeback_goals')->nullable();
            $table->integer('percentage')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scorers');
    }
};