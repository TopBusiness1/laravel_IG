<?php

Route::group(['middleware' => 'web', 'prefix' => 'dashboard', 'namespace' => 'Modules\Dashboard\Http\Controllers'], function () {
    // Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/{status_id?}', 'DashboardController@index')->name('dashboard');
    // Route::get('/polizzacar', 'DashboardController@getPolizzacar')->name('dashboard.polizzacar');
    Route::get('/polizzacar/{status_id}', 'DashboardController@getPolizzacar')->name('dashboard.polizzacar');
});
