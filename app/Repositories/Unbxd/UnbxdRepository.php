<?php

namespace App\Repositories\Unbxd;

use App\Product;
use Illuminate\Support\Str;

class UnbxdRepository
{
	/**
	 * api base url
	 * 
	 * @var string
	 */
	public static $baseUrl = 'http://search.unbxd.io';

	/**
	 * @var string
	 */
	protected $endPoint;

	/**
	 * @param string $accessKey
	 * @param string $secretKey
	 */
	public function __construct()
	{
		$this->client = new \GuzzleHttp\Client([
			'base_uri' => static::$baseUrl,
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			]
		]);
	}

	/**
	 * @return string
	 */
	public function getEndpoint()
	{
		$endPoint = $this->endPoint;

		if (! Str::startsWith($endPoint, '/')) {
			$endPoint = '/' . $endPoint;
		}

		return $endPoint;
	}

	/**
	 * @param string $endPoint
	 * 
	 * @return self
	 */
	public function setEndpoint(string $endPoint)
	{
		$this->endPoint = $endPoint;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return self::$baseUrl . $this->getEndpoint();
	}

	/**
	 * Make http request API
	 * 
	 * @param  string $method 
	 * @param  array  $headers
	 * 
	 * @return GuzzleHttp\Psr7\Response
	 */
	public function makeRequest(string $method = 'GET', array $headers = [])
	{
		return $this->client->request(
			strtoupper($method), $this->getEndpoint(), $headers
		);
	}

	/**
	 * Make http request API
	 * 
	 * @param  Product $product
	 * @param  string  $key
	 * @param  array  $fields
	 * 
	 * @return mixed
	 */
	public function scrape(Product $product, string $key = 'sku', array $fields = ['name', 'price', 'url_key', 'url_key'])
	{
		if (! isset($product->{$key})) {
			return [];
		}

		$response = file_get_contents(
			static::$baseUrl . '/ed39b885dbb44eca3c2782623eb2ba9d/www-foodservicedirect-com-805741548149387/search?q='.( $product->{$key} ).'&version=V2&inFields.count=1&popularProducts.count=1&keywordSuggestions.count=1&topQueries.count=1&promotedSuggestion.count=1&popularProducts.fields='. implode(',', $fields) .'&variants=false'
		);

		$response = json_decode($response);

		if (isset($response->response) && $response->response->numberOfProducts > 0) {
			return isset($response->response->products) ? $response->response->products : [];
		}

		return [];
	}

	/**
	 * @return mixed
	 */
	public function getProductRecommendation(string $sku)
	{
		try {
			$client = new \GuzzleHttp\Client([
				'base_uri' => 'https://www.foodservicedirect.com/',
				'headers' => [
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				]
			]);

			$response = $client->request(
				'POST', 'graphql', [
				    'json' => [
				    	'query' => '{products : recommended_products(skus_query:"sku='.$sku.'",limit:4){
				                title : name
				                sku
				                uniqueId :sku
				                image
				                price :final_price
				                old_price : price
				                special_price
				                ships_in
				                productUrl :url
				            }
				        }'
				    ]
				]
			);
			return json_decode((string) $response->getBody());
		} catch (\Exception $e) {
			return [];
		}
	}

	/**
	 * @param  Product $product
	 * @return array
	 */
	public function getScrapedData(Product $product)
	{
		try {

            $scrapeColumn = strlen($product->upc) === 0 ? 'vendor_code' : 'upc';

            $response = $this->scrape($product, $scrapeColumn);

            if (count($response) === 0 && strlen($product->upc) !== 0) {
                $response = $this->scrape($product, 'vendor_code');
                $scrapeColumn = 'vendor_code';
            }

            $references = collect($response)->pluck('fsd_product_reference_unbxdaq')->flatten();

            if (count($response) > 1) {
                $index = $references->search("OTF{$product->vendor_code}") !== false 
                    ? $references->search("OTF{$product->vendor_code}")
                    : $references->search("OT{$product->vendor_code}");

                $response = $index !== false
                    ? collect($response)->get($index) 
                    : (Object) [];

                $scrapeColumn = $index !== false 
                    ? 'vendor_code'
                    : 'Not found';
            }

            $response = is_array($response) 
                ? $response[0] ?? (Object) [] 
                : $response;

            if (! isset($response->price)) {
            	return [];
            }
            
            if (! isset($response->productUrl)) {
            	return [];
            }

            $widgets = isset($response->sku) && trim($response->sku) !== '' 
            	? file_get_contents('https://recommendations.unbxd.io/v2.0/ed39b885dbb44eca3c2782623eb2ba9d/www-foodservicedirect-com-805741548149387/items?pageType=PRODUCT&uid=uid-1596404554932-43859&id='.$response->sku.'&json.wrf=__jp0') 
            	: json_encode([]);

            $widgets = str_replace(['__jp0(', ');'], '', $widgets);

            return [
            	'product_id' => $product->id,
                'current_price' => $product->price,
                'price' => isset($response->price) ? $response->price : 'Not found',
                'supplier_website' => isset($response->supplier_website) ? $response->supplier_website : 'Not found',
                'productUrl' => isset($response->productUrl) ? $response->productUrl : 'Not found',
                'url_key' => isset($response->url_key) ? $response->url_key : 'Not found',
                'gtin' => isset($response->gtin) ? $response->gtin : 'Not found',
                'upc' => isset($response->upc) ? $response->upc : 'Not found',
                'sku' => isset($response->sku) ? $response->sku : 'Not found',
                'scrap_column' => $scrapeColumn,

				'response' => $response,

                // People Also Viewed
                'widgets' => json_decode($widgets),

                // Customers Also Bought
                'recommended_products' => $this->getProductRecommendation($response->sku),

                // Similar Products
                'similar_products' => json_decode(file_get_contents("https://www.foodservicedirect.com/rest/V1/fsd/product-similarproducts/{$response->sku}")),
            ];
        } catch (\Exception $exception) {
            return [];
        }
	}
}