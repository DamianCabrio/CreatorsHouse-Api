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

use Laravel\Lumen\Routing\Router;

//Password Resset
$router->post('/password/reset-request', 'RequestPasswordController@sendResetLinkEmail');
$router->post('/password/reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);

$router->get('/users/verify/{token}', ["as" => "verify", "uses" => "UserController@verify"]);



//Prueba ---------------------
//$router->get("/follow/{user}/posts", "FollowController@postFollow");
$router->get(
    "/following/{idUser}",
    "FollowController@following"
);
$router->get(
    "/postsPublic/{idUser}",
    "FollowController@postsPublic"
);
$router->get(
    "/postsPremium/{idUser}",
    "FollowController@postsPremium"
);

$router->post("/notification_ipn", "CallbackController@notification");

$router->post("/upload", "CreatorController@uploadBasico");
//SOAP
$router->get("/getCity/{ip}", "SoapController@getCity");
$router->get("/getFrase", "SoapController@getFrase");
$router->get("/creators/{idCreator}/post/{idPost}", "PostController@show");

//----------------------------------
$router->get("/usercreator/{idUser}", "CreatorController@showOne");
//Logged in user routes
$router->group(['prefix' => 'api', "middleware" => "auth:api"], function () use ($router) {
    $router->post("/user/{idUser}/post/{idPost}/comment", "CommentController@store");
    $router->get("/mercado-pago/callback/{code}", "CallbackController@store");
    $router->post("/creators/{creatorId}/posts", ["as" => "createPost", "uses" => "PostController@store"]);
    $router->get("/users/me", "UserController@me");
    $router->get("/usercreator/{idUser}", "CreatorController@showOne");
    $router->get("/following/{idUser}", "FollowController@following");
    // Follow Guardar - Borrar - Consultar
    $router->get("/isFollow/{idCreator}", "FollowController@isFollow");
    $router->post("/follow/{idUser}/{idCreator}", "FollowController@store");
    $router->post("/unfollow/{idUser}/{idCreator}", "FollowController@delete");
    // --------------------------------
    $router->post("/creators", ["as" => "createCreator", "uses" => "CreatorController@store"]);
    $router->put("/creators/{id}", ["as" => "updateCreator", "uses" => "CreatorController@update"]);
    // Subir imagenes banner y avatar
    $router->post("/upload", "CreatorController@upload");
    $router->post("/uploadBanner", "CreatorController@uploadBanner");
    $router->post("/uploadAvatar", "UserController@uploadAvatar");
    //Subor Post
    $router->post("/creators/posts/{creatorId}", ["as" => "createPost", "uses" => "PostController@store"]);
    $router->get("/creators/{idCreador}/pay/", ["as" => "payCreator", "uses" => "CallbackController@createPayment"]);
    $router->get("/creators/{idCreador}/donate/{monto}", ["as" => "payCreator", "uses" => "CallbackController@donate"]);
    $router->post("/posts/like/{idPost}/{idUser}", ["as" => "likePost", "uses" => "PostController@likePost"]);
    $router->post("/posts/unlike/{idPost}/{idUser}", ["as" => "likePost", "uses" => "PostController@removeLikePost"]);
    $router->post("/creator/makeVip/{idCreador}", ["as" => "makeVip", "uses" => "CallbackController@makeVip"]);
    $router->post("/creator/removeVip/", ["as" => "removeVip", "uses" => "CallbackController@removeVip"]);
});
//Muestra todos los Posts de un creator
$router->get("/postscreator/{creator_id}", "CreatorController@showPostsCreator");
//Muestra los datos de un creator, con cantfollowers y sus datos de user
$router->get("/creator/{id}", "CreatorController@show");

//Muestra todos los posts de los followers de un user
//$router->get("/postsFollows/{user_id}", "CreatorController@showPostsFollows");


//$router->get('/users/{id}', ['uses' => 'UserController@show']);
//$router->get('users', ['uses' => 'UserController@index']);

//---------Para el home de Crator House
$router->get('categories', ['uses' => 'CategoryController@index']);
$router->get('creators', ['uses' => 'CreatorController@index']);
$router->get('userCreators', ['uses' => 'CreatorController@showCreators']);
$router->get('userCreatorsHome', ['uses' => 'CreatorController@showCreatorsHome']);
$router->get('userShowOneRandCreator', ['uses' => 'CreatorController@showOneRandCreator']);
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
