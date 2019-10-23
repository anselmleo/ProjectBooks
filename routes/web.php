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
        "message" => "Welcome to Fotomi API endpoints",
        "base_url" => url('/') . "/api/v1/"
    ]);
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('user-registration', 'AuthController@registerUser');
    $router->post('confirm-email', 'AuthController@confirmEmail');
    $router->post('authenticate', 'AuthController@authenticate');

    $router->patch('update-password', 'UserController@updatePassword');
    $router->patch('profile', 'UserController@profile');

    $router->group(['prefix' => 'photos'], function () use ($router) {
        $router->post('upload', 'PhotoController@uploadPhoto');
        $router->post('upload64', 'PhotoController@uploadPhoto64');
        $router->get('', 'PhotoController@myPhotos');
        $router->get('/{photo_id}', 'PhotoController@getSinglePhoto');
    });

    $router->group(['prefix' => 'frames'], function () use ($router) {
        $router->get('pricing', 'FrameController@getPricing');
    });

    $router->group(['prefix' => 'orders'], function () use ($router) {
        $router->post('', 'OrderController@order');
    });

    $router->group(['prefix' => 'pay'], function () use ($router) {
        $router->post('', 'PaystackController@initialize');
        $router->get('verify', 'PaystackController@verifyPay');
    });


    //STILL WORKING ON THIS
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->get('all-users', 'AdminController@allUsers');
        $router->get('all-orders', 'AdminController@getAllOrders');
    });
});
