<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOriginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_origins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        //Initialize data
        \DB::table('order_origins')->insert([
            ['name' => 'Restaurant'],
            ['name' => 'Foodservice'],
            ['name' => 'Home Use'],
            ['name' => 'Retail'],
            ['name' => 'Other']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_origins');
    }
}
