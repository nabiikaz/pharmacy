<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Medicine;
use App\Http\Resources\Medicine as MedicineResource;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
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
            $selected_column = 'medicine_name';

        $search = $request->get('search');
        $orderby = ($request->get('filter_flow') == "Descending")? "desc":"asc";
        



        $medicines = Medicine::where($selected_column,"like", "%".$search."%")
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
                $path  = public_path()."/img/medicines/".$filename;

                $decoded = base64_decode($exploded[1]);
                file_put_contents($path,$decoded);
                 //update the medicine
                $medicine = Medicine::create([
                    'dosage' => $request->dosage,
                    'family' => $request->family,
                    'form' => $request->form,
                    'medicine_name' => $request->medicine_name,
                    'description' => "N/A",
                    'Img' => $filename

                ]);

                return new MedicineResource($medicine);
            }





        
       
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

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicine $medicine)
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
                $path  = public_path()."/img/medicines/".$filename;

                $decoded = base64_decode($exploded[1]);
                file_put_contents($path,$decoded);
            }





        
        //update the medicine
        $medicine->update($request->except('image'));

        return new MedicineResource($medicine);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
    }
}
