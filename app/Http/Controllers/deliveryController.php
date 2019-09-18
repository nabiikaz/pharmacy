<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale as Deliveries;
use App\Medicine;
class deliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $model = "sales";
    public function index()
    { 
        
        //get all the Deliveries that has not been paided yet which means (has not been delivered yet )
        $deliveries = Deliveries::join("users","users.id","=","customer_id")
                            ->where("delivery",true)
                            ->where("paid",false)
                            ->select("sales.id","users.name as customer_name","total_price","geo_coord","address","tel")
                            ->get();

        foreach($deliveries as $delivery)
            $delivery->totalPrice();
        
        $geo_coord = $deliveries->pluck("geo_coord","id")->toArray();
        $keys =  array_keys($geo_coord);
        for ($i=0; $i < count($geo_coord); $i++) { 
            
            $result = explode(",",$geo_coord[$keys[$i]]);
            if(count($result) == 2){
                $geo_coord[$keys[$i]] = ["lat"=>$result[0],"lng"=>$result[1]];
            }
            else
                unset($geo_coord[$keys[$i]]);
        }
        
        return array("deliveries"=>$deliveries,"geo_coords"=>$geo_coord);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $delivery = Deliveries::findOrFail($id);
        
        
        foreach ($delivery->Batches as $key => $Batch) {
            
            $Batch->quantity_min = $Batch->quantity_min - $Batch->quantity_sold;
            $Batch->quantity_stock = $Batch->quantity_stock - $Batch->quantity_sold;
            $Batch->save();


            $medicine = Medicine::find($Batch->medicine_id);
            
            Medicine::where('id',$Batch->medicine_id)
                    ->update(["total_quantity" => $medicine->total_quantity - $Batch->quantity_sold]);
            
            
            
            

            
        }

        $delivery->paid = true;
        $delivery->save();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
