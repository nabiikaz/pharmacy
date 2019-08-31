<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User as Customer;
use App\Http\Resources\Customer as CustomerResource;

class CustomerController extends Controller
{
    public $model = "customers";
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
            $selected_column = 'name';

        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        if($request->has("page"))
            $Customer = Customer::where($selected_column,"like", "%".$search."%")
                        ->orderBy($selected_column,$orderby)->paginate(6);
        else
        $Customer = Customer::where($selected_column,"like", "%".$search."%")
                        ->orderBy($selected_column,$orderby)->paginate(100);

        for ($i=0; $i < count($Customer); $i++) { 
            //dd($Customer[$i]->roles()->first()->name);
            if($Customer[$i]->roles()->first()["name"] != "customer")
                $Customer->forget($i);
            
        }

        return CustomerResource::Collection($Customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //test if the request has the image : 


                //validate email and tel uniqueness
                $request->validate([
                    'email' => 'email|unique:users,email',
                    'name'  => 'required|min:8|max:25',
                    'tel'   => 'required',
                    'address' => 'required',

                ]);

                

                //add new customer
                $customer = Customer::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'tel' => $request->tel,
                    'address' => $request->address,
                    'password' => Hash::make($request->email),

                ]);

                //attach customer (user)
                $customer->attachRole("customer");

                
                
                return new CustomerResource($customer) ;
            
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $Customer = Customer::findOrFail($customer->id);
        
        if($Customer->roles()->first()["name"] != "customer")
            abort(404);
            
        return new CustomerResource($Customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        

            if($request->email != $customer->email)
                $request->validate(['email' => 'email|unique:users,email']);


            $request->validate([
                
                'password' => 'min:8|max:16'

            ]);
            

            

            if($request->has("password"))
                $customer->password = Hash::make($request->get("password"));


            

        
        
        //update the Customer
        $customer->update($request->all());
       
       

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
