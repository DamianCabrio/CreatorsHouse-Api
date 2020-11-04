<?php

/** @var Router $router */

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

use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

$router->get('/users/verify/{token}', ["as" => "verify", "uses" => "UserController@verify"]);

$router->group(['prefix' => 'api', "middleware" => "auth:api"], function () use ($router) {
    $router->get("/users/me", "UserController@me");
    $router->get("/usercreator/{idUser}", "CreatorController@showOne");
});
//Muestra todos los Posts de un creator
$router->get("/postscreator/{creator_id}", "CreatorController@showPostsCreator");

//$router->get('/users/{id}', ['uses' => 'UserController@show']);
//$router->get('users', ['uses' => 'UserController@index']);

//---------Para el home de Crator House
$router->get('categories', ['uses' => 'CategoryController@index']);
$router->get('creators', ['uses' => 'CreatorController@index']);
$router->get('userCreators', ['uses' => 'CreatorController@showCreators']);
$router->get('userCreatorsHome', ['uses' => 'CreatorController@showCreatorsHome']);
//Lista todos los creadores de una categoria dada
$router->get("/catCreators/{category_id}", "CategoryController@showCatCreators");
//------------------

//Registro de Usuario
$router->post('users', ['uses' => 'UserController@store']);

//Login de usuario
$router->post('login', ['uses' => 'UserController@login']);

//Prueba con Auth0 y jwt
/* $router->group(['prefix' => 'api', 'middleware' => 'client.credentials'], function () use ($router) {

    $router->get('/users/{id}', ['uses' => 'UserController@show']);

    $router->put('users/{id}', ['uses' => 'UserController@update']);

    $router->patch('users/{id}', ['uses' => 'UserController@update']);

    $router->delete('users/{id}', ['uses' => 'UserController@delete']);
}); */

//$router->post('users', ['uses' => 'UserController@store']);
//$router->post('login', ['uses' => 'UserController@login']);
