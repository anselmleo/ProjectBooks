<?php


namespace App\Utils;

class Rules
{
    const RULES = [
        'REGISTRATION' => [
            'email' => 'required|unique:users|email',
            'phone' => 'unique:users|digits:11',
            'password' => 'required|confirmed|min:8'
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
