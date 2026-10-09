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
        Schema::table('engineer_profiles', function (Blueprint $table) {
            $table->string('phone', 30)->nullable();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('service_radius_km')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('engineer_profiles', function (Blueprint $table) {
            $table->dropColumn(['phone', 'location', 'service_radius_km', 'bio', 'photo_path']);
        });
    }
};
