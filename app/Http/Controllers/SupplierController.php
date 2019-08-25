<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Supplier ;
use App\Http\Resources\Supplier as SupplierResource;

class SupplierController extends Controller
{
    public $model = "suppliers";
    function __construct()
    {
        
        
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $selected_column  = $request->get("selected_column");

        if($selected_column=='')
            $selected_column = 'supplier_name';

        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        $Suppliers = Supplier::where($selected_column,"like", "%".$search."%")
                    ->orderBy($selected_column,$orderby)
                    ->paginate(5);

       

        return SupplierResource::Collection($Suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
       

                //validate email and tel uniqueness
                $request->validate([
                    'email' => 'required|email|unique:suppliers,email',
                    "supplier_name" => 'required|min:8|max:25',
                    "tel" =>"required|min:9|max:10",
                    "address" => "required|max:50"

                ]);


                //add new Supplier
                $Supplier = Supplier::create([
                    'supplier_name' => $request->supplier_name,
                    'address' => $request->address,
                    'tel' => $request->tel,
                    'email' => $request->email
                    

                ]);
                

              
                return new SupplierResource($Supplier) ;
            
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        $Supplier = Supplier::findOrFail($supplier->id);

        return new SupplierResource($Supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
      
        

            if($request->email != $supplier->email)
                $request->validate(['email' => 'email|unique:users,email']);


            $request->validate([
                
                'password' => 'min:8|max:16'

            ]);
            

            

            if($request->has("password"))
                $supplier->password = Hash::make($request->get("password"));


            

        
        //update the supplier
        $supplier->update($request->all());
       
        

        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        
        $supplier->delete();
    }
}
