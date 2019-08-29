<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Purchase;
use App\Supplier;
use App\Batche;
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
        
        return Batche::where($selected_column,"like", "%".$search."%")
                    ->join('medicines',"medicines.id","=","batches.id")
                    ->orderBy($selected_column,$orderby)
                    ->paginate(5)
                    ->toJSON();

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

        foreach($batches as $batch){

            $medicine_id = $batch["medicineId"];
            
            $purchase->Medicines()->attach([$medicine_id => ["fabrication_date"=>$batch["fabrication_date"],
            "expiry_date" => $batch["expiry_date"],"unit_price" => $batch["unit_price"],
            "batch_price" => $batch["batch_price"],"quantity" => $batch["quantity"],"quantity_stock" => $batch["quantity"],"quantity_min" =>$batch["quantity_min"],"refund_rate"=>$batch["refund_rate"] ] ]);
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
        
        //$batch = Batche::findOrFail($batch->id);
        return (new BatchResource(Batche::where("batches.id","=",$batch->id)
        ->join('medicines',"medicines.id","=","batches.id")->first()));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batche $batch)
    {
        $batch->delete();
    }
}
