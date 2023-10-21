<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Discount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ReportFacebookEventJob;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\CartRepositoryContract;
use App\Repositories\Contracts\DiscountRepositoryContract;

class CartController extends Controller
{
    /**
     * Cart repository
     *
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @param CartRepositoryContract $cartRepository
     * @param DiscountRepositoryContract $discountRepository
     */
    public function __construct(CartRepositoryContract $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $subtotal = $this->cartRepository->getSubTotal();
        } catch (\Exception $e) {
            $subtotal = 0;
        }

        try {
            $total = $this->cartRepository->getTotal();
        } catch (\Exception $e) {
            $total = 0;
        }

        try {
            $tax = $this->cartRepository->getTax();
            $taxRate = $this->cartRepository->getTaxRate();
        } catch (\Exception $e) {
            $tax = 0;
            $taxRate = 0;
        }

        try {
            $discount = $this->cartRepository->getDiscountValue();
        } catch (\Exception $e) {
            $discount = 0;
        }

        try {
            $shipping = $this->cartRepository->getShipping();
        } catch (\Exception $e) {
            $shipping = 0;
        }

        try {
            $cartItems = $this->cartRepository->getMappedCartItems();
        } catch (\Exception $e) {
            $cartItems = [];
        }

        return response()->json([
            'subtotal' => $subtotal,
            'total' => $total,
            'tax' => $tax,
            'taxRate' => $taxRate,
            'discount' => $discount,
            'shipping' => $shipping,
            'cartItems' => $cartItems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        $product = Product::findOrFail($request->id);
        $item = $this->cartRepository->findItem($product->id);
        $currentItemQty = $item ? $item->quantity : 0;
        if ($currentItemQty + $request->input('quantity', 0) > $product->quantity) {
            return response()->json([
                'message' => 'You are ordering more than we have in stock. Please order '.$product->quantity.' or less.',
                'maxQuantity' => $product->quantity
            ], 422);
        }

        $options = $request->except('_token', 'id');

        // create new cart item
        $cartItem = $this->cartRepository
                        ->addToCart($product, $request->quantity, $options)
                        ->get($product->id);

        if (session()->has('discount_id')) {
            $discount = Discount::findOrFail(session('discount_id'));
            $this->cartRepository->setDiscount($discount);
        }

        $this->cartRepository->setBulkDiscount($cartItem);

        $cartItemArr = is_array($cartItem) ? $cartItem : $cartItem->all();
        $cartItemArr['bulkPrice'] = is_array($cartItem) ? $cartItem['price'] : round($cartItem->getPriceWithConditions(), 2);
        $cartItemArr['deleted'] = false;

        try {
            ReportFacebookEventJob::dispatch([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'url' => $request->fullUrl(),
                'product_id' => $product->id,
                'product_price' => $product->price,
                'product_quantity' => $request->quantity,
            ], 'AddToCart')->onQueue('default');
        } catch (\Exception $e) {
            logger($e->getMessage());
        }

        return response()->json($cartItemArr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCartRequest $request
     * @param int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $item = $this->cartRepository->findItem($product->id);

        if ($request->quantity > $product->quantity) {
            return response()->json([
                'message' => 'You are ordering more than we have in stock. Please order '.$product->quantity.' or less.',
                'maxQuantity' => $product->quantity
            ], 200);
        }


        if (! $this->cartRepository->updateQuantityInCart($id , $request->quantity)) {
            return response()->json(false, 500);
        }

        if (session()->has('discount_id')) {
            $discount = Discount::findOrFail(session('discount_id'));
            $this->cartRepository->setDiscount($discount);
        }

        $this->cartRepository->setBulkDiscount($item);

        $item->bulkPrice = round($item->getPriceWithConditions(), 2);

        return response()->json($item->bulkPrice, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRepository->remove($id);

        return response()->json((int) $id);
    }

    /**
     * Get the active Coupon Code.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCouponCode()
    {
        return $this->cartRepository->getDiscount();
    }

    /**
     * Apply the Coupon Code.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyCouponCode(Request $request)
    {
        $data = $request->validate([
            'code'                   => 'required|exists:discounts,coupon_code',
            'orderId'                => 'required|exists:orders,id',
            'contactInfo.first_name' => 'required|max:191',
            'contactInfo.last_name'  => 'required|max:191',
            'contactInfo.email'      => 'required|email:rfc|max:191',
        ]);

        $discount = Discount::where('coupon_code', $data['code'])->firstOrFail();
        $order    = Order::findOrFail($data['orderId']);

        $order->first_name = $data['contactInfo']['first_name'];
        $order->last_name  = $data['contactInfo']['last_name'];
        $order->email      = $data['contactInfo']['email'];
        $order->save();

        if (! $discount->isValid()) {
            return response()->json(false, 422);
        }

        $this->cartRepository->setDiscount($discount);

        session(['discount_id' => $discount->id]);

        return response()->json(true);
    }
}
