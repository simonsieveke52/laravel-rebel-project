<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|min:2|max:191',
            'last_name'  => 'required|string|min:2|max:191',
            'email'      => 'required|email|unique:subscribers,email',
            'origin_id'  => 'required|exists:order_origins,id',
        ];
    }

    /**
     * Validation message
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => "You're already subscribed to our newsletter!",
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
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                session()->forget('visitedAlready');
            }
        });
    }
}
