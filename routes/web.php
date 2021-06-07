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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/fcm', function () {
    return view('firebase');
});
/* Route::get('/send', 'HomeController@senderNotification');
 */Route::get('/send', 'Controller@senderNotification');

Route::post('/fcm_chat', 'FCMController@index');


Auth::routes();

Route::post('/chat', 'HomeController@createChat')->name('chat');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/email', 'HomeController@email')->name('email');
Route::resource('messages', 'MessageController');
Route::post('/facebook/login',"Controller@facebookLogin");
// Route::get('/logout',"Controller@logout");

Route::get('/page','HomeController@page');
