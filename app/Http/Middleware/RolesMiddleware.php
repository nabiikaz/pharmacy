<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Permission;
use App\Http\Response;
use Illuminate\Support\Facades\Redirect;

class RolesMiddleware
{
    public $httpMethod_Permission = [
        "index" => "read",
        "store" => "create",
        "show" => "read",
        "update"   => "edit",
        "destroy"   => "delete",
    ];
    //$httpMethod stores the Request Method made by the Route
    public $httpMethod = "GET";

    //$Roles stores the Roles array that are allowed by this middleware;
    public $Roles = null;

    //$controller stores the name of the name of the controller that this Middleware is attached to 
    public $controller = null;

    //$controllerMethod stores the name of the controller Method ( function )
    public $controllerMethod = null;

    //$modelName stores the name of the model in which the controller Controlle ;
    public $controllerModel = null;

    public $route_type = "web";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$roles,$route_type="web")
    {
        
        $this->route_type = $route_type;
        
        $this->setRoles($roles);
        $this->getModel();
        $Role = $this->userHasRole();
        

        //if the route does not have a controller ( only a closure ) then return the $request
        if($this->controller == null)
        return $next($request);


        //if this controller is found then continue to the controller without any permisssions check because it only need to be an admin
        if(strpos($this->controller,"RoleController")) 
        return $next($request);
        
        
        //test if the selected role has the Needed Permission ;
        if(!$Role->hasPermission($this->getPermissionNeeded()))
             return $this->ErrorResponse(403,"You don't have the Necessary Permissions.");
        

            
        
       
        return $next($request);
    }

    

    public function ErrorResponse($status,$errorMessage){
        if($this->route_type == "api")
            return Response()->json(["error" => $status , "message"=> $errorMessage],$status);
        else if ($this->route_type == "web")
            abort($status, $errorMessage);
        
        
    }


    /**
     * get the permission needed with the combination of the Model name in the Controller and the httpMethod
     */

    public function getPermissionNeeded(){
        
        
        return $this->httpMethod_Permission[$this->controllerMethod]."-".$this->controllerModel;
    }
    


    /**
     * getModel extract the Controller name and the Controller action Method name and 
     * the important variable : the Model name stored in the controller 
     * which is the name of the model that the permissions should apply onto .
     */
    public function getModel(){

        // get the controller in which this middleware is attached to 
        if(isset(Route::current()->action["controller"])){
            $this->controller = explode("@",Route::current()->action["controller"])[0];
            $this->controllerMethod = explode("@",Route::current()->action["controller"])[1];
            
            if(isset(get_class_vars($this->controller)["model"]))
                $this->controllerModel = get_class_vars($this->controller)["model"];

                
        }
    }

    /**
     * verifyPermissions is a function that verifies if the Authenticated User's Role has the Permissions that Match the Request Method 
     * ex : if the Role's permission is to Update only and the method is (GET) then the Role doesn't have the permission to proceed
     */
    public function verifyPermissions($Role){
        //foreach($Role->permissions as $permission)


    }


    /**
     * test if the authenticated user has at least one Role of the input $Roles
     * if true then return the Role instance else abort with Forbidden status
     */

     public function userHasRole(){
         if($this->Roles == null){
             return;
         }
         foreach($this->Roles as $Role){
             if(Auth::user()->hasRole($Role)){
                 return Auth::user()->validateRole($Role);
             }

         }

         //if this line is reaches that means that the authenticated user doesn't have a role from the input $roles attached to It.
         abort(403, "You Don't Have the Necessary Permissions");
     }
    


    /**
     * setMethod get the method of the incoming request from the handle method and sets it to $httpMethod
     * 
     */

     public function setMethod($request){
         $this->httpMethod = $request->method();
     }

    /**
     * setRoles of an incoming @param roles in the handle method 
     * 
     */
    public function setRoles($roles){
            if($roles == "")
                return;
            $this->Roles  = explode('|',str_replace(" ","",$roles));
            
    }


    
}
