<?php

use Illuminate\Support\Facades\Route;

Route::get('/test',  'TestController@index');
Route::get('/proposal/order',  'TestController@order')->name('proposal_order');
Route::post('/proposal/order/upload/image',  'ImageController@upload')->name('proposal_upload_image');
Route::post('/pdf/download',  'TestController@download')->name('download_pdf');

Route::get('/admin/docs',  'DocsController@index')->name('admin_docs_index');
Route::post('/admin/docs/add', 'DocsController@add')->name('admin_docs_add');
Route::get('/admin/docs/hide/{id}', 'DocsController@hide')->name('admin_docs_hide');
Route::get('/admin/docs/show/{id}', 'DocsController@show')->name('admin_docs_show');

Route::get('/admin/docs/view/{id}', 'DocsParamsController@index')->name('admin_docs_view');
Route::post('/admin/docs/insert', 'DocsParamsController@insert')->name('admin_docs_insert');
Route::post('/admin/docs/update', 'DocsParamsController@update')->name('admin_docs_update');
Route::post('/admin/docs/import', 'DocsParamsController@import')->name('admin_docs_import');

Route::get('/admin/docs/params/hide/{id}', 'DocsParamsController@hide')->name('admin_docs_params_hide');
Route::get('/admin/docs/params/show/{id}', 'DocsParamsController@show')->name('admin_docs_params_show');

Route::group(['middleware' => 'web', 'namespace' => 'Admin'], function() {
    Route::post('login',  'LoginController@login');
    Route::get('/',  'LoginController@showLoginForm')->name('login');
    Route::get('logout',  'LoginController@logout')->name('logout');
});

Route::group(['middleware' => ['domain', 'auth'], 'prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin_'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');

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

