<?php

namespace FME\Amazon;

use App\Order;
use App\State;
use App\Address;
use App\Product;
use App\Shipping;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use FME\Amazon\Client\MWSClient;
use FME\Amazon\Client\MWSEndPoint;
use Illuminate\Support\Collection;

class AmazonRepository
{
	/**
	 * @var MWSClient
	 */
	protected $client;

	public function __construct()
	{
		$this->client = new MWSClient([
		    'Marketplace_Id' => config('amazon-mws.marketplace_id'),
		    'Seller_Id' => config('amazon-mws.seller_id'),
		    'Access_Key_ID' => config('amazon-mws.access_key_id'),
		    'Secret_Access_Key' => config('amazon-mws.secret_access_key'),
		    'MWSAuthToken' => config('amazon-mws.MWSAuthToken')
		]);
	}

	/**
	 * @return MWSClient
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * @param  string $amazonOrderId
	 * @return StdClass
	 */
	public function getOrder(string $amazonOrderId)
	{
		return $this->client->GetOrder($amazonOrderId);
	}
	
	/**
	 * Get Orders from API
	 */
	public function listOrders(
		\DateTime $from = null, 
		bool $allMarketplaces = false, 
		array $states = ['Unshipped', 'PartiallyShipped'], 
		array $fulfillmentChannels = ['MFN'],
		\DateTime $till = null
	)
	{
		if (is_null($from)) {
			$from = now()->subDay();
		}

		$response = $this->client->ListOrders(
			$from, $allMarketplaces, $states, $fulfillmentChannels, $till
		);

		$list = isset($response['ListOrders']) 
			? $response['ListOrders'] 
			: $response;

		$nextToken = $response['NextToken'] ?? null;

		while ($nextToken !== null && trim($nextToken) !== '') {

			sleep(mt_rand(62, 66));

			try {
				$response = $this->client->ListOrdersByNextToken($nextToken);
				$orders = isset($response['ListOrders']) 
					? $response['ListOrders'] 
					: $response;
				$list = array_merge($list, $orders);
			} catch (\Exception $e) {

			}

			$nextToken = $response['NextToken'] ?? null;
		}

		return $list;
	}

	/**
	 * Create order from returned amazon order
	 * 
	 * @param  array $data
	 * @return Order
	 */
	public function createOrder($data)
	{
		if (strtotime($data['PurchaseDate']) === false) {
			throw new \Exception("Invalid PurchaseDate");
		}

        $order = Order::create([
        	'name' 				  => $data['BuyerName'] ?? '',
        	'phone' 			  => $data['ShippingAddress']['Phone'] ?? '',
            'email'               => $data['BuyerEmail'],
            'created_at'          => $data['PurchaseDate'],
            'payment_method'      => $data['PaymentMethod'],
            'type'                => 'amazon',
            'amazon_order_id'     => $data['AmazonOrderId'],
            'order_status_id'     => 1,
            'total' 			  => $data['OrderTotal']['Amount'] ?? 0,
        ]);

        try {
        	$order->created_at = Carbon::createFromTimestampUTC(strtotime($data['PurchaseDate']))->setTimezone(config('app.timezone'));
	        $order->confirmed = true;
	        $order->confirmed_at = now();
	        $order->save();
        } catch (\Exception $e) {
        	logger($e->getMessage());
        }

        try {
	        $this->buildOrderDetails($order);
        } catch (\Exception $e) {

        }

        try {
	        $this->createAddress($order, $data, 'billing');
        } catch (\Exception $e) {
        	logger($e->getMessage());
        }

        try {
	        $this->createAddress($order, $data, 'shipping');
        } catch (\Exception $e) {
        	logger($e->getMessage());
        }

        return $order;
	}

