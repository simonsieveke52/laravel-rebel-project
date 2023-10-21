<?php

namespace Tests\Feature;

use App\City;
use App\State;
use App\Product;
use App\Shipping;
use Tests\TestCase;
use App\Repositories\CartRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuestCheckoutTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cartRepository = new CartRepository();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGuestCheckout()
    {
        // given user added 1 random product to cart and filled all info
        // send post request to guest checkout route
        // need to see a new order created in database
        $product = factory(Product::class)->create();
        $state = factory(State::class)->create();
        $city = factory(City::class)->create();
        $shipping = factory(Shipping::class)->create();

        // user added product to cart
        $response = $this->json('POST', route('cart.store'), [
            'id' => $product->id,
            'quantity' => 1
        ]);

        $response->assertStatus(200);

        $orderData = [
            'name' => $this->faker()->name,
            'email' => $this->faker()->email,
            'phone' => '3105551212',
            'shipping_id' => $shipping->id,
        ];

        $addressData = [
            'billing_address_1' => 'test',
            'billing_address_zipcode' => '12345',
            'billing_address_state_id' => $state->id,
            'billing_address_city_id' => $city->id,
        ];

        $response = $this->json('POST', route('guest.checkout.store'), array_merge($orderData, $addressData));

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', $orderData);
    }
}
