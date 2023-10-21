<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Mail\{ QuoteRequest, BulkOrderMailable };
use App\Repositories\{ CartRepository, OrderRepository };
use App\{ Customer, Discount, DiscountProduct, Order, Product, UserList };
use App\Http\Resources\ProductSearchResultResource;

class QuoteRequestController extends Controller
{


    protected $orderRepository;
    protected $cartRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
        $this->orderRepository = new OrderRepository($this->cartRepository);
    }

    /**
     * Getting a list of products that are stored to the quote list if desired
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {

            if (is_null($request->get('product'))) {
                throw new \Exception("Error Processing Request");
            }

            $needle = trim($request->get('product'));

            $product = Product::whereHas('images')
                            ->with('images')
                            ->where('sku','like','%' . $needle . '%')
                            ->firstOrFail();

            return view('front.pages.quote-request', [
                'product' => (new ProductSearchResultResource($product))->toJson()
            ]);
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        return view('front.pages.quote-request', [
            'product' => $request->product ?? null
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {

            $needle = trim($request->get('query'));
            $products = Product::where('status',1)
                               ->whereHas('images')
                               ->where('name','like','%' . $needle . '%')
                               ->orWhere('sku','like','%' . $needle . '%')
                               ->limit(5)
                               ->get();
            return response(ProductSearchResultResource::collection($products->load('images')),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::where('sku', $request->keyword)->orWhere('name', $request->keyword)->first();
        $listQuote = UserList::where('id', 3)->first();

        if($product){
            if(!$listQuote->products()->where('product_id', $product->id)->exists()){
                $listQuote->products()->attach($product);
                $listQuote->products()->where('product_id', $product->id)->update(['product_user_list.quantity' => $request->quantity]);
            }
        }

        $testList = $listQuote->products()->get();

        $productsQuote = $listQuote->products()->getResults();

        $products = $listQuote->products()->getResults();

        $viewType = $request->get('view_type') ?? 'contained-list';
        $pidQ = array();
        foreach ($productsQuote as $product) {
           $pidQ[$product->id] = $product->pivot->quantity;
        }

        return view('front.pages.quote-request',
            compact(
                'pidQ',
                'productsQuote',
                'viewType',
                'listQuote',
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($prodId, Request $request)
    {

        DB::delete('delete from product_user_list where product_id = ? and user_list_id = 3',[$prodId]);

        $viewType = $request->get('view_type') ?? 'contained-list';

        return redirect(route('quoterequest.index'));
    }


    /**
     * Process the request a quote form
     *
     * @return \Illuminate\Http\Response
     */
    public function quoteRequest(Request $request)
    {
        $mailMeta = ['status' => null, 'messages' => [], 'time' => date('l, F jS, Y h:i:s A')];

        $validatedData = $request->validate([
            "products_requested"    => "required",
            "quote_name"            => "required",
            "quote_email"           => "required",
            "quote_phone"           => "required",
            "quote_address_1"       => "required",
            "quote_city"            => "required",
            "quote_state"           => "required",
            "quote_zip"             => "required",
        ]);

        if($mailMeta['status'] != 'invalid') {
            try
            {
                Mail::to(config('mail.from.address'))
                    ->send(new QuoteRequest($request));
            }
            catch (\Exception $e)
            {
                logger('Quote Request error');
                logger($e);
                return back()->with('error','We\'re sorry. There was an error. Please try again later.');
            }
        }

        return back()->with('message','Thank you for submitting your request!');

    }

    /**
     * Method for creating a bulk order on-demand
     *
     * Takes an order id and pushes that order to the session
     *
     * redirects the customer to checkout or to the homepage if the order is no longer valid
     * 
     * @param $order
     * @return \Illuminate\Http\Response
     */
    public function convertOrder($order)
    {
        if(! request()->hasValidSignature()) {
            return redirect()->route('home')
                ->with('message', 'We\'re sorry, but we can\'t proceed with that order. If you believe an error has been made, please contact support.');
        }

        request()->session()->invalidate();
        
        $this->cartRepository->clear();

        $order = Order::where('id', $order)->firstOrFail();

        session(['order' => Arr::wrap($order->id)]);

        // Add order items to cart
        foreach ($order->products as $prod) {
            $options = isset($prod->pivot) ? $prod->pivot->toArray() : [];
            $options['main_image'] = $prod->main_image;
            $this->cartRepository->addToCart($prod, $prod->pivot->quantity, $options);
        }

        if ($order->appliedDiscount != null) {
            $this->cartRepository->setDiscount($order->appliedDiscount->id);
        }

        if ($order->has_free_shipping) {
            $this->cartRepository->setFreeShipping();
        }

        request()->session()->reflash();

        return redirect()->route('guest.checkout.index');
    }

    /**
     * Display a form in the admin to make allow the creation of an order
     */
    public function adminCreate()
    {
        // return a view
        return view('vendor.voyager.orders.create');
    }

    /**
     * Store the Admin-created Order and trigger an email to the customer
     */
    public function adminStore(Request $request)
    {
        $validatedData = $request->validate([
            'customer_email'    => 'required|email',
            'customer_name'     => 'required|min:5',
            'customer_phone'    => 'required',
            'product_sku'       => 'required',
            'product_quantity'  => 'required',
            'product_discount.*' => 'nullable|numeric|min:0|max:100'
        ]);

        $customer = Customer::where('email', $validatedData['customer_email'])->first();
        $customerId = $customer ? $customer->id : null;

        // Create an order on the admin side of things
        $order = Order::create([
            'name'                => $validatedData['customer_name'],
            'email'               => $validatedData['customer_email'],
            'phone'               => preg_replace('/[^0-9]/', '', trim($validatedData['customer_phone'])),
            'cc_number'           => null,
            'cc_name'             => null,
            'cc_expiration'       => null,
            'cc_expiration_month' => null,
            'cc_expiration_year'  => null,
            'card_type'           => null,
            'cc_cvv'              => null,
            //'gclid'               => session('gclid'),
            //'payment_method'      => $data['payment_method'],
            //'tax_rate'            => $this->cartRepository->getTaxRate(),
            'customer_id'         => $customerId,
            'order_status_id'     => 1,
        ]);

        for($i = 0; $i < count($validatedData['product_sku']); $i++) {
            $prod = Product::where('sku', $validatedData['product_sku'][$i])->first();
            $discounts = 0;
            $subtotal = 0;
            if ($prod) {
                if ($validatedData['product_discount'][$i] && $validatedData['product_discount'][$i] > 0) {
                   $new_discount = Discount::create([
                        'discount_amount' => round($prod->price * $validatedData['product_discount'][$i] / 100, 2),
                        'discount_type' => 'Discount on specific product',
                        'discount_method' => 'Percentage off',
                        'coupon_code' => '',
                    ]);
                    DiscountProduct::create([
                        'product_id' => $prod->id,
                        'discount_id' => $new_discount->id,
                    ]);
                    $discounts += $new_discount->discount_amount * $validatedData['product_quantity'][$i];
                    $subtotal += $prod->price * $validatedData['product_quantity'][$i];
                }
                $order->products()->attach($prod, [
                    'quantity' => $validatedData['product_quantity'][$i],
                    'price'    => $prod->price
                ]);

                $order->save();
            }
        }
        $order->update([
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discounts, 2),
        ]);
        // Send the email
        return $this->mailOrder($order->id, $request);
    }

    /**
     * Mails a Bulk Order Email
     *
     * @param $orderId
     * @return \Illuminate\Http\Response
     */
    public function mailOrder($orderId, Request $request)
    {
        $order = Order::findOrFail($orderId);

        Mail::to($order->email)->send(new BulkOrderMailable($order));

        return redirect()
                ->route('voyager.orders.index')
                ->with(
                    'message',
                    'Thank you for submitting your request!'
                );
    }
}
