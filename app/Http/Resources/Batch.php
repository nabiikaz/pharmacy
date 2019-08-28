<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Batch extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "id" => $this->id,
            "fabrication_date" => $this->fabrication_date,
            "expiry_date" => $this->expiry_date,
            "unit_price" => $this->unit_price,
            "batch_price" => $this->batch_price,
            "quantity" => $this->quantity,
            "quantity_stock" => $this->quantity_stock,
            "quantity_min" => $this->quantity_min,
            "refund_rate" => $this->refund_rate,
            "medicine_name" => $this->medicine_name,
            "dosage" => $this->dosage,
            "form" => $this->form,
            "family" => $this->family,
            "Img" => $this->Img,
            
        ];
    }
}
