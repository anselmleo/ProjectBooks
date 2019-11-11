<?php


namespace App\Utils;

class Rules
{
    const RULES = [
        'POST_ORDER' => [
            'full_name' => 'required|string',
            'email' => 'required|unique:users|email',
            'phone' => 'required|unique:users|digits:11',
            'frame_type' => 'required|string',
            'frame_image' => 'required_without:frame_text|image|mimes:jpeg,png,jpg',
            'frame_text' => 'required_without:frame_image|string',
            'frame_dimension' => 'required|string',
            'shipping_addr' => 'required|string',
            'state' => 'required|string',
            'extra_note' => 'string'
        ],

        'CONFIRM_EMAIL' => [
            'token' => 'required|string'
        ],

        'AUTHENTICATE' => [
            'email' => 'required',
            'password' => 'required'
        ],

        'UPDATE_PROFILE' => [
            'full_name' => 'string',
            'avatar' => 'string',
            'gender' => 'string',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
        ],

        'CHANGE_PASSWORD' => [
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ],

        'PAYSTACK_INITIALIZE' => [
            'order_id' => 'required|numeric',
            'email' => 'required|email',
            'amount' => 'required|numeric',
        ],

        'PAYSTACK_VERIFY' => [
            'reference' => 'required'
        ]
    ];

    public static function get($rule, $validation = [])
    {
        $rules = data_get(self::RULES, $rule);
        if ($validation) {
            foreach ($validation as $key => $item) {
                data_set($rules, $key, $item, true);
            }
        }
        return $rules;
    }

}
