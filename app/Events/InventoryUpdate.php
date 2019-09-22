<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Sale;
use \Carbon\Carbon;
use DB;

class InventoryUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $main_statistics;
    public $histo1;
    public $histo2;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $today = Carbon::today();
        $lastWeek = Carbon::today()->subDays(7);
        $lastMonth = Carbon::today()->subMonths(1);
        $lastYear = Carbon::today()->subMonths(12);
        
        $this->main_statistics = ["product_sold"=> [
                                        "today" => $this->getProductSolde($today) ,
                                        "last_week" => $this->getProductSolde($lastWeek),
                                        "last_month" =>$this->getProductSolde($lastMonth) ,
                                        "last_year" =>$this->getProductSolde($lastYear) 
                                        ],
                                        "gross_profit"=> [
                                            "today" => $this->getGrossProfit($today),
                                            "last_week" => $this->getGrossProfit($lastWeek),
                                            "last_month" =>$this->getGrossProfit($lastMonth) ,
                                            "last_year" =>$this->getGrossProfit($lastYear) 
                                            ],
                                        "delivery_orders"=> [
                                            "today" => $this->getDeliveryOrders($today),
                                            "last_week" => $this->getDeliveryOrders($lastWeek),
                                            "last_month" =>$this->getDeliveryOrders($lastMonth) ,
                                            "last_year" =>$this->getDeliveryOrders($lastYear) 
                                            ],
                                        "customers"=> [
                                            "today" => $this->getCustomers($today),
                                            "last_week" => $this->getCustomers($lastWeek),
                                            "last_month" =>$this->getCustomers($lastMonth) ,
                                            "last_year" =>$this->getCustomers($lastYear) 
                                        ],];
        $this->histo1 = $this->histogram1();
        $this->histo2 = $this->histogram2();

        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('statistics');
    }


    public function getProductSolde($date){
        $total_quantity_sold =  DB::table('sale_batches')
                   ->join("sales","sales.id","=","sale_id")
                   ->where("sales.created_at",">=",$date)
                   ->where("paid","=",true)
                    ->select(DB::raw("sum(quantity) as total_quantity_sold"))
                    ->get()->first()->total_quantity_sold;
        return intval($total_quantity_sold);
    }

    public function getGrossProfit($date){
        return Sale::where('created_at',">=",$date)
                    ->groupBy('paid')
                    ->having('paid','=',true)
                    ->sum("total_price");
    }

    public function getDeliveryOrders($date){
        return Sale::where('created_at',">=",$date)
                    ->where("paid","=",false)
                    ->where('delivery','=',true)
                    ->count("delivery");
    }

    public function getCustomers($date){
        return DB::table("roles")->where("roles.name","=","customer")
                            ->join("role_user","role_user.role_id","=","id")
                            ->join("users","users.id","=","role_user.user_id")
                            ->where("users.created_at",">=",$date)
                            ->count("users.created_at");
    }

    public function histogram1(){
        $day = array();
       for ($i = 30;$i >=-1;$i--) {
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
        return $day;
 
    }

    public function histogram2(){
        $month = array();
        for ($i = 12;$i >=0;$i--) {
            $month_str = date('F Y', strtotime(Carbon::today()->subMonth($i)->format("Y M")));           
            //$month_int = Carbon::today()->subMonth($i)->format('m');
                
            $month["dates"][$month_str] =   $month_str ;
            $month["data"][$month_str] = 0;
        }
     $sales = Sale::where('created_at',">=",Carbon::today()->subYear(1))
                   ->orderBy('created_at','ASC')
                   ->where("total_price",">",0)
                   ->where('paid',"=",true)
                   ->get()
                   ->groupBy(function($date){
                     return date('F Y', strtotime(Carbon::parse($date->created_at)->format("Y M")));
                   })->map(function($row,$id){
                     return ["total_price" => $row->sum("total_price")];
                   });
  
       foreach ($sales as $key => $value) {
           $month["data"][$key] = $value["total_price"];
           
       }
       $month["dates"] = array_values($month["dates"]);    
       $month["data"] = array_values($month["data"]);    
       return $month ;
        
 
    }

    
}
