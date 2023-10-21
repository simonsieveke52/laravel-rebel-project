<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyFromCategoryDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('category_discount');
        Schema::dropIfExists('categories');

        Schema::create('category_discount', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('discount_id')->index();
            $table->unique(['category_id', 'discount_id']);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->string('cover')->nullable();
            $table->text('description')->nullable();
            $table->text('marketing_description')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('on_navbar')->default(true);
            $table->boolean('on_filter')->default(true);
            $table->unsignedSmallInteger('homepage_order')->nullable()->default(NULL);
            $table->unsignedBigInteger('_lft')->default(0);
            $table->unsignedBigInteger('_rgt')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
            $table->timestamps();
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->primary(['product_id', 'category_id']);
        });
    }
}
