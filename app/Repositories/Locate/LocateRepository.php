<?php

namespace App\Repositories\Locate;

use App\Order;
use App\Repositories\Locate\LocateApiClient;

class LocateRepository
{
	/**
	 * @var LocateApiClient
	 */
	protected $client;

	/**
	 * @var collection
	 */
	protected $stats;

	/**
	 * @param LocateApiClient $client
	 */
	public function __construct(LocateApiClient $client)
	{
		$this->client = $client;
		$this->stats = collect();
	}

	/**
	 * @param  string $endPoint
	 * @param  string $method  
	 * @return mixed          
	 */
	public function makeRequest($endPoint, $method = 'get', $toArray = false)
	{
		$response = $this->client->setEndpoint($endPoint)
			->makeRequest($method);

		return json_decode((string) $response->getBody(), $toArray);
	}

	/**
	 * @return collection
	 */
	public function getStats()
	{
		if (! $this->stats->isEmpty()) {
			return $this->stats;
		}

		$response = $this->client->setEndpoint("/country/233/division")->makeRequest('get');

		$stats = json_decode((string) $response->getBody());	

		$this->stats = collect((array) $stats);

		return $this->stats;
	}

	/**
	 * @param  int $saleOrderId
	 * @return int             
	 */
	public function getPurchaseOrderId(int $saleOrderId)
	{
		$response = $this->makeRequest("salesorder/{$saleOrderId}/line?embed=purchaseorderline", "GET", true);

        return collect(data_get($response, '*.purchaseorderline.purchaseorder_id', null))->reject(function($item) {
	            return is_null($item);
	        })
			->unique()
			->first();
	}

	/**
	 * @param  array  $data       
	 * @return 
	 */
	public function createCustomer(array $data = [])
	{
		$response = $this->client->setEndpoint('/customer')
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

        return json_decode((string) $response->getBody());
	}

	/**
	 * @param  array  $data       
	 * @return 
	 */
	public function createReceipt(array $data = [])
	{
		$response = $this->client->setEndpoint('/receiptlinebyorder')
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

        return json_decode((string) $response->getBody());
	}

	/**
	 * @param  array  $data       
	 * @return 
	 */
	public function markReceiptReceived(int $receiptLineId, array $data = [])
	{
		$response = $this->client->setEndpoint("/receiptline/{$receiptLineId}/receive")
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

        return json_decode((string) $response->getBody());
	}

	/**
	 * @param  integer $customerId 
	 * @param  string  $email      
	 * @param  integer $emailTypeId
	 * @return string
	 */
	public function createCustomerEmail(int $customerId, string $email, $emailTypeId = 2)
	{
		$response = $this->client->setEndpoint("/customer/{$customerId}/email")
            ->makeRequest('POST', [
            	'form_params' => [
            		'email_address' => $email,
            		'emailtype_id' => $emailTypeId
            	]
            ]);

      	return json_decode((string) $response->getBody());
	}

	/**
	 * @param  integer $customerId 
	 * @param  string  $email      
	 * @param  integer $emailTypeId
	 * @return string
	 */
	public function createCustomerPhone(int $customerId, string $phone, $phoneTypeId = 3)
	{
		$response = $this->client->setEndpoint("/customer/{$customerId}/phone")
            ->makeRequest('POST', [
            	'form_params' => [
            		'phone_number' => $phone,
            		'phonetype_id' => $phoneTypeId
            	]
            ]);

      	return json_decode((string) $response->getBody());
	}

	/**
	 * @param  integer $customerId 
	 * @param  string  $email      
	 * @param  integer $emailTypeId
	 * @return string
	 */
	public function createCustomerAddress(int $customerId, array $data, $addressTypeId = 4)
	{
		$data['addresstype_id'] = $addressTypeId;

		if (! isset($data['customer_id'])) {
			$data['customer_id'] = $customerId;
		}

		if (isset($data['zipcode']) && is_string($data['zipcode'])) {
			$data['postal_code'] = $data['zipcode'];
		}

		$response = $this->client->setEndpoint("/customer/{$customerId}/address")
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

      	return json_decode((string) $response->getBody());
	}

