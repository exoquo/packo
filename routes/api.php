<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;
use \App\Laravue\Acl;

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

Route::post('products/{product}', 'ProductController@store');

Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Auth routes
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');

    Route::get('/user', 'UserController@showRequest');

    // Api resource routes
    Route::apiResource('roles', 'RoleController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('users', 'UserController');
    Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);

        // Custom routes
    Route::put('users/{user}', 'UserController@update');
    Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' .Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('products', 'ProductController@index');
    Route::post('products', 'ProductController@store');
    Route::get('products/{product}', 'ProductController@show');
    Route::put('products/{product}', 'ProductController@update');
    Route::get('products/barcode/{barcode}', 'ProductController@barcode');

    Route::get('stocks', 'StockController@index');
    Route::post('stocks', 'StockController@store');
    Route::put('stocks', 'StockController@update');
    Route::put('stocks/{stock}', 'StockController@update');

});
