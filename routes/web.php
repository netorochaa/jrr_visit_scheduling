<?php

use Illuminate\Support\Facades\Route;

// Auth
Route::get('/', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
Route::get('/home', ['as' => 'auth.home', 'uses' => 'AuthController@dashboard']);
Route::get('/login', 'AuthController@dashboard');
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
Route::post('/login', ['as' => 'auth.login.do', 'uses' => 'AuthController@do']);

// Users
Route::resource('user', 'UsersController');

// Collector
Route::resource('collector', 'CollectorsController');
Route::post('collector/{collector_id}/neighborhoods', ['as' => 'collector.neighborhoods.attach', 'uses' => 'CollectorsController@attachCollectorNeighborhoods']);
Route::post('collector/{collector_id}/neighborhoods/{neighborhood_id}', ['as' => 'collector.neighborhoods.detach', 'uses' => 'CollectorsController@detachCollectorNeighborhoods']);

// City
Route::resource('city', 'CitiesController');

// Neighborhood
Route::resource('neighborhood', 'NeighborhoodsController');

// Freedays
Route::resource('freedays', 'FreeDaysController');

// patientType
Route::resource('patienttype', 'PatientTypesController');

// cancellationType
Route::resource('cancellationtype', 'CancellationTypesController');

// Collect
Route::resource('collect', 'CollectsController');
Route::get('/reserved', ['as' => 'collect.list.reserved', 'uses' => 'CollectsController@listReserved']);
// Route::get('/cancelled', ['as' => 'collect.list.cancelled', 'uses' => 'CollectsController@listCancelled']);
Route::get('/inprogress', ['as' => 'collect.list.inprogress', 'uses' => 'CollectsController@listProgress']);
Route::get('/confirmedlist', ['as' => 'collect.list.confirmedlist', 'uses' => 'CollectsController@listConfirmed']);
// Route::get('/done', ['as' => 'collect.list.done', 'uses' => 'CollectsController@listDone']);
Route::get('/allcollects', ['as' => 'collect.list.allcollects', 'uses' => 'CollectsController@listAll']);
Route::get('/extra', ['as' => 'collect.extra', 'uses' => 'CollectsController@extra']);
Route::get('/transfer/{id}', ['as' => 'collect.transfer', 'uses' => 'CollectsController@transfer']);
Route::post('/reserve', ['as' => 'collect.reserve', 'uses' => 'CollectsController@reserve']);
Route::get('/schedule/{id}', ['as' => 'collect.schedule', 'uses' => 'CollectsController@schedule']);
Route::get('/confirmed/{id}', ['as' => 'collect.confirmed', 'uses' => 'CollectsController@confirmed']);
Route::get('/close/{id}', ['as' => 'collect.close', 'uses' => 'CollectsController@close']);
Route::get('/sendconfirmation/{id}', ['as' => 'collect.sendconfirmation', 'uses' => 'CollectsController@sendconfirmation']);
Route::get('/download/{id}/{archive}', ['as' => 'collect.archive.download', 'uses' => 'CollectsController@download']);
Route::get('/modifyhour/{id}', ['as' => 'collect.modifyhour', 'uses' => 'CollectsController@modifyhour']);

// Collect public
Route::resource('public', 'PublicCollectController');
Route::post('/public/reserve', ['as' => 'collect.public.reserve', 'uses' => 'PublicCollectController@reserve']);
Route::get('/public/schedule/{id}/edit', ['as' => 'collect.public.schedule', 'uses' => 'PublicCollectController@schedule']);
Route::get('/public/schedule/{id}/cancellation', ['as' => 'collect.public.cancellation', 'uses' => 'PublicCollectController@cancellation']);
Route::get('/available', 'PublicCollectController@available');
Route::get('/release', 'PublicCollectController@release');


// Person
Route::resource('collect.person', 'PeopleController');
//Route::resource('person', 'PeopleController');
Route::post('/people/collect', ['as' => 'person.collect.attach', 'uses' => 'PeopleController@attachPeopleCollect']);
Route::get('/people/{people_id}/collect/{collect_id}', ['as' => 'person.collect.detach', 'uses' => 'PeopleController@detachPeopleCollect']);
Route::get('/findperson', 'PeopleController@find');

// Activity
Route::resource('activity', 'ActivitiesController');
Route::get('activity/{id}/close', ['as' => 'activity.close', 'uses' => 'ActivitiesController@close']);

// Report
Route::get('/report/cash', ['as' => 'report.cash', 'uses' => 'ReportController@cash']);
