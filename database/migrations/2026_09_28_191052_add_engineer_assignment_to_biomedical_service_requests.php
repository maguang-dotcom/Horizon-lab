<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biomedical_service_requests', function (Blueprint $table) {
            // The engineer (a user) the admin assigned this request to.
            $table->foreignId('engineer_id')->nullable()->after('facility_id')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('biomedical_service_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('engineer_id');
            $table->dropColumn('assigned_at');
        });
    }
};