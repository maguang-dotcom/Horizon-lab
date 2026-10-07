<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('engineer_profiles', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('years_experience');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->timestamp('location_updated_at')->nullable()->after('longitude');
            $table->unsignedTinyInteger('bio_safety_level')->default(0)->after('location_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('engineer_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'location_updated_at',
                'bio_safety_level',
            ]);
        });
    }
};
