<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  Illuminate\Support\Collection;
class Purchase extends Model
{
    
    protected $appends = ["total_price"];

    /**
     * get all Medicines Purchased in this Purchase
     */
   public function Medicines(){
    return $this->belongsToMany(Medicine::class,"batches");
   }

   /**
    * retrieve The Supplier Of this Purchase Medicines
    */
   public function Supplier(){
    return $this->belongsTo(Supplier::class);
   }

   /**
    * retrieve all medicines Batches Associated with purchase;
    */
    public function Batches(){
        return $this->belongsToMany(Medicine::class,"batches")->withPivot("fabrication_date as fabrication_date","expiry_date as expiry_date","unit_price as unit_price","batch_price as batch_price","quantity as quantity");

    }

    /**
     * Calculate and return the Total Price of the medicines Batches purchased 
     */

    public function getTotalPriceAttribute(){
        $totalPrice = 0;
        foreach ($this->Batches as  $batch) {
            
            $totalPrice +=$batch->quantity * $batch->batch_price;
        }

        return $totalPrice;
    }
   
}
