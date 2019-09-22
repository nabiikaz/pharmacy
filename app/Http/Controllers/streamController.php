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
     $day = array();
       for ($i = 30;$i >=0;$i--) {
        $day_str = date('F jS, Y', strtotime(Carbon::today()->subDay($i)->format("Y-m-d")));           
         
           
        $day["dates"][$day_str] =   $day_str ;
        $day["data"][$day_str] = 0;
    }
    
      $sales = Sale::join("sale_batches","sale_batches.sale_id","=","sales.id")
                    ->select('sales.id', 'created_at','quantity')
                    ->where('created_at',">=",Carbon::today()->subDay(31))
                    
                    ->get()
                    ->groupBy(function($date) {
                        return date('F jS, Y',strtotime(Carbon::parse($date->created_at)->format("Y-m-d"))); // grouping by days
                    })->map(function ($row,$id) use ($day) {
                        return ['sum' =>intval($row->sum('quantity'))];
                    })/*->keyBy('0')->map(function($row){
                        return $row["sum"];
                    })*/;


        foreach ($sales as $key => $value) {
            $day["data"][$key] = $value["sum"];
            
        }
        $day["dates"] = array_values($day["dates"]);    
        $day["data"] = array_values($day["data"]);    
        return $day ;
      

    }
}
