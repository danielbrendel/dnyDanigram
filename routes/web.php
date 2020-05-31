<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index');
Route::post('/register', 'MainController@register');
Route::post('/login', 'MainController@login');
Route::any('/logout', 'MainController@logout');
Route::get('/about', 'MainController@about');
Route::get('/faq', 'MainController@faq');
Route::get('/tos', 'MainController@tos');
Route::get('/imprint', 'MainController@imprint');

Route::get('/feed', 'PostsController@feed');
Route::get('/t/{hashtag}', 'PostsController@hashtag');
Route::get('/fetch/posts', 'PostsController@fetchPosts');
Route::get('/fetch/thread', 'PostsController@fetchThread');
Route::get('/fetch/post', 'PostsController@fetchSinglePost');
Route::get('/upload', 'PostsController@viewUpload');
Route::post('/upload', 'PostsController@upload');
Route::get('/p/{id}', 'PostsController@showPost');
Route::post('/p/{id}/thread/add', 'PostsController@addThread');
Route::post('/p/heart', 'PostsController@heartPost');
Route::post('/p/{id}/report', 'PostsController@reportPost');

Route::get('/u/{id}', 'MemberController@show');
Route::get('/profile', 'MemberController@profile');
