<?php

namespace App\Repositories;

use App\Order;
use Exception;
use App\Product;
use App\Discount;
use App\Subscriber;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Setting;
use MailchimpMarketing\ApiClient;
use Illuminate\Support\Facades\Http;
use MailchimpMarketing\ApiException;
use App\Jobs\ApplyAbandonedDiscountJob;

class MailchimpRepository
{
    public  $isSetup;
    public  $list;
    public  $store;
    private $client;
    private $apiUrl;


    public function __construct()
    {
        $this->client = new ApiClient();
        $this->client->setConfig([
            'apiKey' => config('mailchimp.api_key'),
            'server' => config('mailchimp.server_prefix')
        ]);
        $this->apiUrl = config('mailchimp.api_url');

        $settings = Setting::whereIn('key', [
            'mailchimp.store_id',
            'mailchimp.list_id'
        ])->get();

        if ($settings->count() == 2) {
            $this->store   = $settings->firstWhere('key', 'mailchimp.store_id')->value;
            $this->list    = $settings->firstWhere('key', 'mailchimp.list_id')->value;
            $this->isSetup = true;
        } else {
            $this->store   = null;
            $this->list    = null;
            $this->isSetup = false;
        }
    }

    /**
     * Adds a completed order with its products and customer
     * into the mailchimp ecommerce store
     *
     * @param  App\Order $order
     *
     * @return boolean $result
     */
    public function syncOrder(Order $order) : bool
    {
        try {
            $products = $this->findOrCreateProducts($order->products);
            $this->findOrCreateCustomer($order);
            $this->findOrCreateOrder($order, $products->toArray());
        } catch(Exception $e) {
            logger('Failed to add order ' . $order->id . " to mailchimp store\n" . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Adds an abandoned cart (order) with its products and customer
     * into the mailchimp ecommerce store
     *
     * @param  App\Order $order
     *
     * @return boolean $result
     */
    public function syncAbandonedCart(Order $order) : bool
    {
        try {
            $products = $this->findOrCreateProducts($order->products);
            $this->findOrCreateCustomer($order);
            $this->findOrCreateCart($order, $products->toArray());

            try {
                if ($order->origin_id !== null) {
                    $tags = $this->client->lists->getListMemberTags($this->list, md5(strtolower($order->email)));
                    if ($tags->total_items === 0) {
                        $this->client->lists->updateListMemberTags($this->list, md5(strtolower($order->email)), [
                            'tags' => [
                                ['name' => config('mailchimp.abandoned_cart.tag'), 'status' => 'active'],
                                ['name' => 'Abandoned Cart - ' . $order->origin->name, 'status' => 'active'],
                            ]
                        ]);
                    }
                }
            } catch(Exception $e) {
                logger('Failed to add tags for ' . $order->email . "\n" . $e->getMessage());
            }

            ApplyAbandonedDiscountJob::dispatch($order)
                ->delay(config('mailchimp.abandoned_cart.apply_discount_after'));
        } catch(Exception $e) {
            logger('Failed to add cart ' . $order->id . " to mailchimp store\n" . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Deletes an abandoned cart (order) from the mailchimp ecommerce store
     *
     * @param  App\Order $order
     *
     * @return boolean $result
     */
    public function deleteAbandonedCart(Order $order) : bool
    {
        try {
            $this->client->ecommerce->deleteStoreCart($this->store, (string)$order->id);
        } catch(Exception $e) {
            logger('Failed to delete cart ' . $order->id . " from mailchimp store\n" . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Creates a mailchimp list (audience) and ecommerce store
     * Stores the store and list id the voyager settings, for future reference
     *
     * @param  App\Order $order
     *
     * @return boolean $result
     */
    public function marketingSetup()
    {
        $this->createList();
        $this->createStore();
        return true;
    }

    private function findOrCreateProducts($products)
    {
        return $products->map(function($product, $index) {
            $this->findOrCreateProduct($product);
            return [
                'id'                 => (string)($index + 1),
                'product_id'         => (string)$product->id,
                'product_variant_id' => (string)$product->id,
                'quantity'           => (int)$product->pivot->quantity,
                'price'              => (float)$product->pivot->price,
                'discount'           => (float)$product->pivot->discount_amount,
            ];
        });
    }

    private function findOrCreateProduct(Product $product)
    {
        try {
            return $this->client->ecommerce->getStoreProduct($this->store, $product->id);
        } catch(Exception $e) {
            return $this->client->ecommerce->addStoreProduct(
                $this->store, $this->serializeProduct($product)
            );
        }
    }


    /**
     * Adds or updates product as needed
     * into the mailchimp ecommerce store
     *
     * @param  Product $product
     * @return StdObject
     */
    public function createOrUpdateProduct(Product $product)
    {
        try {
            $this->client->ecommerce->getStoreProduct($this->store, $product->id);
        } catch(Exception $e) {
            return $this->client->ecommerce->addStoreProduct(
                $this->store, $this->serializeProduct($product)
            );
        }

        try {
            return $this->client->ecommerce->updateStoreProduct(
                $this->store, (string)$product->id, $this->serializeProduct($product, false)
            );
        } catch(Exception $e) {
            logger("Failed to add product $product->id to store\n" . $e->getMessage());
            return null;
        }
    }

    private function serializeProduct(Product $product, $new = true)
    {
        $data = [
            'title'       => $product->name,
            'handle'      => $product->slug,
            'url'         => route('product.show', $product),
            'description' => $product->description,
            'vendor'      => $product->brand->name,
            'image_url'   => asset($product->mainImage),
            'variants' => [
                [
                    'id'        => (string)$product->id,
                    'title'     => $product->name,
                    'sku'       => $product->sku,
                    'price'     => (float)$product->price,
                    'url'       => route('product.show', $product),
                    'image_url' => asset($product->mainImage),
                ]
            ]
        ];

        if ($new) {
            $data['id'] = (string)$product->id;
        }

        return $data;
    }

    private function findOrCreateCustomer(Order $order)
    {
        if ($order->first_name === null && $order->last_name === null && $order->name !== null) {
            $name = explode(' ', $order->name);
            $order->first_name = array_shift($name);
            $order->last_name  = implode(' ', $name);
        }

        $customer = [
            'id'            => $order->email,
            'email_address' => $order->email,
            'opt_in_status' => config('mailchimp.opt_in_status'),
            'first_name'    => $order->first_name,
            'last_name'     => $order->last_name,
        ];

        if ($order->shippingAddress !== null) {
            $customer['address'] = [
                'address1'      => $order->shippingAddress->address_1,
                'address2'      => $order->shippingAddress->address_2,
                'city'          => $order->shippingAddress->city,
                'province'      => $order->shippingAddress->state->name,
                'province_code' => $order->shippingAddress->state->abv,
                'postal_code'   => (string)$order->shippingAddress->zipcode,
                'country'       => $order->shippingAddress->country->name,
                'country_code'  => $order->shippingAddress->country->iso,
            ];
        }

        return $this->client->ecommerce->setStoreCustomer($this->store, $customer['id'], $customer);
    }

    /**
     * Add a new member (subscriber) to the mailchimp list (audience)
     *
     * @param  Subscriber $subscriber
     * @return StdObject $member
     */
    public function createOrUpdateMember(Subscriber $subscriber)
    {
        $member = [
            'email_address' => $subscriber->email,
            'status_if_new' => 'subscribed',
            'ip_signup'     => $subscriber->ip_address,
            'merge_fields'  => [
                'FNAME' => $subscriber->first_name,
                'LNAME' => $subscriber->last_name
            ]
        ];

        try {
            $member = $this->client->lists->setListMember(
                $this->list,
                md5(strtolower($subscriber->email)),
                $member
            );

            $this->client->lists->updateListMemberTags($this->list, $member->id, [
                'tags' => [
                    ['name' => config('mailchimp.newsletter.tag'), 'status' => 'active'],
                    ['name' => $subscriber->origin->name, 'status' => 'active'],
                ]
            ]);

            $subscriber->markAsSubscribed($member);
            return $member;
        } catch(Exception $e) {
            logger('Failed to create new member: ' . $subscriber->email . "\n" . $e);
            return null;
        }
    }

    private function findOrCreateOrder(Order $order, array $products)
    {
        try {
            return $this->client->ecommerce->getOrder($this->store, $order->id);
        } catch(Exception $e) {
            $data = [
                'id'                   => (string)$order->id,
                'currency_code'        => config('mailchimp.currency'),
                'order_total'          => $order->total,
                'lines'                => $products,
                'tax_total'            => $order->tax,
                'shipping_total'       => $order->shipping_cost,
                'processed_at_foreign' => $order->confirmed_at->toIso8601String(),
                'customer' => [
                    'id'            => $order->email,
                    'email_address' => $order->email,
                    'opt_in_status' => config('mailchimp.opt_in_status'),
                ],
                'shipping_address' => [
                    'name'          => $order->name,
                    'address1'      => $order->shippingAddress->address_1,
                    'address2'      => $order->shippingAddress->address_2,
                    'city'          => $order->shippingAddress->city,
                    'province'      => $order->shippingAddress->state->name,
                    'province_code' => $order->shippingAddress->state->abv,
                    'postal_code'   => (string)$order->shippingAddress->zipcode,
                    'country'       => $order->shippingAddress->country->name,
                    'country_code'  => $order->shippingAddress->country->iso,
                    'phone'         => $order->phone,
                ],
                'billing_address' => [
                    'name'          => $order->name,
                    'address1'      => $order->billingAddress->address_1,
                    'address2'      => $order->billingAddress->address_2,
                    'city'          => $order->billingAddress->city,
                    'province'      => $order->billingAddress->state->name,
                    'province_code' => $order->billingAddress->state->abv,
                    'postal_code'   => (string)$order->billingAddress->zipcode,
                    'country'       => $order->billingAddress->country->name,
                    'country_code'  => $order->billingAddress->country->iso,
                    'phone'         => $order->phone,
                ],
            ];

            if (!empty($order->mc_cid)) {
                $data['campaign_id'] = $order->mc_cid;
            }

            return $this->client->ecommerce->addStoreOrder($this->store, $data);
        }
    }

    private function findOrCreateCart(Order $order, array $products)
    {
        try {
            return $this->client->ecommerce->getStoreCart($this->store, (string)$order->id);
        } catch(Exception $e) {
            $data = [
                'id'                   => (string)$order->id,
                'currency_code'        => config('mailchimp.currency'),
                'order_total'          => $order->total,
                'lines'                => $products,
                'tax_total'            => $order->tax,
                'checkout_url'         => $order->checkout_url,
                'customer' => [
                    'id'            => $order->email,
                    'email_address' => $order->email,
                    'opt_in_status' => config('mailchimp.opt_in_status'),
                ]
            ];

            return $this->client->ecommerce->addStoreCart($this->store, $data);
        }
    }

    private function createList()
    {
        try {
            $list = $this->client->lists->createList([
                'name'                => config('mailchimp.contact.company'),
                'permission_reminder' => config('mailchimp.permission_reminder'),
                'email_type_option'   => config('mailchimp.email_type_option'),
                'contact'             => config('mailchimp.contact'),
                'campaign_defaults'   => config('mailchimp.campaign_defaults'),
            ]);

            $this->list = $list->id;

            $setting = Setting::firstOrNew(['key' => 'mailchimp.list_id']);
            $setting->fill([
                'display_name' => 'Mailchimp List ID',
                'value'        => $this->list,
                'details'      => '',
                'type'         => 'text',
                'order'        => 1,
                'group'        => 'Mailchimp',
            ])->save();

            return $list;
        } catch(Exception $e) {
            logger("Failed to add mailchimp list\n" . $e->getMessage());
            return null;
        }
    }

    private function createStore()
    {
        try {
            $store = $this->client->ecommerce->addStore([
                'id'             => Str::uuid()->toString(),
                'list_id'        => $this->list,
                'name'           => config('mailchimp.contact.company'),
                'currency_code'  => config('mailchimp.currency'),
                'money_format'   => config('mailchimp.money_format'),
                'domain'         => config('mailchimp.domain'), //must be valid not localhost or test TLD
                'platform'       => config('mailchimp.platform'),
                'email_address'  => config('mailchimp.store_email'),
                'phone'          => config('mailchimp.contact.phone'),
                'primary_locale' => config('mailchimp.primary_locale'),
            ]);

            $this->store = $store->id;

            $setting = Setting::firstOrNew(['key' => 'mailchimp.store_id']);
            $setting->fill([
                'display_name' => 'Mailchimp Store ID',
                'value'        => $this->store,
                'details'      => '',
                'type'         => 'text',
                'order'        => 2,
                'group'        => 'Mailchimp',
            ])->save();
            return $store;
        } catch(Exception $e) {
            logger("Failed to add mailchimp store\n" . $e->getMessage());
            return null;
        }
    }

    /**
     * @param  $amount If 'type' is 'fixed', the amount is treated as a monetary value. If 'type' is 'percentage', amount must be a decimal value between 0.0 and 1.0, inclusive.

     * @param  [type] $type   [description]
     * @return [type]         [description]
     */
    public function createPromocode($amount, $type, $target)
    {
        if (! in_array($type, ['percentage', 'fixed'])) {
            throw new \Exception("Only percentage and fixed are allowed for type attribute");
        }
        
        if (! in_array($target, ['per_item', 'total', 'shipping'])) {
            throw new \Exception("Only 'per_item', 'total', 'shipping' are allowed for target attribute");
        }

        $discount = Discount::create([
            'newsletter_signup' => 0,
            'is_active' => 1,
            'discount_type' => $target == 'per_item' ? 'subtotal' : $target,
            'discount_method' => $type == 'fixed' ? 'dollars' : 'percentage',
            'coupon_code' => \Str::random(12),
            'usage_limit' => 1,
            'discount_amount' => $amount,
        ]);

        try {

            if ($amount >= 1 && $type == 'percentage') {
                $amount /= 100;
            }

            $rule = $this->client->ecommerce->addPromoRules($this->store, [
                "id" => (string) $discount->coupon_code,
                "description" => "Save BIG during our summer sale!",
                "amount" => floatval($amount),
                "type" => $type,
                "target" => $target,
                "enabled" => true,
                "starts_at" => now()->toIso8601String(),
                "ends_at" => now()->addDays(30)->toIso8601String(),
                "created_at_foreign" => now()->toIso8601String(),
            ]);

            return $this->client->ecommerce->addPromoCode($this->store, $rule->id, [
                "id" => (string) $discount->coupon_code,
                "code" => $discount->coupon_code,
                "redemption_url" => route('home', [
                    'coupon' => $discount->coupon_code
                ]),
            ]);

        } catch(Exception $e) {
            $discount->delete();
            dd($e);
            logger("Failed to add mailchimp store\n" . $e->getMessage());
            return null;
        }
    }

    /**
     * @return ApiClient
     */
    public function client() 
    {
        return $this->client;
    }

    /**
     * HTTP Client wrapper to hit the mailchimp api.
     * This is really helpful for debugging,
     * but can be also used when the api client doesnt have a feature supported
     *
     * @param  string $method (get, post, put, delete, etc..)
     * @param  string $url
     * @param  array $body
     *
     * @return Illuminate\Http\Client\Response
     */
    public function httpClient($method, $url, $body)
    {
        $headers = ['Authorization' => 'apikey ' . config('mailchimp.api_key')];
        return Http::withHeaders($headers)->$method($url, $body);
    }
}
