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
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

            // Insert default templates
            DB::table('plantillas')->insert([
                [
                    'name' => 'Plantilla 1',
                    'description' => 'Una plantilla moderna y limpia, ideal para restaurantes y cafeterías.',
                    'image_url' => 'plantilla1.jpg',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Plantilla 2',
                    'description' => 'Una plantilla elegante y sofisticada, perfecta para boutiques y tiendas de lujo.',
                    'image_url' => 'plantilla2.jpg',
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
        Schema::dropIfExists('plantillas');
    }
};
