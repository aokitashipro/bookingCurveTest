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

Route::get('/', 'BookingcurvesController@index')->name('bookingcurve_index');

Route::post('/import', 'BookingcurvesController@import')->name('bookingcurve_import');

Route::post('/export', 'BookingcurvesController@export')->name('bookingcurve_export');
