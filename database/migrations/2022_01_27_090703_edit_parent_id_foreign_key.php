<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditParentIdForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_parent_id_foreign');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_item_id_index');
            $table->dropColumn('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('item_id')->index()->nullable()->after('id');
            $table->string('parent_id')->nullable()->after('item_id');
        });

        Schema::table('products', function(Blueprint $table){
            $table->foreign('parent_id')->on('products')->references('item_id');
        });
    }
}