	/**
	 * @param  Collection $products
	 * @return void
	 */
	public function updateProducts(Collection $products) 
	{
		$array = [];

		foreach ($products as $product) {

			if (trim($product->sku) === '') {
				continue;
			}

			$array[] = [
				'sku' => $product->sku, 'quantity' => $product->quantity, 'latency' => $product->latency
			];
		}

		if (empty($array)) {
			throw new \Exception("No products available to submit");
		}

		return $this->client->updateStockWithFulfillmentLatency($array);
	}

	/**
	 * @param  Order  $order
	 * @param  array $data 
	 * @param  string $type 
	 * @return Address       
	 */
	protected function createAddress(Order $order, $data, $type = 'billing')
	{
		try {
			$state = State::where('abv', $data['ShippingAddress']['StateOrRegion'] ?? '')->firstOrFail();
			$stateId = $state->id;
		} catch (\Exception $e) {
			$stateId = null;
		}

		return Address::create([
            'country_id' => 1,
            'order_id' => $order->id,
            'address_1' => $data['ShippingAddress']['AddressLine1'] ?? '',
            'address_2' => $data['ShippingAddress']['AddressLine2'] ?? '',
            'zipcode' => $data['ShippingAddress']['PostalCode'] ?? '',
            'state_id' => $stateId,
            'city' => $data['ShippingAddress']['City'] ?? null,
            'type' => $type,
        ]);
	}

    /**
     * Update tracking number
     *
     * @param array $array array containing AmazonOrderID as key and array as values
     * @return array feed submission result
     */
    public function updateTrackingNumber(array $array = [])
    {
        $feed = [
            'MessageType' => 'OrderFulfillment',
            'Message' => []
        ];

        foreach ($array as $parcel) {
        	
            if (empty($parcel['ShipperTrackingNumber']) || is_null($parcel['CarrierCode'])) {
            	continue;
            }

            $feed['Message'][] = [
                'MessageID' => rand(),
                'OperationType' => 'Update',
                'OrderFulfillment' => [
                    'AmazonOrderID' => $parcel['AmazonOrderID'],
                    'FulfillmentDate' => $parcel['FulfillmentDate'],
                    'FulfillmentData' => [
                        'CarrierCode' => $parcel['CarrierCode'],
                        'ShippingMethod' => $parcel['ShippingMethod'],
                        'ShipperTrackingNumber' => $parcel['ShipperTrackingNumber']
                    ]
                ]
            ];
        }

        if (empty($feed['Message'])) {
        	throw new \Exception('Empty feed details');
        }

        return $this->client->SubmitFeed('_POST_ORDER_FULFILLMENT_DATA_', $feed, false);
    }

	/**
	 * @param  Collection  $orders
	 * @return array
	 */
	public function prepareTrackingNumbers($orders)
	{
		$array = [];

		if ($orders instanceOf Order) {

			foreach ($orders->trackingNumbers as $tracking) {
	            
	            $array[] = [
	                'AmazonOrderID' => $orders->amazon_order_id,
	                'ShipperTrackingNumber' => $tracking->number,
	                'CarrierCode' => $tracking->carrierCode,
	                'FulfillmentDate' => now()->format('Y-m-d\TH:i:s.\\0\\0\\0\\Z'),
	                'ShippingMethod' => $tracking->name
	            ];
	        }

	        return $array;
		}

		foreach ($orders as $order) {

			if (! $order instanceof Order) {
				continue;
			}

	        foreach ($order->trackingNumbers as $tracking) {
	            $array[] = [
	                'AmazonOrderID' => $order->amazon_order_id,
	                'ShipperTrackingNumber' => $tracking->number,
	                'CarrierCode' => $tracking->carrierCode,
	                'FulfillmentDate' => now()->format('Y-m-d\TH:i:s.\\0\\0\\0\\Z'),
	                'ShippingMethod' => $tracking->name
	            ];
	        }
		}

        return $array;
	}

