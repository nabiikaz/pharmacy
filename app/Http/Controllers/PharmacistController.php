<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User as Pharmacist;
use App\Http\Resources\Pharmacist as PharmacistResource;

class PharmacistController extends Controller
{
    public $model = "users";
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
        



        $Pharmacists = Pharmacist::where($selected_column,"like", "%".$search."%")
                    ->orderBy($selected_column,$orderby)
                    ->paginate(5);

        for ($i=0; $i < count($Pharmacists); $i++) { 
            //dd($Pharmacists[$i]->roles()->first()->name);
            $tmp = $Pharmacists[$i]->roles()->first()["name"];
            $Pharmacists[$i]->role = $tmp;
        }

        return PharmacistResource::Collection($Pharmacists);
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

            if ($request->has('image')){
                //explode the image and get the extention and base64 
                $exploded = explode(',',$request->get('image'));
                if(str_contains($exploded[0],'jpeg'))
                    $ext = '.jpg';
                
                $filename = str_random().$ext;
                //change the image file name 
                $request->merge(['Img' => $filename]);
                
                //the path in which the image will be saved into 
                $path  = public_path()."/img/avatars/".$filename;

                $decoded = base64_decode($exploded[1]);
                file_put_contents($path,$decoded);

                //validate email and tel uniqueness
                $request->validate([
                    'email' => 'required|email|unique:users,email'

                ]);

                //if(Pharmacist::where("tel","like",$request->tel)->count() > 0)


                //add new pharmacist
                $pharmacist = Pharmacist::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'tel' => $request->tel,
                    'birthday' => $request->birthday,
                    'password' => Hash::make($request->password),
                    'Img' => $filename,

                ]);

                //attach moderator (user)
                $pharmacist->attachRole("moderator");

                //$pharmacist->roles()->first()->name;
                $pharmacist->role = "moderator";
                return new PharmacistResource($pharmacist) ;
            }
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Pharmacist $pharmacist)
    {
        $Pharmacist = Pharmacist::findOrFail($pharmacist->id);
        $Pharmacist->role = $Pharmacist->roles()->first()->name;

        return new PharmacistResource($Pharmacist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pharmacist $pharmacist)
    {
        if (Auth::user()->hasRole("admin") && Auth::user()->id == $pharmacist->id && $request->get("role") == "moderator" )
            return  response()->json(['errors' => 'You cannot change Your Role To moderator'],403);

        //test if the request has the image : 
            if ($request->has('image')){
                //explode the image and get the extention and base64 
                $exploded = explode(',',$request->get('image'));
                if(str_contains($exploded[0],'jpeg'))
                    $ext = '.jpg';
                
                $filename = str_random().$ext;
                //change the image file name 
                $request->merge(['Img' => $filename]);
                
                //the path in which the image will be saved into 
                $path  = public_path()."/img/avatars/".$filename;

                $decoded = base64_decode($exploded[1]);
                file_put_contents($path,$decoded);
            }

            if($request->email != $pharmacist->email)
                $request->validate(['email' => 'email|unique:users,email']);


            $request->validate([
                
                'password' => 'min:8|max:16'

            ]);
            

            

            if($request->has("password"))
                $pharmacist->password = Hash::make($request->get("password"));


            

        
        
        //update the medicine
        $pharmacist->update($request->except('image'));
       
        if(Auth::user()->hasRole("admin") ){
            $pharmacist->detachRole($pharmacist->Roles()->first());
            $pharmacist->attachRole($request->get("role"));
        }

        $pharmacist->role = $pharmacist->Roles()->first()->name;

        return new PharmacistResource($pharmacist);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pharmacist $pharmacist)
    {
        if(Auth::user()->id == $pharmacist->id)
            abort(403,"You Cannot Delete Your Own Account");
        if(!Auth::user()->hasRole("admin"))
            abort(403,"Non Admin Users Cannot Delete Accounts");

        $pharmacist->delete();
    }
}
