<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineer_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['engineer_profile_id', 'service_request_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineer_ratings');
    }
};
