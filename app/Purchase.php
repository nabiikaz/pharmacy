<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  Illuminate\Support\Collection;
class Purchase extends Model
{
    

   public function Medicines(){
    return $this->belongsToMany(Medicine::class,"batches");
   }

    public function Batches(){
        return $this->belongsToMany(Medicine::class,"batches")->withPivot("fabrication_date as fabrication_date","expiry_date as expiry_date","unit_price as unit_price","batch_price as batch_price","quantity as quantity");

    }
   
}
