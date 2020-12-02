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
Route::group(['middleware' => 'localization'], function () {
    Route::group(['middleware'=> 'jwt.verify'], function () {
        Route::group(['middleware' => 'check.accesstoken','namespace'=>'API'], function () {
            Route::get('jobs/{id}/save', 'SaveJobController@saveJob');
            // Route::get('saveJobs', 'SaveJobController@saveJobList');
            // Route::delete('savedJobs/{id}', 'SaveJobController@saveJobDelete');
            Route::get('jobs', 'JobController@getJobs');
            Route::post('jobs', 'JobController@createJob');
            Route::put('jobs/{id}', 'JobController@updateJob');
            Route::delete('jobs/{id}', 'JobController@deleteJob');
            
            Route::get('jobTypes' , 'JobTypeController@index');
        });
    });
});
