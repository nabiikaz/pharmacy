<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
use App\Medicine;
class Batche extends Model
{
    //
    public $timestamps = false;

    protected $appends = ['expire_in'];

    public function getExpireInAttribute() {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $this->fabrication_date);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $this->expiry_date);

        return $to->diffInDays($from);
    }

    public function Sales(){
        return $this->belongsToMany(Sale::class,'sale_batches');
    }

    public function Medicine(){
        return $this->belongsTo(Medicine::class);
    }

   
}
