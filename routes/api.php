<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;

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
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('products', 'ProductController@index');
    Route::post('products', 'ProductController@store');
    Route::get('products/{product}', 'ProductController@show');
    Route::put('products/{product}', 'ProductController@update');
    Route::get('products/barcode/{barcode}', 'ProductController@barcode');

    Route::get('stocks', 'StockController@index');
    Route::post('stocks', 'StockController@store');
    Route::put('stocks', 'StockController@update');
    Route::put('stocks/{stock}', 'StockController@update');

    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
    Route::get('users', 'UserController@index')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}', 'UserController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('roles', 'RoleController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
});