	/**
	 * @return void
	 */
	public function buildOrderDetails(Order $order)
	{
		$orderItems = $this->client->ListOrderItems($order->amazon_order_id);

		$order->subtotal = 0;

		collect($orderItems)->each(function ($item) use (&$order) {
			try {
	            $product = Product::withoutGlobalScopes()->where('sku', $item['SellerSKU'])->firstOrFail();
	            $order->products()->attach($product, [
	                'quantity' => $item['QuantityOrdered'], 
	                'price'    => $item['ItemPrice']['Amount'] ?? $product->price,
	                'options'  => json_encode($item)
	            ]);
	            $order->subtotal += ($item['ItemPrice']['Amount'] ?? $product->price) * $item['QuantityOrdered'];
			} catch (\Exception $e) {
				logger($e->getMessage());
			}
        });

		$frozen = $order->products()->get()->values()->pluck('frozen')->unique()->values()->toArray();

		$shippings = Shipping::whereIn('is_frozen', Arr::wrap($frozen))->get();

		foreach ($shippings as $shipping) {
			$order->shippings()->attach($shipping, [ 
	            'cost'    => 0,
	            'is_frozen'  => $shipping->is_frozen
	        ]);
		}

		$order->save();

        return $order;
	}

	/**
	 * @param  Order  $order 
	 * @param  string $action
	 * 
	 * @return mixed
	 */
	public function createFulfillmentOrder(Order $order, string $action = 'Ship')
	{
		if (! in_array($action, ['Ship', 'Hold'])) {
			throw new \Exception("Only Ship and Hold actions are supported.");
		}

		if ($order->products->where('is_fba', true)->isEmpty()) {
			throw new \Exception("No FBA products on this order.");
		}
		
		$query = [
			'MarketplaceId' => config('amazon-mws.marketplace_id'),
			'SellerFulfillmentOrderId' => $order->id,
			'FulfillmentAction' => $action,
			'DisplayableOrderId' => $order->id,
			'DisplayableOrderDateTime' => gmdate(MWSClient::DATE_FORMAT, $order->created_at->getTimestamp()),
			'DisplayableOrderComment' => $order->comment ?? 'Thank you for your order.',
			'ShippingSpeedCategory' => 'Standard',
			'DestinationAddress.Name' => $order->name,
			'DestinationAddress.Line1' => $order->shippingAddress->address_1,
			'DestinationAddress.Line2' => $order->shippingAddress->address_2,
			'DestinationAddress.City' => $order->shippingAddress->city,
			'DestinationAddress.StateOrProvinceCode' => $order->shippingAddress->state->abv,
			'DestinationAddress.CountryCode' => $order->shippingAddress->country->iso,
			'DestinationAddress.PostalCode' => $order->shippingAddress->zipcode,
			'DestinationAddress.PhoneNumber' => $order->phone,
		];

		foreach ($order->products->where('is_fba', true)->values() as $index => $product) {
			$key = $index + 1;
			$query["Items.member.{$key}.SellerSKU"] = $product->sku;
			$query["Items.member.{$key}.SellerFulfillmentOrderItemId"] = $product->id;
			$query["Items.member.{$key}.Quantity"] = $product->pivot->quantity;
		}

		$response = $this->client->CreateFulfillmentOrder($query);

		$order->amazon_fulfillment_channel = 'AFN';
		$order->save();

		if (isset($response['ResponseMetadata']) && isset($response['ResponseMetadata']['RequestId'])) {
			return $response['ResponseMetadata']['RequestId'];
		}

		return $response;
	}

    /**
     * Handle dynamic method calls into the repository.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
    	$availableEndpoints = array_keys(MWSEndPoint::$endpoints);

    	if (in_array($method, $availableEndpoints)) {
    		return $this->client->$method(...$parameters);
    	}

    	if (in_array(ucfirst($method), $availableEndpoints)) {
    		return $this->client->$method(...$parameters);
    	}

    	throw new \Exception("Undefined method {$method}");
    }

    /**
     * Handle dynamic static method calls into the repository.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}