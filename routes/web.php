<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', ['uses' => 'Controller@login']);
Route::post('/login', ['as' => 'auth.login', 'uses' => 'HomeController@doinglogin']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
