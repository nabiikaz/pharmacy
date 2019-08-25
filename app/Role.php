<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Permission as Permission;
class Role extends Model
{
    
    protected $fillable = [
        'name','display_name','description'
    ];
    /**
     * get All The Permissions That Are Attached To This Role
     */
    public function Permissions(){
        return $this->belongsToMany(Permission::class,"permission_role");
    }
    
    

    /**
     * attach Permission to this current role
     */
    public function attachPermission($permission){
        
      
        if(!$this->hasPermission($permission))
            $this->Permissions()->attach($this->validatePermission($permission));
     }

     /**
     * detachPermission to role
     */
    public function detachPermission($permission=null){
        //if($this->hasPermission($this->validatePermission($permission)))
        ($permission == null)? $this->Permissions()->detach() : $this->Permissions()->detach($this->validatePermission($permission)); 
     }

    

     /**
      * 
      */
    public function hasPermission($permission){
        return ($this->Permissions->find($this->validatePermission($permission)) != null)? true:false;
        
    }



    public function validatePermission($permission){

        if(is_string($permission))
            $Permission = Permission::where("name","like",$permission)->first();
        else 
            $Permission = Permission::find($permission);

        return $Permission;
       
    }

    
}
