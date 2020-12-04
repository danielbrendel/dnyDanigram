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
Route::get('/clep/index', 'MainController@clep_index');
Route::post('/register', 'MainController@register');
Route::get('/confirm', 'MainController@confirm');
Route::get('/reset', 'MainController@viewReset');
Route::post('/recover', 'MainController@recover');
Route::get('/resend/{id}', 'MainController@resend');
Route::post('/reset', 'MainController@reset');
Route::post('/login', 'MainController@login');
Route::any('/logout', 'MainController@logout');
Route::get('/about', 'MainController@about');
Route::get('/faq', 'MainController@faq');
Route::get('/tos', 'MainController@tos');
Route::get('/imprint', 'MainController@imprint');
Route::get('/news', 'MainController@news');
Route::get('/contact', 'MainController@viewContact');
Route::post('/contact', 'MainController@contact');

Route::get('/feed', 'PostsController@feed');
Route::get('/t/{hashtag}', 'PostsController@hashtag');
Route::get('/fetch/posts', 'PostsController@fetchPosts');
Route::get('/fetch/thread', 'PostsController@fetchThread');
Route::get('/fetch/post', 'PostsController@fetchSinglePost');
Route::get('/upload', 'PostsController@viewUpload');
Route::post('/upload', 'PostsController@upload');
Route::get('/p/{id}', 'PostsController@showPost');
Route::post('/p/{id}/thread/add', 'PostsController@addThread');
Route::post('/heart', 'PostsController@heart');
Route::post('/p/{id}/report', 'PostsController@reportPost');
Route::post('/comment/edit', 'PostsController@editComment');
Route::post('/comment/delete', 'PostsController@deleteComment');
Route::post('/comment/report', 'PostsController@reportComment');
Route::get('/t/{id}/report', 'PostsController@reportTag');

Route::get('/u/{id}', 'MemberController@show');
Route::get('/u/{id}/report', 'MemberController@report');
Route::get('/profile', 'MemberController@profile');
Route::post('/profile/edit', 'MemberController@save');
Route::post('/profile/geo', 'MemberController@saveGeoLocation');
Route::post('/u/deleteownaccount', 'MemberController@deleteOwnAccount');
Route::any('/u/{id}/ignore/add', 'MemberController@addToIgnore');
Route::any('/u/{id}/ignore/remove', 'MemberController@removeFromIgnore');
Route::get('/geosearch', 'MemberController@viewGeosearch');
Route::post('/geosearch', 'MemberController@geosearch');
Route::get('/profilesearch', 'MemberController@viewProfileSearch');
Route::post('/profilesearch', 'MemberController@profilesearch');

Route::post('/f/add', 'FavoritesController@add');
Route::post('/f/remove', 'FavoritesController@remove');

Route::get('/c/subthread', 'PostsController@fetchSubThread');
Route::post('/c/reply', 'PostsController@replyThread');

Route::get('/notifications/list', 'NotificationController@list');
Route::get('/notifications/fetch', 'NotificationController@fetch');

Route::get('/messages', 'MessageController@list');
Route::get('/messages/list', 'MessageController@fetchList');
Route::get('/messages/show/{id}', 'MessageController@show');
Route::get('/messages/create', 'MessageController@create');
Route::post('/messages/send', 'MessageController@send');
Route::any('/messages/unread/count', 'MessageController@unreadCount');

Route::get('/forum', 'ForumController@index');
Route::post('/forum/list', 'ForumController@list');
Route::get('/forum/{id}/show', 'ForumController@show');
Route::post('/forum/{id}/list', 'ForumController@threadList');
Route::get('/forum/thread/{id}/show', 'ForumController@showThread');
Route::post('/forum/thread/{id}/posts', 'ForumController@threadPostings');
Route::post('/forum/thread/create', 'ForumController@createThread');
Route::post('/forum/thread/reply', 'ForumController@replyThread');
Route::post('/forum/thread/edit', 'ForumController@editThread');
Route::get('/forum/thread/post/{id}/show', 'ForumController@showPost');
Route::any('/forum/thread/post/{id}/report', 'ForumController@reportPost');
Route::any('/forum/thread/post/{id}/lock', 'ForumController@lockPost');
Route::post('/forum/thread/post/edit', 'ForumController@editPost');

Route::get('/p/{id}/lock', 'AdminController@lockPost');
Route::get('/p/{id}/togglensfw', 'AdminController@toggleNsfw');
Route::get('/t/{id}/lock', 'AdminController@lockHashtag');
Route::get('/u/{id}/deactivate', 'AdminController@deactivateUser');
Route::get('/c/{id}/lock', 'AdminController@lockComment');

Route::get('/install', 'InstallerController@viewInstall');
Route::post('/install', 'InstallerController@install');

Route::get('/maintainer', 'MaintainerController@index');
Route::post('/maintainer/save', 'MaintainerController@save');
Route::post('/maintainer/faq/create', 'MaintainerController@addFaq');
Route::post('/maintainer/faq/edit', 'MaintainerController@editFaq');
Route::get('/maintainer/faq/{id}/remove', 'MaintainerController@removeFaq');
Route::post('/maintainer/env/save', 'MaintainerController@envSave');
Route::get('/maintainer/u/details', 'MaintainerController@userDetails');
Route::post('/maintainer/u/save', 'MaintainerController@userSave');
Route::post('/maintainer/newsletter', 'MaintainerController@newsletter');
Route::post('/maintainer/themes/add', 'MaintainerController@addTheme');
Route::post('/maintainer/themes/edit', 'MaintainerController@editTheme');
Route::get('/maintainer/themes/delete', 'MaintainerController@deleteTheme');
Route::get('/maintainer/themes/setdefault', 'MaintainerController@setDefaultTheme');
Route::post('/maintainer/logo/save', 'MaintainerController@saveLogo');
Route::get('/maintainer/entity/lock', 'MaintainerController@lockEntity');
Route::get('/maintainer/entity/delete', 'MaintainerController@deleteEntity');
Route::get('/maintainer/entity/safe', 'MaintainerController@setSafeEntity');
Route::post('/maintainer/welcomecontent', 'MaintainerController@welcomeContent');
Route::post('/maintainer/formattedprojectname', 'MaintainerController@saveFormattedProjectName');
Route::post('/maintainer/profileitem/create', 'MaintainerController@createProfileItem');
Route::Post('/maintainer/profileitem/edit', 'MaintainerController@editProfileItem');
Route::any('/maintainer/profileitem/{id}/remove', 'MaintainerController@removeProfileItem');

Route::get('/stories/selection', 'PostsController@selection');
Route::get('/stories/view/{userId}', 'PostsController@story');
Route::post('/stories/add/image', 'PostsController@addStoryImage');
Route::post('/stories/add/text', 'PostsController@addStoryText');
Route::post('/stories/image/upload', 'PostsController@setStoryImage');
Route::get('/stories/expire', 'PostsController@expireStories');

Route::post('/payment/charge', 'PaymentController@charge');
