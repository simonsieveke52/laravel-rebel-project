<?php 

namespace FME\GoogleMerchant;

use Illuminate\Support\Collection;
use FME\EloquenceCsvFeed\Handlers\ProductHandler;

class ProductsProcesser
{
	/**
	 * Produccts collection
	 * 
	 * @var $products
	 */
	protected $products;

	/**
	 * @param Collection $products
	 */
	function __construct(Collection $products)
	{
		$this->products = $products;
	}

	/**
	 * Update products on api
	 * 
	 * @return Collection
	 */
	public function update($callback = null)
	{
		return $this->products->each(function($product) use ($callback){

            $googleProduct = app()->make('GoogleProducts');

            try {

                tap($googleProduct, function($googleProduct) use ($product){
                    $data = (new ProductHandler())->transform($product);
                    $product = $googleProduct->getProduct($product->id);
                    $googleProduct->updateProduct(
                        $googleProduct->setProductAttributes($product ,$data)
                    );
                });

                if ($callback instanceof \Closure) {
                	$callback($product);
                }

            } catch (\Exception $e) {

                $message = $e->getMessage();

                if (strpos(json_encode($message), 'notFound') !== false && $callback instanceof \Closure) {
                	$callback($product);
                } else {
	                logger($message);
                }
            }

        });
	}
}