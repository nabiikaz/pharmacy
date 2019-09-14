<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User as Customer;
use App\User as Pharmacist;

class User extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password','Img','birthday','address','tel','api_token','geo_coord','refund_rate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot(){

        parent::boot();

        /*self::created(function($created_user){
            
            $created_user->attachRole("moderator");
        });*/

    }
    /**
     * get all the sales that were made by this user if he is a (Pharmacist) 
     * or the purchases that were made by this user if he were a (Customer)
     */


    public function Customers(){
        return $this->belongsToMany(Customer::Class,"sales","user_id","customer_id")->withPivot("id as sale_id","created_at as sale_date")->withTimestamps();
    }


    /**
     * get all purchases that are attached to this user
     */
    public function Suppliers(){
        return $this->belongsToMany(Supplier::class,"purchases")->withTimestamps();
    }

     /**
     * get all Roles that are attached to this user
     */

    public function Roles(){
        return $this->belongsToMany(Role::class,"role_user");
    }

    

    /**
     * attach role to this current user
     */
    public function attachRole($role){
        $this->Roles()->attach($this->validateRole($role));
     }

     /**
     * detach Role to user
     */
    public function detachRole($role){
        $this->Roles()->detach($this->validateRole($role)); 
     }
     /**
      * test if the current Authenticated User has the input $role
      */
    public function hasRole($role){
        
        return ($this->Roles->find($this->validateRole($role)) != null)? true:false;
        
    }


    /**
     * validate the input $role whether it is the name of a role that it exist in the the database or an object of the Role class;
     */
    public function validateRole($role){

        if(is_string($role))
            $Role = Role::where("name","like",$role)->first();
        else 
            $Role = Role::find($role);

        return $Role;
       
    }
}
