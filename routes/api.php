<?php

use Illuminate\Http\Request;

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
Route::prefix("/")->middleware("role:admin|moderator|customer,api","auth")->group(function(){

    Route::apiResource('pharmacists','PharmacistController');
    Route::apiResource('medicines','MedicineController');
    Route::apiResource('suppliers','SupplierController');
    Route::apiResource('batches','BatchController');
    Route::apiResource('customers','CustomerController');
    Route::apiResource('purchases','PurchaseController');
    Route::apiResource('sales','SaleController');


    Route::apiResource('deliveries','deliveryController');
});

Route::apiResource('medicines_','MedicineFrontController');


Route::apiResource("cart","cartController");
Route::post("cart/checkout","cartController@checkout")->middleware("auth");



Route::get("stream","streamController@index");