<?php

namespace App\Http\Controllers;

use App\Shipping;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateShippingOptionRequest;
use App\Repositories\Contracts\CartRepositoryContract;

class ShippingController extends Controller
{
	/**
	 * Shipping controller
	 */
	public function __construct(CartRepositoryContract $cartRepository)
	{
		$this->cartRepository = $cartRepository;
	}

	/**
	 * @return jsonResponse
	 */
	public function index()
	{
		if (! request()->ajax()) {
			abort(404);
		}
		
		return Shipping::available()->get();
	}

    /**
     * 
     * @param  UpdateShippingOptionRequest $request
     * @return JsonResponse                              
     */
	public function update(UpdateShippingOptionRequest $request)
	{
		$shipping = Shipping::find($request->shipping);
		
		$this->cartRepository->setShipping($shipping);

		session(['shipping' => Arr::wrap($request->shipping)]);

		return response()->json(
			(float) $this->cartRepository->getShipping()
		);
	}
}
