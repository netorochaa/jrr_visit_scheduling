<?php

use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', ['uses' => 'Controller@login']);
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'HomeController@logout']);
Route::get('/home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::post('/home', ['as' => 'auth.login', 'uses' => 'HomeController@index']);

// Users
Route::resource('user', 'UsersController');

// Collector
Route::resource('collector', 'CollectorsController');
Route::post('collector/{collector_id}/neighborhoods',                   ['as' => 'collector.neighborhoods.store', 'uses' => 'CollectorsController@storeCollectorNeighborhoods']);
Route::post('collector/{collector_id}/neighborhoods/{neighborhood_id}', ['as' => 'collector.neighborhoods.detach', 'uses' => 'CollectorsController@detachCollectorNeighborhoods']);

// City
Route::resource('city', 'CitiesController');

// Neighborhood
Route::resource('neighborhood', 'NeighborhoodsController');

// Freedays
Route::resource('freedays', 'FreedaysController');

// patientType
Route::resource('patienttype', 'PatientTypesController');

// cancellationType
Route::resource('cancellationtype', 'CancellationTypesController');

// Collect
Route::resource('collect', 'CollectsController');
Route::get('/collect/reserved', ['as' => 'collect.list.reserved', 'uses' => 'CollectsController@listReserved']);
Route::get('/collect/cancelled', ['as' => 'collect.list.cancelled', 'uses' => 'CollectsController@listCancelled']);
Route::post('/reserve', ['as' => 'collect.reserve', 'uses' => 'CollectsController@reserve']);
Route::get('/schedule/{id}', ['as' => 'collect.schedule', 'uses' => 'CollectsController@schedule']);
Route::get('/confirmed/{id}', ['as' => 'collect.confirmed', 'uses' => 'CollectsController@confirmed']);
Route::get('/cancellation/{id}', ['as' => 'collect.cancellation', 'uses' => 'CollectsController@cancellation']);

// Person
Route::resource('collect.person', 'PeopleController');
Route::post('/people/collect', ['as' => 'person.collect.attach', 'uses' => 'PeopleController@attachPeopleCollect']);
Route::get('/people/{people_id}/collect/{collect_id}', ['as' => 'person.collect.detach', 'uses' => 'PeopleController@detachPeopleCollect']);

//Activity
Route::resource('activity', 'ActivitiesController');
Route::get('activity/{id}/close', ['as' => 'activity.close', 'uses' => 'ActivitiesController@close']);
