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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            // 2. El código de la ciudad ahora es una clave única para asegurar consistencia
            $table->string('code')->unique();
            
            // 3. Nombre de la ciudad (aumentado a 255 por defecto para evitar restricciones molestas)
            $table->string('name', 30);
            
            // 4. Población de la ciudad (puede ser nulo si no se tienen datos)
            $table->integer('population')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
