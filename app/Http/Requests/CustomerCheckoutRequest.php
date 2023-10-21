<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CartCheckoutRequest
 * 
 * @package App\Shop\Cart\Requests
 * @codeCoverageIgnore
 */
class CustomerCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => 'required|string|in:paypal,credit_card',
            'cc_name' => 'required',
            'cc_number' => 'required',
            'cc_expiration_month' => 'required|numeric|between:01,12',
            'cc_expiration_year' => 'required|numeric|between:20,30',
            'cc_cvv' => 'required',
            'billing_address' => [
                'required', 
                'integer', 
                Rule::exists('addresses', 'id')->where(function ($query) {
                    $query->where(
                        'customer_id', auth()->guard('customer')->user()->id 
                    );
                })
            ],
        ];
    }
}
