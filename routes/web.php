<?php

use App\Http\Controllers\UserController;

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();

});


$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

    $router->get('users', ['uses' => 'UserController@index']);

    $router->get('/users/{id}', ['uses' => 'UserController@show']);

    $router->post('users', ['uses' => 'UserController@store']);

    $router->delete('users/{id}', ['uses' => 'UserController@delete']);

    $router->put('users/{id}', ['uses' => 'UserController@update']);
});
