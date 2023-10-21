<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptIdToTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_numbers', function (Blueprint $table) {
            $table->unsignedInteger('api_receipt_id')->nullable()->after('lot_number');
            $table->timestamp('api_receipt_created_at')->nullable()->after('lot_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_numbers', function (Blueprint $table) {
            $table->dropColumn(['api_receipt_id', 'api_receipt_created_at']);
        });
    }
}
