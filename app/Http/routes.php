<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Main index
Route::get('/', 'HomeController@index');

// Home
Route::get('home', function() {
    return redirect('/');
});

// ITL
Route::get('itl', 'EraserController@itlIndex');
Route::post('itl','EraserController@itlStore');

// CTL
Route::get('ctl', 'EraserController@ctlIndex');
Route::post('ctl','EraserController@ctlStore');

//Bulk
Route::resource('bulk','BulkController',
    ['except' => ['destroy','edit']]
);

//SQL
Route::get('sql/history', 'SqlController@history');
Route::resource('sql','SqlController',
    ['except' => ['destroy','edit']]
);

//Phones
Route::resource('phone', 'PhoneController');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
