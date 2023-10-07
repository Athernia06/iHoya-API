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




/*
 *
 * UNAUTHENTICATED ROUTES
 *
 */
$router->get('/', function () use ($router) {
  return '<h1>Welcome to iHoya API</h1>';
});
$router->post( "login", ["uses" => "AuthController@login"]);
$router->post( "register", ["uses" => "AuthController@register"] );

/*
*
* AUTHENTICATED ROUTES
*
*/
$router->group(
  [
    "middleware" => "jwt.auth",
  ], function () use ($router) {
    //Auth
    $router->post( "logout", ["uses" => "AuthController@logout"] );
    $router->get( "refresh", ["uses" => "AuthController@refresh"] ); 
    $router->post( "refresh", ["uses" => "AuthController@refresh"] );
    $router->get( "profile", ["uses" => "AuthController@me"] );
    $router->post( "tanaman", ["uses" => "TanamanController@postTanaman"]);
    
    //Forum
    $router->post( "post", ["uses" => "ForumController@createPost"]);
    $router->post( "post/{postId}/likes", ["uses" => "ForumController@Like"]);
    $router->post( "post/{postId}/shares", ["uses" => "ForumController@createShare"]);
    $router->post( "post/{postId}/bookmarks", ["uses" => "ForumController@createBookmark"]);

    $router->get( "forums", ["uses" => "ForumController@getForums"]);
    $router->get( "forums/{userId}", ["uses" => "ForumController@getForums"]);
    $router->post( "forums", ["uses" => "ForumController@createForums"]);
    $router->post( "post/update", ["uses" => "ForumController@updateForums"]);
    $router->post( "post/delete","ForumController@destroy" );
    
    //Comment
    $router->post( "comment/store", ["uses" => "ForumController@commentStore"]);
    $router->post( "comment/update", ["uses" => "ForumController@commentUpdate"]);
    $router->post( "comment/delete","ForumController@commentDelete" );

    //ListTanaman
    $router->get( "tanaman", ["uses" => "TanamanController@listTanaman"]);
    $router->get( "pulau/tanaman", ["uses" => "TanamanController@getTanaman"]);
    $router->get( "pulau", ["uses" => "TanamanController@listPulau"]);
  });