<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', ['uses' => 'Controller@login']);
Route::post('/login', ['as' => 'auth.login', 'uses' => 'HomeController@doinglogin']);

// Home route
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

// Users routes
// Route::get('/user', ['as' => 'user.index', 'uses' => 'UsersController@index']);
// Route::post('/user', ['as' => 'user.store', 'uses' => 'UsersController@store']);
Route::resource('user', 'UsersController');

// Collector routes
// Route::get('/collector', ['as' => 'collector.index', 'uses' => 'CollectorsController@index']);
// Route::post('/collector', ['as' => 'collector.store', 'uses' => 'CollectorsController@store']);
Route::resource('collector', 'CollectorsController');
Route::post('collecotor/{collector_id}/neighborhoods', ['as' => 'collector.neighborhoods.store', 'uses' => 'CollectorsController@storeCollectorNeighborhoods']);

// City routes
Route::resource('city', 'CitiesController');

// Neighborhood routes
Route::resource('neighborhood', 'NeighborhoodsController');
