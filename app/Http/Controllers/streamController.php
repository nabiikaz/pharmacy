<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use App\Sale;
use \Carbon\Carbon;
use App\User;
use DB;
class streamController extends Controller
{
    /**
     * index all events in one stream 
     */

    public function index(Request $request){

        

       return event(new \App\Events\InventoryUpdate());
       


        return Db::table("roles")->where("roles.name","=","customer")
                            ->join("role_user","role_user.role_id","=","id")
                            ->join("users","users.id","=","role_user.user_id")
                            ->where("users.created_at",">=",$lastMonth)
                            ->count("users.created_at");


       return Sale::select('id', 'created_at')
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->created_at)->format('d'); // grouping by days
                    });
        



      /* return Sale::select('id', 'created_at')
                    ->get()
                    ->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('d'); // grouping by days
                    });;
*/
       return $lastYear;

    }
}
