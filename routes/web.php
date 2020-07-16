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
/**
 * Display login page
 */
Route::get('/login', function () {
    return view('login');
})->name('login');

/**
 * Login for user
 */

Route::post('/login','LoginController@login')->name('login');

/**
 * To activate user account
 */

Route::get('/confirm/{token}','LoginController@confirm')->name('confirm');

/**
 * dashboard for authenticated user 
 */
Route::group(['middleware' => 'auth:api'], function() {
       Route::get('/dashboard','LoginController@dashboard');
});