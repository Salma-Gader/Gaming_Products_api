<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::put('update', 'update');
    Route::put('changerole/{user}', 'changeRole')->middleware('permission:assign role');

});
    // Products
    Route::group(['controller' => ProductController::class], function ()
    {
        Route::get('products', 'index')->middleware('permission:show product');
        Route::post('product', 'store')->middleware('permission:add product');
        Route::get('product/{product}', 'show')->middleware('permission:show product');
        Route::put('product/{product}', 'update')->middleware('permission:edit my product|edit every product');
        Route::delete('product/{product}', 'destroy')->middleware('permission:delete my product|delete every product');
        Route::get('product/search/{searching}', 'searchByCategory');
});
    Route::group(['controller' => CategoryController::class], function ()
    {
        Route::get('categories', 'index')->middleware('permission:show category');
        Route::post('category', 'store')->middleware('permission:add category');
        Route::get('category/{category}', 'show')   ;
        Route::put('category/{category}', 'update')->middleware('permission:edit category');
        Route::delete('category/{category}', 'destroy')->middleware('permission:delete category');
});
   // categories
// Route::apiResource('category', CategoryController::class);



