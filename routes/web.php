<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', ['uses' => 'Controller@login']);
Route::post('/login', ['as' => 'auth.login', 'uses' => 'HomeController@index']);

// Home route
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);

// Users routes
Route::resource('user', 'UsersController');

// Collector routes
Route::resource('collector', 'CollectorsController');
Route::post('collector/{collector_id}/neighborhoods',                   ['as' => 'collector.neighborhoods.store', 'uses' => 'CollectorsController@storeCollectorNeighborhoods']);
Route::post('collector/{collector_id}/neighborhoods/{neighborhood_id}', ['as' => 'collector.neighborhoods.detach', 'uses' => 'CollectorsController@detachCollectorNeighborhoods']);

// City routes
Route::resource('city', 'CitiesController');

// Neighborhood routes
Route::resource('neighborhood', 'NeighborhoodsController');

// Freedays routes
Route::resource('freedays', 'FreedaysController');

// patientType
Route::resource('patienttype', 'PatientTypesController');

// cancellationType
Route::resource('cancellationtype', 'CancellationTypesController');

// Collect
Route::resource('collect', 'CollectsController');
Route::post('/reserve', ['as' => 'collect.reserve', 'uses' => 'CollectsController@reserve']);
Route::get('/schedule/{id}', ['as' => 'collect.schedule', 'uses' => 'CollectsController@schedule']);
Route::get('/confirmed/{id}', ['as' => 'collect.confirmed', 'uses' => 'CollectsController@confirmed']);

// Person
Route::resource('collect.person', 'PeopleController');
Route::post('/people/collect', ['as' => 'person.collect.attach', 'uses' => 'PeopleController@attachPeopleCollect']);
Route::post('/people/{people_id}/collect/{collect_id}', ['as' => 'person.collect.detach', 'uses' => 'PeopleController@detachPeopleCollect']);

//Activity
Route::resource('activity', 'ActivitiesController');
