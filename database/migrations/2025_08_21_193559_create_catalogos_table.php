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
        Schema::create('catalogos', function (Blueprint $table) {
            $table->id();

            $table->string('name_handle')->unique();
            $table->string('name');            
            $table->string('divisa')->default('dolar');
            $table->string('description',1000)->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plantilla_id')->default(1)->constrained('plantillas')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->string('banner_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('ubicacion_mapa')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('horario')->nullable();
            $table->foreignId('theme_id')->default(1)->constrained('themes')->onDelete('restrict');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogos');
    }
};
