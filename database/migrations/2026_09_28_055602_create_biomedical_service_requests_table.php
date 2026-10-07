<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biomedical_service_requests', function (Blueprint $table) {
            $table->id();

            // Facility submitting the request. Assumes a `facilities` table/model
            // already exists from the approval workflow project.
            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            $table->string('equipment_name');          // e.g. "Infant Incubator"
            $table->string('equipment_model')->nullable();
            $table->string('serial_number')->nullable();

            $table->enum('service_type', [
                'repair',
                'preventive_maintenance',
                'calibration',
                'installation',
                'other',
            ]);

            $table->enum('urgency', ['low', 'normal', 'high', 'critical'])
                ->default('normal');

            $table->text('issue_description');
            $table->date('preferred_date')->nullable();

            $table->enum('status', [
                'pending',
                'assigned',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biomedical_service_requests');
    }
};
