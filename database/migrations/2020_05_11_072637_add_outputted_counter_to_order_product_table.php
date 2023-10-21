<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutputtedCounterToOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->boolean('was_user_outputted')->default(false)->nullable()->after('was_outputted_at');
            $table->timestamp('user_outputted_at')->nullable()->after('was_user_outputted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn([
                'was_user_outputted',
                'user_outputted_at',
            ]);
        });
    }
}
