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
    "middleware" => "auth",
  ], function( $app ) {
    $app->post( "logout", ["uses" => "AuthController@logout"] );
    $app->get( "refresh", ["uses" => "AuthController@refresh"] ); 
    $app->post( "refresh", ["uses" => "AuthController@refresh"] );
    $app->get( "profile", ["uses" => "AuthController@me"] );
    $app->post( "tanaman", ["uses" => "TanamanController@postTanaman"]);
    
    $app->post( "post", ["uses" => "ForumController@createPost"]);
    $app->get( "post/{postId}/likes", ["uses" => "ForumController@createLike"]);
    $app->post( "post/{postId}/comments", ["uses" => "ForumController@createComment"]);
    $app->post( "post/{postId}/shares", ["uses" => "ForumController@createShare"]);
    $app->post( "post/{postId}/bookmarks", ["uses" => "ForumController@createBookmark"]);

    $app->get( "tanaman", ["uses" => "TanamanController@listTanaman"]);
  });