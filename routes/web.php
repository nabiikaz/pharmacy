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

Route::prefix('dashboard')->middleware("role:superadministrator|administrator")->group(function(){
    Route::get('/',function(){
        return view('layouts.dashboard');
    });

    

    Route::get('pharmacists',function(){
        return view('users');
    });


    Route::get('medicines',function(){
        return view('medicines');
    });

    //batches --------------------------------------------------------
    Route::get('medicines/batches/{id}',function($id){
        return view('batches',compact('id'));
    });

    Route::get('medicines/batches',function(){
        $id= -1;
        return view('batches')->with('id',$id);
    });

    //sales --------------------------------------------------
    Route::get('medicines/sales',function(){
        $id= -1;    
        return view('sales')->with('id',$id);
    });


    Route::get('medicines/sales/{id}',function($id){
        return view('sales')->with('id',$id);
    });

    //suppliers  --------------------------------------------------

    Route::get('suppliers',function(){
        $id= -1;    
        return view('suppliers')->with('id',$id);
    });


    Route::get('suppliers/{id}',function($id){
        return view('suppliers')->with('id',$id);
    });


    //customers  --------------------------------------------------

    Route::get('customers',function(){
        $id= -1;    
        return view('customers')->with('id',$id);
    });


    Route::get('customers/{id}',function($id){
        return view('customers')->with('id',$id);
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
