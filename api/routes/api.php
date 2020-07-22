<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// User
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('logout', 'API\UserController@logout');
// Post
Route::post('posts', 'API\PostController@createPost');
Route::patch('posts/{post}', 'API\PostController@updatePost');
Route::delete('posts/{post}', 'API\PostController@deletePost');
// Route::group(['middleware' => 'auth:api'], function(){
//   Route::post('posts', 'API\PostController@createPost');
// });

// Comment
Route::post('posts/{post}/comments', 'API\CommentController@createComment');
Route::patch('posts/{post}/comments/{comment}', 'API\CommentController@updateComment');
Route::delete('posts/{post}/comments/{comment}', 'API\CommentController@deleteComment');
