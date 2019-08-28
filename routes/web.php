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

Route::get("purchases",function(){

    //dd(Auth::user()->Suppliers()->attach(App\Supplier::find(1)));
dd(App\Purchase::find(1)->Medicines()->attach([2=>["fabrication_date"=>"2019-08-17",
                                 "expiry_date" => "2019-08-17","unit_price" => 48000,"batch_price" => 30000,"quantity" => 99,"quantity_min" =>0,"refund_rate"=>0 ]]))->withTimestamps();

dd(App\Purchase::find(1)->Batches()->withPivot("fabrication_date","expiry_date","unit_price","batch_price","quantity","quantity_min","refund_rate"));



});

Route::prefix("permissions")->group(function(){
    
    //this route is for initiating registred  roles  and permissions into the system 
    Route::get("init","RoleController@index");
    
    //this route is for reseting all roles and permissions to the new permissions and roles configuration
    Route::get("reset","RoleController@reset");//->middleware("role:admin");
});



Route::get("/",function(){
    return "index page";
})->name("index");


Route::get("logout",function(){
    Auth::logout();
    return redirect()->route("index");
});
//->middleware("role:admin|moderator")
Route::prefix('dashboard')->middleware("auth")->group(function(){
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


     //suppliers  --------------------------------------------------

     Route::get('purchases',function(){
        $id= -1;    
        return view('purchases')->with('id',$id);
    });


    Route::get('purchases/{id}',function($id){
        return view('purchases')->with('id',$id);
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
