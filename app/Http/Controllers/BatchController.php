<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Purchase;
use App\Sale;
use App\Supplier;
use App\Batche;
use App\Medicine;
use App\user as Customer;
use App\Http\Resources\Batch as BatchResource;
class BatchController extends Controller
{
    public $model = "batches";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       
        
        $selected_column  = $request->get("selected_column");

        if($selected_column=='')
            $selected_column = 'medicine_name';

        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        $batches = Medicine::where($selected_column,"like", "%".$search."%")
        ->join('batches',"batches.medicine_id","=","Medicines.id")
        ->orderBy($selected_column,$orderby)
        ->paginate(5);
        return BatchResource::collection($batches);
                    

    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       /* $request->validate([
            "batches.*.fabrication_date" => 'required|date|before:batches.*.expiry_date',
            "batches.*.expiry_date" => 'required|date|after:batches.*.fabrication_date',

        ],
        [
           "before"=> "please Verify that Fabrication Date Preceed Expiry Date",
           "after" => "please Verify that Expiry Date Is After Fabrication Date",
        ]);*/
        $supplier_id = $request->get("supplier_id");
        $batches = $request->get("batches");
        
        $auth_user = Auth::user();
        $supplier = Supplier::findOrFail($supplier_id);

        $auth_user->Suppliers()->attach($supplier);
        $auth_user = $auth_user->fresh();

        $purchase = Purchase::where("user_id","=",$auth_user->id)
                            ->where("supplier_id","=",$supplier_id)
                            ->orderBy('created_at', 'desc')->first();
        
        $quantity_added_per_medicine = [];
        foreach($batches as $key => $batch){
            $medicine_id = $batch["medicineId"];

            if(!isset($quantity_added_per_medicine[$medicine_id]))
                $quantity_added_per_medicine[$medicine_id] = intval($batch["quantity"]);
            else {
                $quantity_added_per_medicine[$medicine_id] += intval($batch["quantity"]); 
            }




            


            
            $purchase->Medicines()->attach([$medicine_id => ["fabrication_date"=>$batch["fabrication_date"],
            "expiry_date" => $batch["expiry_date"],"unit_price" => $batch["unit_price"],
            "batch_price" => $batch["batch_price"],"quantity" => $batch["quantity"],"quantity_stock" => $batch["quantity"],"quantity_min" =>$batch["quantity_min"],"refund_rate"=>$batch["refund_rate"] ] ]);

            

           
        }
        $purchase = Purchase::find($purchase)->last();

        //update medicine total_quantity to be equals to the sum of the medicine's batches quantity_stock
        foreach ($purchase->Batches as  $Batche) {
            
            $medicine = Medicine::find($Batche->pivot->medicine_id);
            
            Medicine::where('id',$Batche->pivot->medicine_id)
                    ->update(["total_quantity" => $medicine->total_quantity + $quantity_added_per_medicine[$Batche->pivot->medicine_id]]);
            
        }

        



        

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Batche $batch)
    {
        
        
        
        return (new BatchResource(Medicine::join('batches',"batches.medicine_id","=","medicines.id")
                                    ->where("batches.id","=",$batch->id)
                                    ->first()));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $pharmacist = Auth::user();
        
        

        foreach($request->all() as $batch){

            $Batch = Batche::find($batch["id"]);

            $max = $Batch->quantity_stock - $Batch->quantity_min;
            if($batch["quantity"]  > $max)
                return Response()->json([
                    'message' => "Quantity Cannot Exceed the Max:".$max,
                    'batch_id'=> $batch["id"]
                ], 412);
        }

        $pharmacist->Customers()->attach([$customer->id =>["paid"=>true]]);        
        $sale = Sale::where("user_id","=",$pharmacist->id)
                            ->where("customer_id","=",$customer_id)
                            ->orderBy('created_at', 'desc')->first();

        //attach pharmacist with the sale customer 


        //update batches's Quantities and attach batches to the current sale
        foreach($request->all() as $batch){
            
            $Batch = Batche::find($batch["id"]);

           
            
            /*if($batch["quantity"] > $Batch->quantity_stock)
                $batch["quantity"] = $Batch->quantity_stock;*/
            
                $Batch->quantity_stock -= $batch["quantity"];
                $Batch->timestamps = false;
                $Batch->save();

            if($Batch != null)
                $sale->Batches()->attach([$Batch->id => ["quantity" => $batch["quantity"]]]);
            //update Batch's Medicine total_quantity with the quantity of this batch in this Sale
            $medicine = Medicine::where('id',$Batch->Medicine->id)->update(["total_quantity" => $Batch->Medicine->total_quantity - $batch["quantity"]]);
            
        }
        $sales = Sale::all();
        foreach($sales as $sale)
            $sale->totalPrice();

        event(new \App\Events\InventoryUpdate());

        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batche $batch)
    {
        $quantity_stock = $batch->quantity_stock;
        $medicine = Medicine::find($batch->Medicine)->first();
        
        $medicine->total_quantity -= $quantity_stock;
        $medicine->save();
        $batch->delete();
        
        
    }
}
