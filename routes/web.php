<?php

use Illuminate\Support\Facades\Route;

Route::get('/view/{token}/{id}', 'IsuzuController@view')->name('public_show_task');
Route::get('/view/{token}', 'IsuzuController@index');

Route::get('/tJEqL3LABZwq', 'IndexController@index');
Route::get('/task/show/{id}', 'IndexController@show')->name('show_task');

Route::get('/process/{id}', 'Front\ProcessController@index')->name('front_process');
Route::get('/process/view/{id}', 'Front\ProcessController@view')->name('front_process_view');
Route::post('/process/view/task', 'Front\ProcessController@task')->name('front_process_task');
Route::post('/process/copy', 'Front\ProcessController@copy')->name('front_process_copy');
Route::get('/regulations', 'Front\RegulationsController@index')->name('front_regulations');

Route::get('/supervisor', 'Front\SupervisorController@index')->name('front_supervisor');
Route::get('/sale', 'Front\SupervisorController@sale')->name('front_sale');

Route::group(['middleware' => 'web', 'namespace' => 'Admin'], function () {
    Route::post('login', 'LoginController@login')->name('login_check');
    Route::get('/', 'LoginController@showLoginForm')->name('login');
    Route::get('logout', 'LoginController@logout')->name('logout');
});

Route::post('/api/v3/{token}/parts/phone', 'Front\PartsController@index');
Route::get('/api/v3/{token}/parts/phone', 'Front\PartsController@test');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin_'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'isuzu', 'as' => 'isuzu_'], function () {
        Route::get('/', 'IsuzuController@index')->name('index');
        Route::post('/set', 'IsuzuController@set')->name('set');
        Route::get('/add', 'IsuzuController@add')->name('add');
        Route::post('/add', 'IsuzuController@insert')->name('insert');
    });

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

    Route::group(['prefix' => 'process', 'as' => 'process_'], function () {
        Route::get('/', 'ProcessController@index')->name('index');
        Route::get('add', 'ProcessController@add')->name('add');
        Route::post('insert', 'ProcessController@insert')->name('insert');
        Route::get('edit/{id}', 'ProcessController@edit')->name('edit');
        Route::post('update', 'ProcessController@update')->name('update');
        Route::get('task/delete/{id}', 'ProcessController@delete')->name('task_delete');
        Route::get('view/{id}', 'ProcessController@view')->name('view');
        Route::get('success/{id}', 'ProcessController@success')->name('success');
    });

    Route::group(['prefix' => 'regulations', 'as' => 'regulations_'], function () {
        Route::get('/', 'RegulationsController@index')->name('index');
        Route::get('add', 'RegulationsController@add')->name('add');
        Route::post('insert', 'RegulationsController@insert')->name('insert');
        Route::get('view/{id}', 'RegulationsController@view')->name('view');
        Route::get('success/{id}', 'RegulationsController@success')->name('success');
    });

    Route::group(['prefix' => 'users', 'as' => 'users_'], function () {
        Route::get('/', 'UsersController@index')->name('index');
    });
});

