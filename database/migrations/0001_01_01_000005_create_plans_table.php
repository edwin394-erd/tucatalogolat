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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Básico, Premium
            $table->text('description')->nullable();
            $table->text('features')->nullable(); // Lista de características del plan
            $table->decimal('price', 10, 2);
            $table->integer('max_products')->default(100); // Límite de productos permitidos
            $table->integer('duration_in_days')->default(30); // Días que otorga el plan
            $table->boolean('is_active')->default(true);
            $table->boolean('visibility')->default(true); // Si el plan es visible para nuevos usuarios
            $table->timestamps();
        });

        // Insertar planes predeterminados
        DB::table('plans')->insert([
            [
                'name' => 'Free',
                'description' => 'Acceso limitado a funciones, ideal para probar la plataforma.',
                'features' => 'Acceso a 50 productos; Plantillas básicas; Soporte por email',
                'price' => 0.00,
                'max_products' => 50,
                'duration_in_days' => 30,
                'is_active' => true,
                'visibility' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Básico',
                'description' => 'Acceso a funciones básicas, ideal para pequeñas empresas.',
                'features' => 'Acceso a 100 productos; Plantillas básicas; Soporte por email',
                'price' => 9.99,
                'max_products' => 100,
                'duration_in_days' => 30,
                'is_active' => true,
                'visibility' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'description' => 'Acceso a todas las funciones, ideal para empresas en crecimiento.',
                'features' => 'Acceso a 1000 productos; Plantillas premium; Soporte prioritario',
                'price' => 29.99,
                'max_products' => 1000,
                'duration_in_days' => 30,
                'is_active' => true,
                'visibility' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Unlimited',
                'description' => 'Acceso ilimitado a todas las funciones, ideal para grandes empresas.',
                'features' => 'Acceso ilimitado a productos, todas las plantillas, soporte dedicado',
                'price' => 99.99,
                'max_products' => 10000,
                'duration_in_days' => 36500, // 100 años
                'is_active' => true,
                'visibility' => false, // No visible para nuevos usuarios
                'created_at' => now(),
                'updated_at' => now(),

            ],
            

        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
