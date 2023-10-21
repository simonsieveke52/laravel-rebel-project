<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_emails', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quote_id');
            $table->longText('url');
            $table->dateTime('expires_at');
            $table->dateTime('sent_at');
            $table->string('sent_to');
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
        Schema::dropIfExists('quote_emails');
    }
}
