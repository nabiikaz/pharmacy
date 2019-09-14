<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class SaleInvoice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      

        //dd(parent::toArray($request));
        //return parent::toArray($request);
        $request = parent::toArray($request);
        return [
            "id" => $request["id"],
            "name" => $request["name"],
            "dosage" => $request["dosage"]."mg",
            "family" => $request["family"],
            "form" => $request["form"],
            //"status" => ($request["quantity_stock"] > $request["quantity_min"] )? "In-stock":"Stock Empty",
            //"max"=>  $request["quantity_stock"]-$request["quantity_min"],
            "quantity" => $request["quantity_sold"],
            "price"=>$request["unit_price"],

        ];
    }

   
}
