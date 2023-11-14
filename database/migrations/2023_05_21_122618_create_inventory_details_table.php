<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('inventory_id',11);
            $table->unsignedInteger('product_id',11);
            $table->decimal('purchase_price',11,2);
            $table->decimal('sale_price',11,2);
            $table->decimal('quantity',11,2);

            // Establecer clave externa hacia la tabla 'inventories'
            $table->foreign('inventory_id')->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_details');
    }
};
