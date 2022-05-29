<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('users', 'App\Http\Controllers\UserController@index');
Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
Route::post('users', 'App\Http\Controllers\UserController@store');
Route::patch('users/{id}', 'App\Http\Controllers\UserController@update');
Route::delete('users/{id}', 'App\Http\Controllers\UserController@delete');
Route::get('users/{id}/contacts', 'App\Http\Controllers\UserController@showContacts');

Route::get('contacts', 'App\Http\Controllers\ContactController@index');
Route::get('contacts/{id}', 'App\Http\Controllers\ContactController@show');
Route::post('contacts', 'App\Http\Controllers\ContactController@store');
Route::patch('contacts/{id}', 'App\Http\Controllers\ContactController@update');
Route::delete('contacts/{id}', 'App\Http\Controllers\ContactController@delete');
