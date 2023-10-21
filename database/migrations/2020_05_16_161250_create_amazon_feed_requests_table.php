<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmazonFeedRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_feed_requests', function (Blueprint $table) {
            $table->id();
            $table->string('feed_submission_id')->unique()->nullable();
            $table->string('feed_type')->nullable();
            $table->string('feed_processing_status')->nullable();
            $table->longText('response')->nullable();
            $table->timestamp('response_at')->nullable();
            $table->boolean('status')->default(false)->nullable();
            $table->timestamps();
        });

        Schema::create('amazon_feed_request_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('amazon_feed_request_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('amazon_feed_request_id')->references('id')->on('amazon_feed_requests');
            $table->index(['order_id', 'amazon_feed_request_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amazon_feed_request_order');
        Schema::dropIfExists('amazon_feed_requests');
    }
}
