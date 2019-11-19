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

$router->get('/api/v1', function () use ($router) {
    return response()->json([
        "Message" => "Please specify endpoint to process request",
        "Next Steps" => "See API documentation for help on end-points"
    ]);
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->post('user-registration', 'AuthController@registerUser');
    $router->post('confirm-email', 'AuthController@confirmEmail');
    $router->post('authenticate', 'AuthController@authenticate');

    $router->patch('update-password', 'UserController@updatePassword');

    $router->patch('profile', 'UserController@profile');
    $router->get('all-users', 'UserController@getUsers');


    $router->group(['prefix' => 'book'], function () use ($router) {
        $router->get('', 'BookController@getBooks');
        $router->post('create', 'BookController@createBook');
        $router->patch('{book_id}/update', 'BookController@updateBook');
        $router->delete('{book_id}/delete', 'BookController@deleteBook');
    });
});
