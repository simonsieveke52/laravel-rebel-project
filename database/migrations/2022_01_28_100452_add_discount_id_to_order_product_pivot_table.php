<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountIdToOrderProductPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('order_product', 'discount_id')) {
            return true;
        }

        Schema::table('order_product', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_id')->index()->nullable()->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('order_product', 'discount_id')) {
            Schema::table('order_product', function (Blueprint $table) {
                $table->dropColumn('discount_id');
            });
        }
    }
}
