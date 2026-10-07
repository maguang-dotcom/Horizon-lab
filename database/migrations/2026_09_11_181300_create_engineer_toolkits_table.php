<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engineer_toolkits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engineer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('item');
            $table->boolean('in_stock')->default(true);
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engineer_toolkits');
    }
};
