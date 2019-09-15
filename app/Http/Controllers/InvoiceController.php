<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Dashboard ;
use App\Sale;
use App\Medicine;
use App\User as Customer;

use App\Http\Resources\SaleInvoice as saleInvoiceResource;


class InvoiceController extends Controller
{
    public function salesInvoice(Request $request,$id){

        // Get default limit
    $normalTimeLimit = ini_get('max_execution_time');

    

    //other code

        //first we need to get the sale with $id
        $sale = Sale::findOrFail($id);
    
        
        
        $customer = Customer::findOrFail($sale->customer_id);
        $pharmacist = Customer::findOrFail($sale->user_id)->name;
        //get Store Informations
        $store = Dashboard::orderBy("created_at","DESC")->first();
        
        
        $batches_ids =$sale->Batches()->pluck("batches.id")->toArray();
        $batches_quantities= $sale->Batches->pluck("quantity_sold","id")->toArray();

        $items = Medicine::whereIn("batches.id",$batches_ids)
                            ->join("batches","batches.medicine_id","=","medicines.id")
                            ->join("sale_batches","sale_batches.batche_id","=","batches.id")
                            ->where("sale_batches.sale_id","=",$sale->id)
                            
                            ->select("batches.id as id","medicine_name as name","dosage","family","form","unit_price","sale_batches.quantity as quantity_sold","refund_rate")
                            ->get();
        
        //get tax percentage and shipping default price 
        $tax_shipping = Dashboard::orderBy("created_at","DESC")->select("tax","shipping_price")->first();
        $sub_total = 0;
        if($customer->toArray()["refund_rate"] > 0)
            foreach ($items as  $value) {
                $sub_total += $batches_quantities[$value->id] * ($value->unit_price-($value->unit_price * $value->refund_rate/100)*$customer->toArray()["refund_rate"]/100);
            }
        else 
        foreach ($items as  $value) {
            $sub_total += $batches_quantities[$value->id] * $value->unit_price;
            }
            

        //if the sale is a delivery that means shipping_price is to be added to the total
        $total = $sub_total +($sub_total * $tax_shipping->tax / 100);
        if($sale->delivery)
            $total += $tax_shipping->shipping_price;
        
        $InvoiceBatchesDetailed = saleInvoiceResource::collection($items)->additional(['invoice' => [
                            'sub_total' => round($sub_total,2),
                            'tax' => round($sub_total * $tax_shipping->tax /100,2),
                            'shipping' =>  round($tax_shipping->shipping_price,2),
                            'total' => $total
                        ]]);

        
        //return $InvoiceBatchesDetailed->collection;
        //->additional["invoice"]["sub_total"]
        
        $html =  view('invoices.sales',["store"=>$store->toArray(),
                                        "sale"=>$sale->toArray(),
                                        "customer"=>$customer->toArray(),
                                        "pharmacist"=>$pharmacist,
                                        "batches" => $InvoiceBatchesDetailed->collection,
                                        "invoice" => $InvoiceBatchesDetailed->additional["invoice"]])
                                        ->render();
        if($request->has("action"))
            if($request->action == "view")
                return $html;

        

        // Set new limit
        ini_set('max_execution_time', 120); //2minutes
        
        $pdf = PDF::loadHTML($html)->setPaper('A3', 'landscape');
        // Restore default limit
        ini_set('max_execution_time', $normalTimeLimit); 

        if($request->has("action"))
            if($request->action == "download")
                return $pdf->download("invoice.pdf");
            
        
        return $pdf->stream();
    }
}
