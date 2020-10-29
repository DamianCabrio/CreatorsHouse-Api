<?php

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

$router->group(['prefix' => 'api', "middleware" => "auth:api"], function () use ($router) {
    $router->get("/users/me", "UserController@me");
});

$router->get('/users/{id}', ['uses' => 'UserController@show']);
$router->get('users', ['uses' => 'UserController@index']);
$router->get('categories', ['uses' => 'CategoryController@index']);
$router->get('creators', ['uses' => 'CreatorController@index']);
$router->get('userCreators', ['uses' => 'CreatorController@showCreators']);

$router->group(['prefix' => 'api', 'middleware' => 'client.credentials'], function () use ($router) {


    $router->get('/users/{id}', ['uses' => 'UserController@show']);

    $router->put('users/{id}', ['uses' => 'UserController@update']);

    $router->patch('users/{id}', ['uses' => 'UserController@update']);

    $router->delete('users/{id}', ['uses' => 'UserController@delete']);
});

$router->post('users', ['uses' => 'UserController@store']);
$router->post('login', ['uses' => 'UserController@login']);
