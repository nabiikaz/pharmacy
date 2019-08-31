<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Batche;
use App\Http\Resources\Sale as SaleResource;
class SaleController extends Controller
{
    public $model = "sales";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $selected_column  = $request->get("selected_column");

        switch ($selected_column) {
            case 'pharmacist':
                $selected_column = "pharmacists.name";
                break;
            case 'customer':
                $selected_column = "customers.name";
                break;
            case 'created_at':
                $selected_column = "sales.created_at";
                break;
            case '':
                $selected_column = "sales.created_at";
                break;
            
            default:
                # code...
                break;
        }
       
        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        $sales = Sale::where($selected_column,"like", "%".$search."%")        
                            ->join("users as pharmacists","pharmacists.id","=","sales.user_id")
                            ->join("users as customers","customers.id","=","sales.customer_id")
                            ->select("sales.id","total_price","pharmacists.name as pharmacist","customers.name as customer","sales.created_at as created_at")
                            ->orderBy($selected_column,$orderby)
                            ->paginate(5);

        foreach($sales as $sale)
            $sale->totalPrice();


        return SaleResource::collection($sales);
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
