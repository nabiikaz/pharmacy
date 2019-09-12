<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client_session = $_COOKIE["client_session"];

        $client_session_array = Cache::get($client_session);

        //dd(parent::toArray($request));
        //return parent::toArray($request);
        $request = parent::toArray($request);
        return [
            "id" => $request["id"],
            "name" => $request["name"]."  [ ".$request["family"]." ]  ".$request["dosage"]."mg",
            "form" => $request["form"],
            "status" => ($request["quantity_stock"] > $request["quantity_min"] )? "In-stock":"Stock Empty",
            "max"=>  $request["quantity_stock"]-$request["quantity_min"],
            "quantity" => $client_session_array[$request["id"]],
            "price"=>$request["unit_price"],

        ];
    }

   
}
