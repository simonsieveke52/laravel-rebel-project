<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReviewColumnToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('should_review')->default(false)->after('mailed_at')->nullable();
            $table->boolean('reviewed')->default(false)->after('should_review')->nullable();
            $table->timestamp('reviewed_at')->after('reviewed')->nullable();
            $table->boolean('refunded')->default(false)->after('reviewed_at')->nullable();
            $table->timestamp('refunded_at')->after('refunded')->nullable();
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
            $table->dropColumns([
                'should_review',
                'reviewed',
                'reviewed_at',
                'refunded',
                'refunded_at',
            ]);
        });
    }
}
