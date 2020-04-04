<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', ['uses' => 'Controller@login']);
Route::post('/login', ['as' => 'auth.login', 'uses' => 'HomeController@doinglogin']);

// Home route
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

// Users routes
Route::get('/user', ['as' => 'user.index', 'uses' => 'UsersController@index']);
Route::post('/user', ['as' => 'user.store', 'uses' => 'UsersController@store']);
