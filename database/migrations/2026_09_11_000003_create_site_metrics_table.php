<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        $now = now();
        DB::table('site_metrics')->insert([
            ['key' => 'hospitals', 'value' => '120+', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'engineers', 'value' => '450+', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'uptime', 'value' => '99.4%', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_metrics');
    }
};