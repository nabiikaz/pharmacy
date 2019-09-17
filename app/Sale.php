<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
        $thisSale = Sale::findOrFail($this)->first();
        $customerRefund_rate = User::findOrFail($thisSale->customer_id)->refund_rate/100;
        
        foreach ($this->Batches as  $batch) {
            
            $totalPrice +=$batch->quantity_sold * ($batch->unit_price - (($batch->unit_price * $batch->refund_rate)/100)*$customerRefund_rate) ;
        }
        $shipping = 0;
        
        $tax_shipping = Dashboard::orderBy("created_at","DESC")->select("tax","shipping_price")->first();
        if($thisSale->delivery)
            $shipping = $tax_shipping->shipping_price;

        $this->total_price = round($totalPrice+$totalPrice* $tax_shipping->tax /100+$shipping ,2);
        $this->save();
    }

   
}
