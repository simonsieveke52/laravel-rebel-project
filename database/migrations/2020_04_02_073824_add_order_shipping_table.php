<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_frozen');
            $table->dropForeign('orders_shipping_id_foreign');
            $table->dropColumn('shipping_id');
        });

        Schema::create('order_shipping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('shipping_id')->nullable();
            $table->decimal('cost', 12, 6)->default(0);
            $table->boolean('is_frozen')->default(false);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('shipping_id')->references('id')->on('shippings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shipping');
    }
}
