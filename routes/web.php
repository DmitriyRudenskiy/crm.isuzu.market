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

