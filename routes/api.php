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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('products', 'Products\IndexController');
Route::post('orders', 'Orders\CreateController');
Route::get('orders/{order}', 'Orders\ShowController');
Route::post('orders/{order}/lineitems', 'Orders\AddLineItemController');
Route::delete('orders/{order}/lineitems', 'Orders\RemoveLineItemController');
Route::post('orders/{order}/checkout', 'Orders\CheckoutController');
