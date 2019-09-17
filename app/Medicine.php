<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $appends = ["quantity"];
    
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dosage', 'family', 'form','id','medicine_name','description','Img'
    ];

    public function Batches(){
        return $this->belongsToMany(Batche::class,"batches");
    }

    public function getQuantityAttribute(){
        //dd(Medicine::find($this->id)->Batches);
        $batches = Batche::join("medicines","medicines.id","=","batches.medicine_id")
                         ->where("medicines.id","=",$this->id)->get();
        $quantity = 0;
        foreach ($batches as $key => $batch) {
            $quantity = $batch->quantity_stock;
        }
        return $quantity;
    }

}
