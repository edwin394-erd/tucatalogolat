<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('quarterly_offer', 10, 2)->nullable()->after('price');
            $table->decimal('annual_offer', 10, 2)->nullable()->after('quarterly_offer');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['quarterly_offer', 'annual_offer']);
        });
    }
};