<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_type_id');
            $table->integer('order_id');
            $table->string('charge_code',60);
            $table->string('document',20);
            $table->string('client',150);
            $table->text('address');
            $table->string('phone',60);
            $table->string('email',100);
            $table->string('payment_condition',100);
            $table->double('vat', 8, 2);//igv
            $table->double('subtotal', 8, 2);
            $table->double('total', 8, 2);
            $table->double('cash', 8, 2);//efectivo
            $table->double('change', 8, 2);//vuelto
            $table->integer('status_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
