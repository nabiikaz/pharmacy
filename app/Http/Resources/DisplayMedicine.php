<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DisplayMedicine extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->medicine_name,
            "image" => $this->Img,
            "price" => $this->unit_price,
            "refund_rate" => $this->refund_rate,
            "dosage" => $this->dosage,
            "form" => $this->form,
            "family" => $this->family,
            //"quantity" => $this->quantity,
            "fabrication_date" => $this->fabrication_date,
            "expiry_date" => $this->expiry_date,
            
        ];
    }
}
