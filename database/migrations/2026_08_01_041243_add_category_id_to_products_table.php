<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Crea el campo. 'constrained' asume que tu tabla de categorías se llama 'categories'
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Elimina la relación y luego la columna si se revierte la migración
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
