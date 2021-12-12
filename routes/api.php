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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('products', App\Http\Controllers\API\ProductsAPIController::class);
Route::post('products/subscribe',[\App\Http\Controllers\API\WaitListApiController::class,'toggleSubscribe']);


Route::post('products/{products}/product_variant', [App\Http\Controllers\API\ProductVariantAPIController::class,'store']);
Route::delete('products/product_variant/{product_variant}', [App\Http\Controllers\API\ProductVariantAPIController::class,'destroy']);
