<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();

            $table->decimal('store_latitude', 10, 7);
            $table->decimal('store_longitude', 10, 7);

            $table->integer('free_radius_km')->default(15);

            $table->decimal('delivery_cost', 8, 2)->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_settings');
    }
};