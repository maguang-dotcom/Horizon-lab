<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('form_ref')->unique();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->string('problem_classification');
            $table->text('diagnostic_notes');
            $table->string('urgency_tier')->default('standard');
            $table->unsignedSmallInteger('sla_minutes')->nullable();
            $table->string('status')->default('matching');
            $table->foreignId('assigned_engineer_profile_id')
                ->nullable()
                ->constrained('engineer_profiles')
                ->nullOnDelete();
            $table->timestamp('matched_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
