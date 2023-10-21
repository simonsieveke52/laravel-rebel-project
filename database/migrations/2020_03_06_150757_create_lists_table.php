<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
        });

        Schema::create('product_user_list', function (Blueprint $table) {
            $table->unsignedBigInteger('user_list_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('user_list_id')->references('id')->on('user_lists');
            $table->foreign('product_id')->references('id')->on('products');
            $table->primary(['product_id', 'user_list_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_user_list', function (Blueprint $table) {
        $table->dropForeign('product_user_list_user_list_id_foreign');
        $table->dropForeign('product_user_list_product_id_foreign');
        });
        
        Schema::dropIfExists('user_lists');
        Schema::dropIfExists('product_user_list');
    }
}
