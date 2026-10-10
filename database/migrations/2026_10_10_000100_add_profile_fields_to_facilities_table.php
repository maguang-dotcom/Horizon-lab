<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('duration_of_operation')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('website')->nullable();
            $table->text('bio')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['email', 'duration_of_operation', 'contact_person', 'website', 'bio']);
        });
    }
};
