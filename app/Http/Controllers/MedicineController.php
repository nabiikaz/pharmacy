<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Medicine;
use App\Http\Resources\Medicine as MedicineResource;
class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selected_column_count = 0;
        if($request->has("selected_column"))
            $selected_column_count = count($request->get("selected_column"));
        $selected_column  = ( $selected_column_count == 0)? "medicine_name":$request->get("selected_column");
        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        $medicines = Medicine::where("medicine_name","like", "%".$search."%")
                    ->orderBy($selected_column,$orderby)
                    ->paginate(5);

        return MedicineResource::collection($medicines);
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
