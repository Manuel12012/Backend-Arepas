<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Primero creamos un índice normal para que
            // la foreign key siga teniendo un índice disponible.
            $table->index('offer_id', 'products_offer_id_index');

            // Ahora podemos eliminar el UNIQUE.
            $table->dropUnique('products_offer_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Volvemos a hacer offer_id UNIQUE.
            $table->unique('offer_id', 'products_offer_id_unique');

            // Eliminamos el índice normal.
            $table->dropIndex('products_offer_id_index');
        });
    }
};
