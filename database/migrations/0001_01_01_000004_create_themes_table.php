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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('primary_font_color')->nullable();
            $table->string('secondary_font_color')->nullable();
            $table->string('catalogo_id')->nullable();
            
            $table->timestamps();
        });

            // Insert default themes
            DB::table('themes')->insert([
                [
                    'name' => 'Default Theme',
                    'primary_color' => '#6d28d9',
                    'secondary_color' => '#fff',
                    'bg_color' => '#eef2ff',
                    'primary_font_color' => '#000000',
                    'secondary_font_color' => '#000000',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Dark Theme',
                    'primary_color' => '#334155',
                    'secondary_color' => '#1e293b',
                    'bg_color' => '#0f172a',
                    'primary_font_color' => '#E0E0E0',
                    'secondary_font_color' => '#a0aec0',
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
        Schema::dropIfExists('themes');
    }
};
