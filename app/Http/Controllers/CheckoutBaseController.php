<?php

namespace App\Http\Controllers;

use App\Shipping;
use App\{Zipcode, Order};
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\OrderCreateEvent;
use App\Http\Controllers\Controller;
use App\Repositories\AddressRepository;
use App\Http\Requests\ConfirmCheckoutRequest;
use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Payment\{AuthorizeNetRepository, PaypalRepository};
use App\Repositories\{CartRepository, OrderRepository, OrderProductRepository};

class CheckoutBaseController extends Controller
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var PaypalRepository
     */
    protected $paypalRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @var AuthorizeNetRepository
     */
    protected $authorizeNetRepository;

    /**
     * -------------------------------------------------------------------
     * Checkout base controller
     * -------------------------------------------------------------------
     *
     */
    public function __construct(
        OrderRepository $orderRepository,
        PaypalRepository $paypalRepository,
        AddressRepository $addressRepository,
        AuthorizeNetRepository $authorizeNetRepository,
        CartRepositoryContract $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->addressRepository = $addressRepository;
        $this->authorizeNetRepository = $authorizeNetRepository;
        $this->paypalRepository = $paypalRepository;
        
        if (!defined('AUTHORIZENET_LOG_FILE')) {
            define('AUTHORIZENET_LOG_FILE', storage_path('logs/authorize.log'));
        }
    }

    /**
     * Execute payment
     *
     * @param  Request $request
     * @return view
     */
    public function execute(Request $request)
    {
        $orders = Order::findOrFail(
            Arr::wrap(session('order'))
        );

        // if user select to pay with paypal process method will get redirect url
        // all payment process is done within paypal sandbox or live api
        // if anything went wrong user is returned to checkout page
        if ($orders->first()->payment_method === 'paypal' && false) {
            return redirect()->to($this->paypalRepository->process($orders));
        }

        try {

            $this->authorizeNetRepository->process($orders->first());
            $this->authorizeNetRepository->confirm($orders->first());
            $this->cartRepository->clear();

            session()->forget('order');

            foreach ($orders as $order) {
                event(new OrderCreateEvent($order));
            }

            return view('front.checkout.success', [
                'order' => $orders->first()
            ]);

        } catch (\Exception $e) {

            foreach ($orders as $order) {
                $order->transaction_response = $e->getMessage();
                $order->save();
            }

            return redirect()->route('guest.checkout.index')
                ->with('error', "This transaction has been declined by your bank. Please check your account balance, billing address, and billing zipcode.")
                ->withInput();
        }
    }

    /**
     * Confirm paypal payment
     *
     * @param  Request $request
     * @return
     */
    public function confirm(Request $request)
    {
        $orders = Order::findOrFail(
            Arr::wrap(session('order'))
        );

        // confirm authorize payment
        if ($orders->first()->payment_method === 'credit_card') {
            $this->authorizeNetRepository->confirm($orders);
            $this->cartRepository->clear();
            return redirect()->route('checkout.success');
        }

        if ($orders->first()->payment_method != 'paypal') {
            return redirect()->back();
        }

        try {
            $this->paypalRepository->check($request)->confirm($orders);
            $this->cartRepository->clear();
            return redirect()->route('checkout.success');
        } catch (\Exception $e) {;
            return redirect()->route('guest.checkout.index')
                ->with('error', 'Unfortunately the transaction was unsuccessful. Please try again later.');
        }
    }

    /**
     * Get success page
     *
     * @return view
     */
    public function success(Request $request)
    {
        $orders = Order::findOrFail(
            Arr::wrap(session('order'))
        );

        session()->forget('order');

        foreach ($orders as $order) {
            event(new OrderCreateEvent($order));
        }

        return view('front.checkout.success', [
            'order' => $orders->first()
        ]);
    }
}
