<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\ExampleController;
use App\Http\Controllers\UserController;

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
        'success' => 'Confirmed',
        'message' => ' Welcome to our new Polls Api ',
    ]);
});


$router->group(['prefix' => 'api/v1/'], function() use ($router){
    $router->get('/', 'ExampleController@something');


    // User
    $router->post('/user', 'UsersController@create');
    $router->get('/user', 'UsersController@index');

    $router->post('/login', 'UsersController@authnicate');


});
