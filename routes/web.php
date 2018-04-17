<?php

use Illuminate\Support\Facades\Route;

Route::get('/tJEqL3LABZwq', 'IndexController@index')->name('logout');

Route::group(['middleware' => 'web', 'namespace' => 'Admin'], function () {
    Route::post('login', 'LoginController@login')->name('login_check');
    Route::get('/', 'LoginController@showLoginForm')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin_'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'phones', 'as' => 'phones_'], function () {
        Route::get('/', 'PhonesController@index')->name('index');
        Route::get('add', 'PhonesController@add')->name('add');
        Route::post('insert', 'PhonesController@insert')->name('insert');
        Route::get('view/{id}', 'PhonesController@view')->name('view');
    });

    Route::group(['prefix' => 'info', 'as' => 'info_'], function () {
        Route::get('/region', 'InfoController@region')->name('region');
        Route::get('/phones/days', 'InfoController@day')->name('phones_days');
    });

    Route::group(['prefix' => 'parts', 'as' => 'spare_parts_'], function () {
        Route::get('/', 'SparePartsController@index')->name('index');
        Route::get('add', 'SparePartsController@add')->name('add');
        Route::post('insert', 'SparePartsController@insert')->name('insert');
        Route::get('view/{id}', 'SparePartsController@view')->name('view');
        Route::get('success/{id}', 'SparePartsController@success')->name('success');
    });

    Route::group(['prefix' => 'task', 'as' => 'task_'], function () {
        Route::get('/', 'TaskController@index')->name('index');
        Route::get('add', 'TaskController@add')->name('add');
        Route::post('insert', 'TaskController@insert')->name('insert');
        Route::get('view/{id}', 'TaskController@view')->name('view');
        Route::get('success/{id}', 'TaskController@success')->name('success');

        Route::get('edit/{id}', 'TaskController@edit')->name('edit');
        Route::post('edit', 'TaskController@update')->name('update');
        Route::get('request/{id}', 'TaskController@request')->name('request');
    });



    /*
    Route::get('/info', 'DashboardController@info')->name('info');

    Route::group(['prefix' => 'benefits', 'as' => 'benefits_'], function () {
        Route::get('/',  'BenefitsController@index')->name('index');
        Route::get('add', 'BenefitsController@add')->name('add');
        Route::get('edit/{id}', 'BenefitsController@edit')->name('edit');
        Route::post('insert', 'BenefitsController@insert')->name('insert');
        Route::post('update', 'BenefitsController@update')->name('update');
        Route::post('cover', 'BenefitsController@cover')->name('cover');
        Route::get('hide/{id}', 'BenefitsController@hide')->name('hide');
        Route::get('show/{id}', 'BenefitsController@show')->name('show');
    });
    */
});

