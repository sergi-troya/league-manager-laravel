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
        Schema::create('goalkeepers', function (Blueprint $table) {

            $table->id();

            $table->string('team',3)->nullable();
            $table->integer('number')->nullable();

            $table->foreignId('player_id')
                ->unique()
                ->constrained('players')
                ->cascadeOnDelete();

            $table->integer('matches')->nullable();
            $table->integer('goals')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goalkeepers');
    }
};