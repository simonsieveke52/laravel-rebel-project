<?php 

namespace FME\EloquenceCsvFeed\Handlers;

use App\Product;
use App\Scopes\EnabledScope;
use \FME\EloquenceCsvFeed\Helper;
use \FME\EloquenceCsvFeed\Base\EloquenceCsvFeedHandler;

class ProductSecondaryHandler extends EloquenceCsvFeedHandler
{
	/**
	 * @param $model model name
	 * @param $startAt limit from
	 * @param $endAt limit ends
	 */
	public function __construct($model = null)
	{
		$this->model = $model;
	}

	/**
	 * Set feed to feed attribute
	 * 
	 * @return [type] [description]
	 */
	public function handle() : void
	{
		$data = [];
		$fh = fopen('php://output', 'w');

		$query = Product::with(['images','brand', 'categories'])
			->withoutGlobalScopes([EnabledScope::class]);

		if ($this->hasOffset()) {
			$query = $query->offset((int)$this->offset);
		}

		if ($this->hasLimit()) {
			$query = $query->limit((int)$this->limit);
		}

		if ($this->hasOffset() && !$this->hasLimit()) {
			$limit = Product::count();
			$query = $query->limit($limit);
		}

		$query->chunk(250, function($products) use (&$data) {
			    foreach ($products as $product) {

			    	if (strpos($product->main_image, config('default-variables.default-image')) !== false) {
			    		continue;
			    	}

			    	if (! $product->category instanceof \App\Category) {
			    		continue;
			    	}

			    	$data[] = $this->transform($product);
			    }

			    sleep(mt_rand(1, 2));
			});

		$this->setFeed($data);
	}

	/**
	 * transform product to array
	 * 
	 * @param product
	 * @return array
	 */
	public function transform($product) : array
	{
		$categories = $product->parentCategories->pluck('name')->toArray();

		array_unshift(
			$categories, 
			$product->frozen ? 'Frozen/Refer' : 'Standard product'
		);

		$additionalImage = $product->images->isEmpty() ? '' : asset("storage/" . $product->images->first()->src);

		$description = html_entity_decode(strip_tags($product->description));

		if (trim($description) === '') {
			$description = ucfirst($product->name);
		}

		return [

			'id' 			=> $product->sku,

			'product_id'	=> $product->id,

			'title' 		=> ucfirst($product->name),

			'description' 	=> $description,

			'link' 			=> route('product.index', $product->slug),

			'condition'		=> 'new',

			'price'    		=> number_format($product->price, 2, '.', '') . ' USD',

			'availability'  => $product->quantity > 0 ? 'in stock' : 'out of stock',

			'image_link'    => asset($product->main_image),

			'gtin'			=> $product->upc,

			'mpn'			=> $product->mpn,

			'brand'			=> html_entity_decode($product->brand->name),

			'google_product_category' => $product->google_product_category,

			'shipping_weight' => $product->weight,

			'Custom_label_0' => isset($categories[0]) ? $categories[0] : '',

			'Custom_label_1' => isset($categories[1]) ? $categories[1] : '',

			'Custom_label_2' => isset($categories[2]) ? $categories[2] : '',

			'Custom_label_3' => isset($categories[3]) ? $categories[3] : '',

			'Custom_label_4' => isset($categories[4]) ? $categories[4] : '',

			'min_handling_time' => (int) $product->frozen === 1 ? '4' : '3',

			'max_handling_time' => '',

			'shipping_label' => (int) $product->frozen === 1 ? 'Frozen' : 'Standard',

			'cost' => $product->cost,

			'total_cost' => $product->total_cost,

			'product_weight' => $product->vendor_weight,

			'fulfillment_fee' => $product->fulfillment_fee,

			'vedor_code' => $product->vendor_code,

		];
	}
}