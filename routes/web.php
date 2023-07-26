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
$router->get( "profile", ["uses" => "AuthController@me"] );
/*
*
* AUTHENTICATED ROUTES
*
*/
$router->group(
  [
    "middleware" => "auth",
  ], function( $router ) {
    $router->post( "logout", ["uses" => "AuthController@logout"] );
    $router->get( "refresh", ["uses" => "AuthController@refresh"] ); 
    $router->post( "refresh", ["uses" => "AuthController@refresh"] );
    
    $router->post( "tanaman", ["uses" => "TanamanController@postTanaman"]);
    
    $router->post( "post", ["uses" => "ForumController@createPost"]);
    $router->get( "post/{postId}/likes", ["uses" => "ForumController@createLike"]);
    $router->post( "post/{postId}/comments", ["uses" => "ForumController@createComment"]);
    $router->post( "post/{postId}/shares", ["uses" => "ForumController@createShare"]);
    $router->post( "post/{postId}/bookmarks", ["uses" => "ForumController@createBookmark"]);

    $router->get( "tanaman", ["uses" => "TanamanController@listTanaman"]);
  });