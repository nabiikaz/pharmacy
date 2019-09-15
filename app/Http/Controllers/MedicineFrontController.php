<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Medicine;
use App\Batche;
use App\Http\Resources\DisplayMedicine as MedicineResource;

use Illuminate\Support\Facades\Storage;

class MedicineFrontController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = json_decode($request->get("filter"));
        if(empty($filter->forms))
            $filter->forms = array("comprime","collyre","sirop","gelule");
        if($filter->start_date == null)
            $filter->start_date = "2000-01-01";
        if($filter->end_date == null)
            $filter->end_date = "2100-01-01";

        
        $ret =  Batche::where("medicine_name","like", "%".$filter->search."%")
                        ->join('medicines',"medicines.id","=","batches.medicine_id")
                        ->whereBetween("refund_rate",$filter->refund_range_value)
                        ->whereBetween("unit_price",$filter->price_range_value)
                        ->whereBetween("dosage",$filter->dosage_range_value)
                        ->whereBetween("expiry_date",[$filter->start_date,$filter->end_date])
                        ->whereIn("form",$filter->forms)
                        ->where("quantity_stock",">=",0)
                        //->groupBy("medicine_id")
                        ->select("*","batches.id as id")
                        
                        
                        ->paginate(12);
        

        
        //->orderBy($selected_column,$orderby)
        
        return MedicineResource::collection($ret);
    }

    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Medicine $medicine)
    {

        $Medicine = Medicine::findOrFail($medicine->id);
        return new MedicineResource($Medicine);
    }

    

   
}
