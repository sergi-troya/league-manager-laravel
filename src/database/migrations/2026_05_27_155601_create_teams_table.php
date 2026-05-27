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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            // 2. El codi original VARCHAR(3) NOT NULL, ara com a camp únic
            $table->string('code', 3)->unique();
            
            // 3. Camps de text opcionals (nullable) amb les seves longituds originals
            $table->string('short_name', 20)->nullable();
            $table->string('full_name', 40)->nullable();
            
            // 4. Clau forana que es connecta amb el camp 'id' de la taula 'cities'
            // El mètode foreignId defineix un BIGINT i constrained busca la taula 'cities' per defecte
            $table->foreignId('city_id')->nullable()->constrained('cities');
            
            // 5. Altres camps de text i configuració original
            $table->string('coach', 30)->nullable();
            $table->string('stadium', 30)->nullable();
            $table->string('brand', 30)->nullable();
            $table->string('sponsor', 30)->nullable();
            
            // 6. Pressupost (INT NULL)
            $table->integer('budget')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
