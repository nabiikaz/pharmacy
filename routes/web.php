<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('dashboard', function () {
    return view('layouts.dashboard');
});


Route::get('dashboard/users',function(){
    return view('users');
});


Route::get('dashboard/medicines',function(){
    return view('medicines');
});


Route::get('dashboard/medicines/batches/{id}',function($id){
    return view('batches',compact('id'));
});

Route::get('dashboard/medicines/batches',function(){
    $id= -1;
    return view('batches')->with('id',$id);
});


Route::get('dashboard/medicines/sales',function(){
    $id= -1;    
    return view('sales')->with('id',$id);
});


Route::get('dashboard/medicines/sales/{id}',function($id){
    return view('sales')->with('id',$id);
});