<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductScrappedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_scrappeds', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->index()->nullable();

            $table->decimal('current_price', 12, 6)->default(0);
            $table->decimal('price', 12, 6)->default(0);

            $table->string('supplier_website')->nullable();
            $table->string('productUrl')->nullable();
            $table->string('url_key')->index()->nullable();
            $table->string('gtin')->index()->nullable();
            $table->string('upc')->index()->nullable();
            $table->string('sku')->index()->nullable();
            $table->string('scrap_column')->nullable();

            $table->longText('widgets')->nullable();
            $table->longText('recommended_products')->nullable();
            $table->longText('similar_products')->nullable();
            $table->longText('response')->nullable();

            $table->timestamps();
        });

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('scraped_data');
            });
        } catch (\Exception $e) {
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_scrappeds');

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->text('scraped_data')->after('inventory_status')->nullable();
            });
        } catch (\Exception $e) {
            
        }
    }
}
