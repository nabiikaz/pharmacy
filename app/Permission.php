<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Role;
class Permission extends Model
{
    protected $fillable = [
        'name','display_name','description'
    ];

    /**
     * get All Roles That this Permission Belongs To (or All Roles That Owns this Permission)
     */
    public function Roles(){
        return $this->belongsToMany("App\Role","permission_role");
    }
    





    
    
    
}
