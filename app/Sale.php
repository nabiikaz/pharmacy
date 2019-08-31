<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Batche;
use App\User as Customer;
class Sale extends Model
{
    
    //protected $appends = ["total_price"];

    /**
     * get all batches that got saled in this Sale
     */
    public function Batches(){
        return $this->belongsToMany(Batche::class,"sale_batches")->withPivot("quantity as quantity_sold");

    }

    /**
     * Calculate the Total Price of the medicines Batches purchased 
     */

    public function totalPrice(){
        if($this->total_price != 0)
            return;
        $totalPrice = 0;
        
        foreach ($this->Batches as  $batch) {
            
            $totalPrice +=$batch->quantity_sold * ($batch->unit_price - ($batch->unit_price * $batch->refund_rate)/100) ;
        }
        $this->total_price = $totalPrice;
        $this->save();
    }

   
}
