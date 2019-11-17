<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json([
        "message" => "Welcome to GetDevBooks API endpoints",
        "base_url" => url('/') . "/api/v1/"
    ]);
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('user-registration', 'AuthController@registerUser');
    $router->post('confirm-email', 'AuthController@confirmEmail');
    $router->post('authenticate', 'AuthController@authenticate');

    $router->patch('update-password', 'UserController@updatePassword');
    $router->patch('profile', 'UserController@profile');

    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('', 'BookController@getBooks');
    });

    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('all-users', 'AdminController@getAllUsers');
        $router->get('all-orders', 'AdminController@getAllOrders');
        $router->patch('pay-order/{order_id}', 'AdminController@payOrder');
        $router->patch('process-order/{order_id}', 'AdminController@processOrder'); 
        $router->patch('receive-order/{order_id}', 'AdminController@receiveOrder'); 
        $router->patch('ship-order/{order_id}', 'AdminController@shipOrder'); 
        $router->patch('deliver-order/{order_id}', 'AdminController@deliverOrder'); 
        $router->patch('complete-order/{order_id}', 'AdminController@completeOrder'); 
    });
});
