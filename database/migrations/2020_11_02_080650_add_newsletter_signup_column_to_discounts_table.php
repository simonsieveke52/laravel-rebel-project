<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsletterSignupColumnToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->boolean('newsletter_signup')->default(false)->after('shipping_id');
        });

        $discount = \App\Discount::create([
            'discount_amount'   => config('mailchimp.newsletter.discount'),
            'discount_type'     => 'subtotal',
            'discount_method'   => 'percentage',
            'coupon_code'       => 'NEWSLETTER2020',
            'activation_date'   => now(),
            'expiration_date'   => now(),
            'newsletter_signup' => true,
            'is_active'         => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('newsletter_signup');
        });
    }
}
