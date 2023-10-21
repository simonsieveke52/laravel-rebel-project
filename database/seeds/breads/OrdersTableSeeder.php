<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
     try {
        // \DB::beginTransaction();

        // \DB::table('orders')->delete();

        // \DB::table('orders')->insert(array (
        //         0 => 
        //         array (
        //             'id' => 1,
        //             'name' => 'Judd Franklin',
        //             'email' => 'juddfranklin@gmail.com',
        //             'phone' => '310-310-3103',
        //             'payment_method' => 'paypal',
        //             'transaction_id' => NULL,
        //             'cc_number' => NULL,
        //             'cc_name' => NULL,
        //             'cc_expiration' => NULL,
        //             'cc_expiration_month' => NULL,
        //             'cc_expiration_year' => NULL,
        //             'cc_cvv' => NULL,
        //             'card_type' => NULL,
        //             'gclid' => NULL,
        //             'tax_rate' => '9.500000',
        //             'tax' => '3.230000',
        //             'shipping_cost' => '5.990000',
        //             'subtotal' => '33.950000',
        //             'total' => '43.165250',
        //             'order_status_id' => 1,
        //             'shipping_id' => 1,
        //             'customer_id' => 1,
        //             'confirmed' => 0,
        //             'confirmed_at' => NULL,
        //             'mailed' => NULL,
        //             'mailed_at' => NULL,
        //             'deleted_at' => NULL,
        //             'created_at' => '2020-01-03 03:39:53',
        //             'updated_at' => '2020-01-03 03:39:53',
        //             'discount_id' => NULL,
        //         ),
        //     ));
       } catch(Exception $e) {
         // throw new Exception('Exception occur ' . $e);

         // \DB::rollBack();
       }

       // \DB::commit();
    }
}
