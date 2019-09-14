<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use App\Batche;
use App\Medicine;
use App\Dashboard;
use App\Http\Resources\Cart as CartResource;
use Illuminate\Http\Request;

class cartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $client_session = $_COOKIE["client_session"];

        $client_session_array = Cache::get($client_session);
        
        $result = Medicine::whereIn("batches.id",array_keys($client_session_array))
                            ->join("batches","batches.medicine_id","=","medicines.id")
                            ->select("batches.id as id","medicine_name as name","dosage","family","form","quantity_stock","quantity_min","unit_price")
                            ->get();


        //get tax percentage and shipping default price 
        $tax_shipping = Dashboard::orderBy("created_at","DESC")->select("tax","shipping_price")->first();
        $sub_total = 0;
        foreach ($result as $value) {
            $sub_total += $client_session_array[$value->id] * $value->unit_price;
        }
        
        return CartResource::collection($result)->additional(['invoice' => [
                            'sub_total' => round($sub_total,2),
                            'tax' => round($sub_total * $tax_shipping->tax /100,2),
                            'shipping' =>  round($tax_shipping->shipping_price,2),
                            'total' => round(($sub_total +($sub_total * $tax_shipping->tax / 100)+ $tax_shipping->shipping_price),2)
                        ]]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client_session = $_COOKIE["client_session"];

        $client_session_array = Cache::get($client_session);
        if(!$request->has("quantity"))
                $request->quantity = 1;
        if($request->quantity <= 0)
                $request->quantity = 1;

        

        

        
        $client_session_array[$request->id]=$request->quantity;
        Cache::put($client_session,$client_session_array);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $client_session = $_COOKIE["client_session"];

        
        $client_session_array = Cache::get($client_session);
        
        unset($client_session_array[$id]);
        
        Cache::forget($client_session);
        Cache::put($client_session,$client_session_array,(86400 * 30));
       // dd(Cache::get($client_session));
        
        
        
    }
}