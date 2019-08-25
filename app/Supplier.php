<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    
    public $fillable = [
        "supplier_name","address","tel","email"
    ];
}
