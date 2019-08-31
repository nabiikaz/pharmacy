<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
class Batche extends Model
{
    //

    protected $appends = ['expire_in'];

    public function getExpireInAttribute() {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $this->fabrication_date);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $this->expiry_date);

        return $to->diffInDays($from);
    }

    public function Sales(){
        return $this->belongsToMany(Sale::class,'sale_batches');
    }
}
