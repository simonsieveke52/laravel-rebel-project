<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiResponseColumnToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('api_customer_id')->nullable()->after('mailed_at');
            $table->unsignedInteger('api_sales_order_id')->nullable()->after('mailed_at');
            $table->timestamp('api_requested_at')->nullable()->after('api_customer_id');
            $table->boolean('is_reported')->default(false)->after('api_requested_at');
            $table->timestamp('reported_at')->nullable()->after('is_reported');
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
            $table->dropColumn(['api_customer_id', 'api_sales_order_id', 'api_requested_at', 'is_reported', 'reported_at']);
        });
    }
}
