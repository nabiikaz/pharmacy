<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;

class streamController extends Controller
{
    /**
     * index all events in one stream 
     */

    public function index(Request $request){
        $start = time();
        $max_timeout = ini_get('max_execution_time');
        
        $response = new StreamedResponse(function() use ($request,$start,$max_timeout) {
            while(true) {
                //check if the connection time is max out 
                if( time() >= $start+$max_timeout )
                    exit(200);
                $data = array("product_sold"=>["today"=>0]);
               
                echo "data: ".json_encode($data)."\n\n";
                ob_flush();
                flush();
                usleep(2000000);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        $response->headers->set('Cach-Control', 'no-cache');
        return $response;

    }
}
