<?php

namespace FME\GoogleMerchant;

use App\Shop\Products\Product;
use FME\GoogleMerchant\ProductsProcesser;
use FME\GoogleMerchant\Contract\GoogleMerchantContract;

class GoogleProducts extends GoogleMerchantContract {

    // These constants define the identifiers for all of our example products
    // The products will be sold online
    const CHANNEL = 'online';
    
    // The product details are provided in English
    const CONTENT_LANGUAGE = 'en';

    // The products are sold in the United States
    const TARGET_COUNTRY = 'US';

    // This constant defines how many example products to create in a batch
    const BATCH_SIZE = 10;

    public function deleteProduct($offerId) 
    {
        $productId = $this->buildProductId($offerId);
        // The response for a successful delete is empty
        $this->session->service->products->delete(
            $this->session->merchantId, $productId
        );
    }
    
    public function listProducts($callback = null) 
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);

        // We set the maximum number of results to be lower than the number of
        // products that we inserted, to demonstrate paging.
        $products = $this->session->service->products->listProducts(
            $this->session->merchantId
        );

        while (!empty($products->getResources())) 
        {
            foreach ($products->getResources() as $product) {
                if ($callback instanceof closure) {
                    $callback($product);
                }
            }

            // If the result has a nextPageToken property then there are more pages
            // available to fetch
            if (empty($products->getNextPageToken())) {
                break;
            }

            // You can fetch the next page of results by setting the pageToken
            // parameter with the value of nextPageToken from the previous result.
            $parameters['pageToken'] = $products->nextPageToken;

            $products = $this->session->service->products->listProducts(
                $this->session->merchantId, $parameters
            );
        }

        return $products;
    }

    public function getProduct($offerId) 
    {
        $productId = $this->buildProductId($offerId);

        $product = $this->session->service->products->get(
            $this->session->merchantId, $productId
        );

        return $product;
    }

    public function updateProduct(\Google_Service_ShoppingContent_Product $product) 
    {
        $product->setSource(null);
        return $this->session->service->products->insert($this->session->merchantId, $product);
    }

    private function buildProductId($offerId) {
        return sprintf('%s:%s:%s:%s', self::CHANNEL, self::CONTENT_LANGUAGE, self::TARGET_COUNTRY, $offerId);
    }

    public function insertProduct(\Google_Service_ShoppingContent_Product $product) 
    {
        return $this->session->service->products->insert(
            $this->session->merchantId, $product
        );
    }

    public function createProduct(array $data)
    {
        $product = new \Google_Service_ShoppingContent_Product();

        return $this->insertProduct(
            $this->setProductAttributes($product, $data)
        );
    }

    public function setProductAttributes(\Google_Service_ShoppingContent_Product $product, array $data)
    {
        if ( isset($data['id']) ) {
            $product->setOfferId($data['id']);
        }

        if ( isset($data['title']) ) {
            $product->setTitle($data['title']);
        }

        if ( isset($data['description']) ) {
            $product->setDescription($data['description']);
        }
        $product->setContentLanguage(self::CONTENT_LANGUAGE);
        $product->setTargetCountry(self::TARGET_COUNTRY);
        $product->setChannel(self::CHANNEL);
        $product->setCondition('new');

        if ( isset($data['link']) ) {
            $product->setLink($data['link']);
        }

        if ( isset($data['image_link']) ) {
            $product->setImageLink($data['image_link']);
        }

        if ( isset($data['additional_image_link']) ) {
            $product->setAdditionalImageLinks([ $data['additional_image_link'] ]);
        }

        if ( isset($data['availability']) ) {
            $product->setAvailability($data['availability']);
        }

        if ( 
            isset($data['Custom_label_0']) || 
            isset($data['Custom_label_1']) || 
            isset($data['Custom_label_2']) || 
            isset($data['Custom_label_3']) || 
            isset($data['Custom_label_4']) ) 
        {
            $product->setCustomLabel0($data['Custom_label_0']);
            $product->setCustomLabel1($data['Custom_label_1']);
            $product->setCustomLabel2($data['Custom_label_2']);
            $product->setCustomLabel3($data['Custom_label_3']);
            $product->setCustomLabel4($data['Custom_label_4']);
        }

        if ( isset($data['google_product_category']) ) {
            $product->setGoogleProductCategory($data['google_product_category']);
        }

        if ( isset($data['product_type']) ) {
            $product->setProductTypes([$data['product_type']]);
        }

        if ( isset($data['gtin']) ) {
            $product->setGtin($data['gtin']);
        }

        if ( isset($data['brand']) ) {
            $product->setBrand($data['brand']);
        }

        if ( isset($data['mpn']) ) {
            $product->setMpn($data['mpn']);
        }

        if ( isset($data['price']) ) {
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue(parse_number($data['price']));
            $price->setCurrency('USD');
            $product->setPrice($price);
        }

        if ( isset($data['sale_price']) ) {
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue(parse_number($data['sale_price']));
            $price->setCurrency('USD');
            $product->setSalePrice($price);
        }

        if ( isset($data['cost_of_goods_sold']) ) {
            // Cost of goods sold
            $price = new \Google_Service_ShoppingContent_Price();
            $price->setValue(parse_number($data['cost_of_goods_sold']));
            $price->setCurrency('USD');
            $product->setCostOfGoodsSold($price);
        }

        if ( isset($data['shipping']) ) {
            $shippingPrice = new \Google_Service_ShoppingContent_Price();
            $shippingPrice->setValue(parse_number($data['shipping']));
            $shippingPrice->setCurrency('USD');
            $shipping = new \Google_Service_ShoppingContent_ProductShipping();
            $shipping->setPrice($shippingPrice);
            $shipping->setCountry('US');
            $shipping->setService('Standard');
            $product->setShipping([$shipping]);
        }

        if ( isset($data['shipping_weight']) ) {
            $shippingWeight = new \Google_Service_ShoppingContent_ProductShippingWeight();
            $shippingWeight->setValue($data['shipping_weight']);
            $product->setShippingWeight($shippingWeight);
        }

        if ( isset($data['tax']) ) {
            $tax = new \Google_Service_ShoppingContent_ProductTax;
            $tax->setCountry('US');
            $tax->setRegion('PA');
            $tax->setRate(parse_number($data['tax']));
            $tax->setTaxShip(false);
            $product->setTaxes([$tax]);
        }

        return $product;
    }

}
