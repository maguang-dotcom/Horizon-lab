<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineer_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('oem_scope')->nullable();
            $table->string('admin_status')->default('pending');
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineer_credentials');
    }
};
