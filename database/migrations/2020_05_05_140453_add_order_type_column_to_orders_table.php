<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderTypeColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('type', ['direct', 'amazon'])->default('direct')->after('gclid');
            $table->string('amazon_order_id')->after('type')->index()->nullable()->default(null);
            $table->unsignedBigInteger('discount_id')->nullable()->default(null)->after('tax')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'amazon_order_id']);
        });
    }
}
