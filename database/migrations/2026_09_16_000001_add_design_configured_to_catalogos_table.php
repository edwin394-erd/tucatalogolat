<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->boolean('design_configured')->default(false)->after('theme_id');
        });
    }

    public function down(): void
    {
        Schema::table('catalogos', function (Blueprint $table) {
            $table->dropColumn('design_configured');
        });
    }
};
