<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as Pharmacist;
use App\Http\Resources\Pharmacist as PharmacistResource;

class PharmacistController extends Controller
{
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
                    'password' => $request->password,
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

        return PharmacistResource($Pharmacist);
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
    public function destroy(Pharmacist $pharmacist)
    {
        $pharmacist->delete();
    }
}
