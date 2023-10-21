<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirstNameAndLastNameColumnsToSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->enum('status', ['subscribed', 'unsubscribed', 'cleaned', 'pending', 'transactional'])->nullable()->after('subscribe');
            $table->string('ip_address')->nullable()->after('status');
            $table->string('member_id')->nullable()->after('ip_address');
            $table->unsignedInteger('discount_id')->nullable()->after('member_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'status', 'ip_address', 'member_id', 'discount_id']);
        });
    }
}
