<?php

namespace App\Repositories;

use App\Address;

class AddressRepository extends BaseRepository
{
	/**
	 * Create billing address
	 * 
	 * @param  array  $data       
	 * @param  string $addressType
	 * @return Address
	 */
    public function createBillingAddress(array $data)
    {
    	return Address::create([
            'country_id' => 1,
            'address_1' => $data['billing_address_1'],
            'address_2' => $data['billing_address_2'] ?? null,
            'zipcode' => $data['billing_address_zipcode'],
            'state_id' => $data['billing_address_state_id'],
            'city' => $data['billing_address_city'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'type' => 'billing',
        ]);
    }

  	/**
	 * Create shipping address
	 * 
	 * @param  array  $data       
	 * @param  string $addressType
	 * @return Address
	 */
    public function createShippingAddress(array $data)
    {
    	return Address::create([
            'country_id' => 1,        
            'address_1' => $data['shipping_address_1'],
            'address_2' => $data['shipping_address_2'] ?? null,
            'zipcode' => $data['shipping_address_zipcode'],
            'state_id' => $data['shipping_address_state_id'],
            'city' => $data['shipping_address_city'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'type' => 'shipping',
        ]);
    }
}
