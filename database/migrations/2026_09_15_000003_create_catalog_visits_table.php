<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();

            $table->index(['catalogo_id', 'visited_at']);
            $table->index(['catalogo_id', 'session_id', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_visits');
    }
};
