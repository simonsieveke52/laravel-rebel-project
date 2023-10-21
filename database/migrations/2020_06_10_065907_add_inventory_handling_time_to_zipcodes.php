<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInventoryHandlingTimeToZipcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zipcodes', function (Blueprint $table) {
            $table->integer('illinois')->nullable()->default(0)->after('tax_rate');
            $table->integer('maryland')->nullable()->default(0)->after('tax_rate');
            $table->integer('modesto')->nullable()->default(0)->after('tax_rate');
            $table->integer('oklahoma')->nullable()->default(0)->after('tax_rate');
            $table->integer('burley')->nullable()->default(0)->after('tax_rate');
            $table->integer('arizona')->nullable()->default(0)->after('tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zipcodes', function (Blueprint $table) {
            $table->dropColumn([
                'illinois',
                'maryland',
                'modesto',
                'oklahoma',
                'burley',
                'arizona',
            ]);
        });
    }
}
