<?php

namespace Tests\Feature;

use App\Order;
use App\State;
use App\Customer;
use Tests\TestCase;
use App\Repositories\Locate\LocateApiClient;
use App\Repositories\Locate\LocateRepository;

class LocateApiIntegrationTest extends TestCase
{
    /**
     * @var string
     */
    protected $email = 'ryan@fountainheadme.com';

    /**
     * @var string
     */
    protected $password = 'Cassius#2624';

    /**
     * @return  void
     */
    public function it_can_authentificate_client()
    {
        $client = new LocateApiClient();

        $this->assertTrue($client->isAuth());
    }

    /**
     * @return [type] [description]
     */
    public function it_can_list_search_customer()
    {
        $client = new LocateApiClient();

        $response = $client->setEndpoint('/customer?email=test@fme.com&perPage=1')
            ->makeRequest('GET');

        $this->assertTrue(is_string((string) $response->getBody()));
    }

    /**
     * @return void
     */
    public function it_can_create_customer()
    {
        $order = factory(Order::class)->make();

        $repo = new LocateRepository(new LocateApiClient());

        $response = $repo->createCustomer([
            'customertype_id' => 2,
            'name' => $order->name,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'email' => $order->email,
            'phone' => $order->phone
        ]);

        // $customer->id => 6
        $this->assertTrue(is_string((string) $response->getBody()));
    }

    /**
     * @return void
     */
    public function it_can_create_customer_phone()
    {
        $repo = new LocateRepository(new LocateApiClient());

        $response = $repo->createCustomerPhone(6, '3105551212');

        $this->assertTrue(is_object($response));     
    }

    /**
     * @return void
     */
    public function it_can_create_customer_email()
    {
        $repo = new LocateRepository(new LocateApiClient());

        $response = $repo->createCustomerEmail(6, 'ryan@fountainheadme.com');

        $this->assertTrue(is_object($response));
    }

    /**
     * @return void
     */
    public function it_can_create_customer_address()
    {
        $order = Order::first();

        $repo = new LocateRepository(new LocateApiClient());

        $response = $repo->createCustomerAddress(6, $order->billingAddress->toArray());

        $this->assertTrue(is_object($response));
    }

    /**
     * @return void
     */
    public function it_can_create_customer_from_order()
    {
        $order = Order::first();

        $repo = new LocateRepository(new LocateApiClient());

        $response = $repo->makeRequest("/customer?first_name={$order->first_name}&last_name={$order->last_name}&perPage=1");

        $customer = $response->total > 0 
                ? array_shift($response->data)
                : $repo->createCustomer([
                    'customertype_id' => 2,
                    'name' => $order->name,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name
                ]);

        $repo->createCustomerEmail($customer->id, $order->email);
        $repo->createCustomerPhone($customer->id, $order->phone);
        $repo->createCustomerAddress($customer->id, $order->billingAddress->toArray());

        $this->assertTrue(is_object($customer));
    }

    /**
     * @return  void
     */
    public function it_can_create_api_order()
    {
        $order = Order::first();

        $repo = new LocateRepository(new LocateApiClient());

        $customer = $repo->createCustomerFromOrder($order);

        $order = $repo->createSaleOrder([
            'customer_id' => $customer->id,
            'bill_to_name' => $order->name,
            'bill_address_1' => $order->billingAddress->address_1,
            'bill_address_2' => $order->billingAddress->address_2,
            'bill_city' => $order->billingAddress->city,
            'bill_postal_code' => $order->billingAddress->zipcode,
            'bill_countrydivision_id' => $repo->getStats()->where('abbreviation', $order->billingAddress->state->abv)->first()->id ?? 0,

            'ship_to_name' => $order->name,
            'ship_address_1' => $order->shippingAddress->address_1,
            'ship_address_2' => $order->shippingAddress->address_2,
            'ship_city' => $order->shippingAddress->city,
            'ship_postal_code' => $order->shippingAddress->zipcode,
            'ship_countrydivision_id' => $repo->getStats()->where('abbreviation', $order->shippingAddress->state->abv)->first()->id ?? 0,

            'total_tax' => $order->tax
        ]);

        $this->assertTrue(is_object($order));
    }

    /**
     * @return [type] [description]
     */
    public function it_can_get_all_stats()
    {
        $found = 0;

        $repo = new LocateRepository(new LocateApiClient());

        $stats = $repo->getStats();

        $stats->each(function($state) use (&$found) {

            if (in_array($state->abbreviation, ['AA', 'AE', 'AP'])) {
                return true;
            }

            try {
                State::where('abv', $state->abbreviation)->firstOrFail();
                $found++;
            } catch (\Exception $e) {

            }
        });

        $this->assertTrue($found === 51);
    }

    /**
     * @return void
     */
    public function it_can_add_products_to_api_order()
    {
        $order = Order::first();

        $repo = new LocateRepository(new LocateApiClient());

        $order->api_sales_order_id = 6;

        foreach($order->products as $product) {
            $response = $repo->createProduct($order->api_sales_order_id, [
                'linetype_id' => 1,
                'part_id' => $product->id,
                'qty' => $product->pivot->quantity,
                'unit_price' => $product->price
            ]);
        };

        // add shipping
        $repo->createProduct($order->api_sales_order_id, [
            'linetype_id' => 9,
            'unit_price' => $order->shipping->cost,
            'part_id' => 0,
            'qty' => 1,
        ]);

        $this->assertTrue(is_object($response));
    }

    /**
     * @return
     */
    public function it_can_add_sale_order_from_order()
    {
        $order = Order::first();

        $repo = new LocateRepository(new LocateApiClient());

        $order = $repo->reportSaleOrder($order);

        $this->assertTrue($order->is_reported);
    }

    /**
     * @test
     * @return
     */
    public function it_can_ship_and_track_order()
    {
        $order = Order::first();

        $repo = app(LocateRepository::class);

        $order = $repo->reportSaleOrder($order);

        $response = $repo->createReceipt([
            'order_id' => [$order->api_purchase_order_id],
            'shipping_cost' => 10,
            'tracking_number' => 'test2'
        ]);

        $receiptId = $response->id;

        $response = $repo->makeRequest("receipt/{$receiptId}?embed=lines", "GET", true);

        foreach (data_get($response, 'lines.*') as $line) {
            $repo->markReceiptReceived((int) $line['id'], [
                'qty' => $line['qty']
            ]);
        }

        $response = $repo->makeRequest("/receipt/{$receiptId}/finish", "POST");

        $this->assertTrue($order->is_reported);
    }
}
