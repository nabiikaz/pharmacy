<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Http\Resources\Purchase as PurchaseResource;
class PurchaseController extends Controller
{
    public $model = "purchases";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $selected_column  = $request->get("selected_column");

        if($selected_column=='')
            $selected_column = 'name';
        else if ($selected_column == "created_at")
            $selected_column = "purchases.created_at";

        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        $purchases = Purchase::where($selected_column,"like", "%".$search."%")        
                            ->join("suppliers","suppliers.id","=","purchases.supplier_id")
                            ->join("users","users.id","=","purchases.user_id")
                            ->select("supplier_name","name","purchases.id","purchases.created_at")
                            ->orderBy($selected_column,$orderby)
                            ->paginate(5);


        return PurchaseResource::collection($purchases);
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
        //
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
