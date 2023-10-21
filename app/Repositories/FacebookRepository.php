<?php

namespace App\Repositories;

use App\Order;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\EventRequest;

class FacebookRepository
{
    /**
     * @var string
     */
    protected $pixelId;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $api;

    /**
     * @param string $pixelId
     * @param string $token
     */
    public function __construct($pixelId, $token)
    {
        $this->token = $token;
        $this->pixelId = $pixelId;

        $this->api = Api::init(null, null, $this->token);
    }

    /**
     * @return EventResponse
     */
    public function reportPurchase(Order $order)
    {
        $userData = (new UserData())
            ->setEmail($order->email)
            ->setClientIpAddress($order->ip_address)
            ->setClientUserAgent($order->user_agent)
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');

        $events = [];

        foreach ($order->products as $product) {
            $events[] = (new Event())
                ->setEventName('Purchase')
                ->setEventTime(time())
                ->setEventSourceUrl(route('product.show', $product->slug))
                ->setUserData($userData)
                ->setCustomData((new CustomData())
                    ->setCurrency('usd')
                    ->setValue(
                        $product->pivot->price * $product->pivot->quantity
                    )
                )
                ->setActionSource(ActionSource::WEBSITE);
        }

        $request = (new EventRequest($this->pixelId))->setEvents($events);

        return $request->execute();
    }

    /**
     * @return EventResponse
     */
    public function reportInitiateCheckout($order)
    {
        $userData = (new UserData())
            ->setEmail($order->email)
            ->setClientIpAddress($order->ip_address)
            ->setClientUserAgent($order->user_agent)
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');

        $customData = (new CustomData())
            ->setNumItems($order->products->sum('pivot.quantity'))
            ->setCurrency('usd')
            ->setContentIds($order->products->pluck('id')->toArray())
            ->setValue($order->products->sum('pivot.quantity') * $order->products->sum('pivot.price'));

        $events[] = (new Event())
            ->setEventName('InitiateCheckout')
            ->setEventTime(time())
            ->setEventSourceUrl(route('abandoned-cart.store'))
            ->setUserData($userData)
            ->setCustomData($customData)
            ->setActionSource(ActionSource::WEBSITE);

        $request = (new EventRequest($this->pixelId))->setEvents($events);

        return $request->execute();
    }

    /**
     * @return 
     */
    public function reportViewContent(array $data)
    {
        $userData = (new UserData())
            ->setClientIpAddress($data['ip_address'])
            ->setClientUserAgent($data['user_agent'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');

        $events[] = (new Event())
            ->setEventName('ViewContent')
            ->setEventTime(time())
            ->setEventSourceUrl($data['url'])
            ->setUserData($userData)
            ->setActionSource(ActionSource::WEBSITE);

        $request = (new EventRequest($this->pixelId))->setEvents($events);

        return $request->execute();
    }

    /**
     * @return 
     */
    public function reportAddToCart(array $data)
    {
        $userData = (new UserData())
            ->setClientIpAddress($data['ip_address'])
            ->setClientUserAgent($data['user_agent'])
            ->setFbc('fb.1.1554763741205.AbCdEfGhIjKlMnOpQrStUvWxYz1234567890')
            ->setFbp('fb.1.1558571054389.1098115397');

        $customData = (new CustomData())
            ->setNumItems($data['product_quantity'])
            ->setCurrency('usd')
            ->setContentType('product')
            ->setContentIds([$data['product_id']])
            ->setValue($data['product_price'] * $data['product_quantity']);

        $events[] = (new Event())
            ->setEventName('AddToCart')
            ->setEventTime(time())
            ->setEventSourceUrl($data['url'])
            ->setUserData($userData)
            ->setCustomData($customData)
            ->setActionSource(ActionSource::WEBSITE);

        $request = (new EventRequest($this->pixelId))->setEvents($events);

        return $request->execute();
    }
}