	/**
	 * @param  Order  $order 
	 * @return void
	 */
	public function createCustomerFromOrder(Order $order)
	{
		try {
			$response = $this->makeRequest("/customer?first_name={$order->first_name}&last_name={$order->last_name}&perPage=1");
	        $customer = $response->total > 0 
                ? array_shift($response->data)
                : $this->createCustomer([
                    'customertype_id' => 2,
                    'name' => $order->name,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name
                ]);
		} catch (\Exception $e) {
			$customer = $this->createCustomer([
                'customertype_id' => 2,
                'name' => $order->name,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name
            ]);
		}

        $this->createCustomerEmail($customer->id, $order->email);
        $this->createCustomerPhone($customer->id, $order->phone);
        $this->createCustomerAddress($customer->id, $order->billingAddress->toArray());

        return $customer;
	}

	/**
	 * @param  array  $data       
	 * @return 
	 */
	public function createSaleOrder(array $data = [])
	{
		$response = $this->client->setEndpoint('/salesorder')
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

        return json_decode((string) $response->getBody());
	}

	/**
	 * @param  array  $data       
	 * @return 
	 */
	public function createProduct(int $saleOrderId, array $data = [])
	{
		$response = $this->client->setEndpoint("/salesorder/{$saleOrderId}/line")
            ->makeRequest('POST', [
            	'form_params' => $data
            ]);

        return json_decode((string) $response->getBody());
	}

	/**
	 * @param  Order  $order
	 * @return Order
	 * @throws \Exception
	 */
	public function reportSaleOrder(Order $order)
	{
		$order->api_requested_at = date('Y-m-d H:i:s');

		try {
	        $customer = $this->createCustomerFromOrder($order);
	        $order->api_customer_id = $customer->id;
		} catch (\Exception $execption) {
	        $order->save();
			throw $execption;
		}

		try {

			$saleOrderData = [
	            'customer_id' => $order->api_customer_id,
	            'bill_to_name' => $order->name,
	            'bill_address_1' => $order->billingAddress->address_1,
	            'bill_address_2' => $order->billingAddress->address_2,
	            'bill_city' => $order->billingAddress->city,
	            'bill_postal_code' => $order->billingAddress->zipcode,
	            'ship_to_name' => $order->name,
	            'ship_address_1' => $order->shippingAddress->address_1,
	            'ship_address_2' => $order->shippingAddress->address_2,
	            'ship_city' => $order->shippingAddress->city,
	            'ship_postal_code' => $order->shippingAddress->zipcode,
	            'total_tax' => $order->tax
	        ];

			try {
				$value = $this->getStats()->where('abbreviation', $order->shippingAddress->state->abv)->first()->id ?? 0;
				if ((int) $value !== 0) {
					$saleOrderData['ship_countrydivision_id'] = $value;
				}
			} catch (\Exception $e) {
				
			}

			try {
				$value = $this->getStats()->where('abbreviation', $order->billingAddress->state->abv)->first()->id ?? 0;
				if ((int) $value !== 0) {
					$saleOrderData['bill_countrydivision_id'] = $value;
				}
			} catch (\Exception $e) {
				
			}

	        $saleOrder = $this->createSaleOrder($saleOrderData);

	        $order->api_sales_order_id = $saleOrder->id;

	        foreach($order->products as $product) {
	            $this->createProduct($saleOrder->id, [
	                'linetype_id' => 1,
	                'part_id' => $product->id,
	                'qty' => $product->pivot->quantity,
	                'unit_price' => $product->price,
	            ]);
	        };

	        $this->createProduct($saleOrder->id, [
	            'linetype_id' => 9,
	            'part_id' => 45267,
	            'qty' => 1,
	            'unit_price' => $order->shipping_cost
	        ]);

	        $this->makeRequest("/salesorder/{$saleOrder->id}/issue", "post");

	        $order->api_purchase_order_id = $this->getPurchaseOrderId($saleOrder->id);

	        $this->makeRequest("/purchaseorder/{$order->api_purchase_order_id}/issue", "post");

	        return tap($order, function($order) {
				$order->is_reported = true;
				$order->reported_at = date('Y-m-d H:i:s');
				$order->save();
			});
	        
		} catch (\Exception $execption) {
			$order->save();
			throw $execption;
		}
	}

}