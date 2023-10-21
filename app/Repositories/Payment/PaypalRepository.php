<?php

namespace App\Repositories\Payment;

use Ramsey\Uuid\Uuid;
use App\{Order, Address};
use Illuminate\Http\Request;
use App\Custom\PaymentMethods\Paypal;
use PayPal\Api\Payment as PayPalPayment;
use PayPal\Exception\PayPalConnectionException;
use App\Repository\Payment\Contracts\PaymentRepositoryContract;

class PaypalRepository
{
    /**
     * @var Paypal
     */
    protected $paypal;

    /**
     * @var string
     */
    protected $cancelUrl;

    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var mixed
     */
    protected $transaction;

    /**
     * PaypalRepository constructor.
     */
    public function __construct()
    {
        try {
            // $this->cancelUrl = route('guest.checkout.index');
            // $this->successUrl = route('checkout.confirm');
            // $this->paypal = new Paypal(
            //     config('paypal.'.config('app.env').'.client_id'),
            //     config('paypal.'.config('app.env').'.client_secret'),
            //     config('paypal.'.config('app.env').'.mode')
            // );
        } catch (\Exception $e) {
            
        }
    }

    /**
     * @param mixed $orders
     *
     * @return redirect url
     * @throws \Exception
     */
    public function process($orders)
    {
        $this->paypal->setPayer();

        $this->paypal->setItems(
            $orders->pluck('products')->flatten()
        );

        $this->paypal->setOtherFees(
            $orders->sum('subtotal'), $orders->sum('tax'), $orders->sum('shipping_cost')
        );

        $this->paypal->setAmount($orders->sum('total'));
        
        $this->paypal->setTransactions($orders->first()->id);

        $orders->first()->addresses->each(function($address){
            if ($address->type == 'billing') {
                $this->paypal->setBillingAddress($address);
            } elseif( $address->type == 'shipping' ) {
                $this->paypal->setShippingAddress($address);
            }
        });

        // set success url and cancel url
        $response = $this->paypal->createPayment($this->successUrl, $this->cancelUrl);

        // if payment failed user is redirect to this url
        if ($response) {
            return $response->links[1]->href;
        }
        
        return $this->cancelUrl;
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     * @return  mixed
     */
    public function check(Request $request)
    {
        $payment = PayPalPayment::get($request->paymentId, $this->paypal->getApiContext());
        $execution = $this->paypal->setPayerId($request->PayerID);
        $this->transaction = $payment->execute($execution, $this->paypal->getApiContext());

        // check for transaction status and email
        if (is_null($this->transaction) || $this->transaction->getState() != 'approved') {
            throw new \Exception("Declined transaction.");
        }

        return $this;
    }

    /**
     * Update order attributes and confirm order
     * 
     * @param  Order $order
     * @throws \Exception
     * @return boolean
     */
    public function confirm($orders)
    {
        if (! is_null($this->transaction)) {
            $transactionId = $this->transaction->getId();
        }

        foreach ($orders as $order) {
            $order->transaction_id = $transactionId ?? null;
            $order->payment_method = 'paypal';
            $order->confirmed = true;
            $order->confirmed_at = date('Y-m-d H:i:s');
            $order->save();
        }

        return $this;
    }
}