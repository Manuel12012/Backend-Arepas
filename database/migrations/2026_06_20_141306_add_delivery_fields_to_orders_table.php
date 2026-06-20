<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->decimal(
                'distance_km',
                8,
                2
            )->nullable();

            $table->boolean(
                'free_delivery'
            )->default(false);

            $table->decimal(
                'delivery_cost',
                10,
                2
            )->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'distance_km',
                'free_delivery',
                'delivery_cost'
            ]);

        });
    }
};
