<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('plantillas')->insert([
            'name' => 'Menú Restaurante',
            'description' => 'Una plantilla enfocada en menús de comida, con secciones claras para platos, recomendaciones y categorías de menú.',
            'image_url' => 'plantilla3.svg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('plantillas')->where('name', 'Menú Restaurante')->delete();
    }
};
