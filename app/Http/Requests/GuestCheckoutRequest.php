<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|email:rfc,dns|max:191',
            'phone' => 'required|max:191',
            'origin_id' => 'required|exists:order_origins,id',
            'billing_address_1' => 'required|max:191',
            'billing_address_zipcode' => 'required|max:191',
            'billing_address_state_id' => 'required|exists:states,id',
            'billing_address_city' => 'required',
            'payment_method' => 'required|string|in:paypal,credit_card',
            'cc_name' => 'required',
            'cc_number' => 'required',
            'cc_expiration_month' => 'required|numeric|between:01,12',
            'cc_expiration_year' => 'required|numeric|between:20,30',
            'cc_cvv' => 'required',
        ];
    }

    /**
     * Get shipping address validation rules
     *
     * @return array
     */
    public function conditionalRules()
    {
        return [
            'shipping_address_1' => 'required|max:191',
            'shipping_address_zipcode' => 'required|max:191',
            'shipping_address_state_id' => 'required|exists:states,id',
            'shipping_address_city' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if (request()->has('shipping_address_different') && (request()->shipping_address_different === 'true' || request()->shipping_address_different === true)) {
            request()->validate(array_merge(
                $this->rules(),
                $this->conditionalRules(),
            ));
        }
    }
}
