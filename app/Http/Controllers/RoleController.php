<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\Permission;
use App\User;

class RoleController extends Controller
{
    private $Roles = [
        //admin Role : 
        "admin"=> [
            "users" => ["create","read","edit","delete"],
            "medicines" => ["create","read","edit","delete"],
            "suppliers" => ["create","read","edit","delete"],
            "batches" => ["create","read","edit","delete"],
            "customers" => ["create","read","edit","delete"],
            "purchases" => ["create","read","edit","delete"],
            "sales" => ["create","read","edit","delete"],

            
        ], 
        "moderator"=> [
            "users" => ["read","edit"],
            "medicines" => ["create","read","edit","delete"],
            "suppliers" => ["create","read","edit","delete"],
            "batches" => ["create","read","edit","delete"],
            "customers" => ["create","read","edit","delete"],
            "purchases" => ["create","read","edit","delete"],
            "sales" => ["create","read","edit","delete"],

        ],
        "customer" => [
            "customers" => ["read","edit"]
        ]
        
    ];
    function index() {

        
        $this->createAdmin();
    }

    /**
     * this function reset all permissions from all roles to the new permissions of each role old and new 
     */
    public function reset(){

        //first we detach all permissions from each Role in $this->Roles
        foreach ($this->Roles as  $roleName => $models) {
            $role = $this->validateRole($roleName);

            $role  = $this->validateRole($roleName);
            if($role != null)
            $role->detachPermission();
        }

        //secondly we reattach all the permissions registered in the $this->Roles variable above 
        $this->attachPermissionsToRoles();



    }

    //test if Role with the given $name exist in Roles tables else create and return it else return null
    private function createRole($name){
        $role = Role::where("name","like",$name);
        if($role->count() > 0)
            return $role->first()   ;
        else{
            $role = new Role();
            $role->name = $name;
            $role->display_name = "Pharmacy $name.";
            $role->save();

            return $role;
        }
        return null;
    }

    //test if Permission with the given ($operation and $model name) exist in Permission tables else create it and return it else return null
    private function createPermission($operation,$model){
        $permission = Permission::where("name","like","$operation-$model");
        if($permission->count() > 0)
            return $permission->first();
        else {
            $permission = new Permission();
            $permission->name         = "$operation-$model";
            $permission->save();


            return $permission;
        }
        return null;
    }

    //attach each permission to its corresponding Role according to $this->Roles
    private function attachPermissionsToRoles(){
        
        

        foreach ($this->Roles as  $roleName => $models) {
            //echo "Role : ".$roleName."<br>";

            //create the role if not exist and get it 
            $role  = $this->createRole($roleName);

            foreach($models as  $modelName => $permissions){
                // echo "------>Model : ".$modelName."<br>";
                foreach ($permissions as $permissionOperation ) {
                //echo "------------>Permission : $permissionOperation-$modelName<br>";

                //create the permission  if not exist and get it 
                $permission = $this->createPermission($permissionOperation,$modelName);
                //attach the permission to the role if not already attached
                if(!$role->hasPermission($permission->name))
                    $role->attachPermission($permission->name);
                    
                }
  
                  
  
            }
           
          }
          
        
            
    }

    //create an admin for the first time 
    private function createAdmin(){
        //check if the users table in the database is empty ( that mean this is the admin setup )
        if(User::all()->count() == 0){
            
            //create the Administrator 
            $admin =  User::create([
                'name' => "Administrator",
                'email' => "admin@admin.com",
                'password' => Hash::make("password"),
            
            ]);
            $this->attachPermissionsToRoles();
            $admin->attachRole("admin");
        }else{

            abort(403 , "Unauthorized action.")->redirect()->route("index");
        }
        

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

    public function validatePermission($permission){

        if(is_string($permission))
            $Permission = Permission::where("name","like",$permission)->first();
        else 
            $Permission = Permission::find($permission);

        return $Permission;
       
    }



}
